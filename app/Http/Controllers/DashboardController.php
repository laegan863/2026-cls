<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicensePayment;
use App\Models\LicenseRequirement;
use App\Models\User;
use App\Models\PermitType;
use App\Models\PermitSubType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $twoMonthsFromNow = $now->copy()->addMonths(2);
        $user = Auth::user();
        $role = $user->Role->name;
        $userId = Auth::id();
        $isClient = $role === 'Client';
        
        // Check if user has access to the payment queue
        if (!$user->hasPermission('payment-renewal-queue')) {
            abort(403, 'Unauthorized access to dashboard.');
        }
        
        // Renewal Queue - based on license_payments to track each renewal separately
        $query = LicensePayment::with(['license.client', 'assignedAgent', 'creator'])
            ->whereHas('license', function ($q) {
                $q->whereIn('workflow_status', [
                    License::WORKFLOW_ACTIVE,
                    License::WORKFLOW_PAYMENT_PENDING,
                    License::WORKFLOW_PAYMENT_COMPLETED,
                    License::WORKFLOW_APPROVED,
                ]);
            });
        
        // Role-based filtering: Client can only see their own transactions
        // Admin and Agent can see all payments
        if ($isClient) {
            // Client can only see their own transactions
            $query->whereHas('license', function ($q) use ($userId) {
                $q->where('client_id', $userId);
            });
        }
        // Admin and Agent can see all payments
        
        // Apply filters
        if ($request->filled('expiration_from')) {
            $query->whereHas('license', function ($q) use ($request) {
                $q->where('expiration_date', '>=', $request->expiration_from);
            });
        }
        if ($request->filled('expiration_to')) {
            $query->whereHas('license', function ($q) use ($request) {
                $q->where('expiration_date', '<=', $request->expiration_to);
            });
        }
        if ($request->filled('client_id')) {
            $query->whereHas('license', function ($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }
        if ($request->filled('permit_type')) {
            $query->whereHas('license', function ($q) use ($request) {
                $q->where('permit_type', $request->permit_type);
            });
        }
        if ($request->filled('permit_subtype')) {
            $query->whereHas('license', function ($q) use ($request) {
                $q->where('permit_subtype', $request->permit_subtype);
            });
        }
        if ($request->filled('payment_status')) {
            $query->where('status', $request->payment_status);
        }
        // Assigned agent filter only for Admin (Agent already filtered to their own)
        if ($role === 'Admin' && $request->filled('assigned_agent_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('assigned_agent_id', $request->assigned_agent_id)
                  ->orWhere(function ($fallback) use ($request) {
                      $fallback->whereNull('assigned_agent_id')
                               ->where('created_by', $request->assigned_agent_id);
                  });
            });
        }
        
        // Default sorting by payment creation date (most recent first)
        $renewalQueue = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Build license query base for stats - filter by client if needed
        $licenseBaseQuery = function() use ($isClient, $userId) {
            $query = License::query();
            if ($isClient) {
                $query->where('client_id', $userId);
            }
            return $query;
        };

        $paymentBaseQuery = function() use ($isClient, $userId) {
            $query = LicensePayment::query();
            if ($isClient) {
                $query->whereHas('license', fn($q) => $q->where('client_id', $userId));
            }
            return $query;
        };
        
        // Renewal Status Stats
        $renewalStatusStats = [
            'open' => $licenseBaseQuery()->where('renewal_status', License::RENEWAL_OPEN)->count(),
            'closed' => $licenseBaseQuery()->where('renewal_status', License::RENEWAL_CLOSED)->count(),
            'expired' => $licenseBaseQuery()->where('renewal_status', License::RENEWAL_EXPIRED)->count(),
        ];
        
        // Billing Status Stats - based on payment statuses to match the renewal queue table
        $billingStatusStats = [
            'closed' => $paymentBaseQuery()->where('status', LicensePayment::STATUS_CANCELLED)->count(),
            'pending' => $paymentBaseQuery()->where('status', LicensePayment::STATUS_DRAFT)->count(),
            'open' => $paymentBaseQuery()->where('status', LicensePayment::STATUS_OPEN)->count(),
            'invoiced' => $paymentBaseQuery()->where('status', LicensePayment::STATUS_OPEN)->count(),
            'paid' => $paymentBaseQuery()->where('status', LicensePayment::STATUS_PAID)->count(),
            'overridden' => $paymentBaseQuery()->where('status', LicensePayment::STATUS_OVERRIDDEN)->count(),
        ];
        
        // Workflow Status Stats
        $workflowStatusStats = [
            'pending_validation' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_PENDING_VALIDATION)->count(),
            'requirements_pending' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_REQUIREMENTS_PENDING)->count(),
            'requirements_submitted' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_REQUIREMENTS_SUBMITTED)->count(),
            'approved' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_APPROVED)->count(),
            'active' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_ACTIVE)->count(),
            'payment_pending' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_PAYMENT_PENDING)->count(),
            'payment_completed' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_PAYMENT_COMPLETED)->count(),
            'completed' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_COMPLETED)->count(),
            'rejected' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_REJECTED)->count(),
            'expired' => $licenseBaseQuery()->where('workflow_status', License::WORKFLOW_EXPIRED)->count(),
        ];
        
        // Due Date Stats
        $dueDateStats = [
            'overdue' => $licenseBaseQuery()->where('expiration_date', '<', $now)->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED])->count(),
            'due_this_week' => $licenseBaseQuery()->whereBetween('expiration_date', [$now, $now->copy()->addWeek()])->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED])->count(),
            'due_this_month' => $licenseBaseQuery()->whereBetween('expiration_date', [$now, $now->copy()->addMonth()])->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED])->count(),
            'active' => $licenseBaseQuery()->where('expiration_date', '>', $twoMonthsFromNow)->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED, License::WORKFLOW_EXPIRED])->count(),
        ];

        // Client-specific stats
        $clientStats = [];
        if ($isClient) {
            // Items that need attention - pending requirements for client's licenses
            $pendingRequirements = LicenseRequirement::whereHas('license', fn($q) => $q->where('client_id', $userId))
                ->where('status', LicenseRequirement::STATUS_PENDING)
                ->count();
            
            // Rejected requirements that need re-submission
            $rejectedRequirements = LicenseRequirement::whereHas('license', fn($q) => $q->where('client_id', $userId))
                ->where('status', LicenseRequirement::STATUS_REJECTED)
                ->count();

            // Total stores for this client
            $totalStores = $licenseBaseQuery()->distinct('store_name')->count('store_name');
            
            // Total licenses for this client
            $totalLicenses = $licenseBaseQuery()->count();
            
            $clientStats = [
                'pending_requirements' => $pendingRequirements,
                'rejected_requirements' => $rejectedRequirements,
                'items_needing_attention' => $pendingRequirements + $rejectedRequirements,
                'total_stores' => $totalStores,
                'total_licenses' => $totalLicenses,
            ];
        }
        
        // New Users and Stores Stats (this month) - Admin only
        $startOfMonth = $now->copy()->startOfMonth();
        $newUsersStats = [
            'this_month' => User::whereHas('role', fn($q) => $q->where('slug', 'client'))
                ->where('created_at', '>=', $startOfMonth)->count(),
            'total' => User::whereHas('role', fn($q) => $q->where('slug', 'client'))->count(),
        ];
        
        $newStoresStats = [
            'this_month' => License::where('created_at', '>=', $startOfMonth)
                ->distinct('store_name')->count('store_name'),
            'total' => License::distinct('store_name')->count('store_name'),
        ];
        
        // Get filter options
        $clients = User::whereHas('role', function($q) {
            $q->where('slug', 'client');
        })->orderBy('name')->get();
        
        $agents = User::whereHas('role', function($q) {
            $q->whereIn('slug', ['admin', 'agent']);
        })->orderBy('name')->get();
        
        $permitTypes = PermitType::where('is_active', true)->orderBy('permit_type')->get();
        $permitSubTypes = PermitSubType::where('is_active', true)->orderBy('name')->get();
        
        return view('files.dashboard', compact(
            'renewalQueue',
            'renewalStatusStats',
            'billingStatusStats',
            'workflowStatusStats',
            'dueDateStats',
            'clientStats',
            'newUsersStats',
            'newStoresStats',
            'clients',
            'agents',
            'permitTypes',
            'permitSubTypes',
            'isClient'
        ));
    }

    /**
     * Show detailed list based on filter type
     */
    public function details(Request $request, string $type)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $twoMonthsFromNow = $now->copy()->addMonths(2);
        $userId = Auth::id();
        $isClient = $user->Role->name === 'Client';

        $title = '';
        $subtitle = '';
        
        // Base query - filter by client if needed
        $query = License::with(['client']);
        if ($isClient) {
            $query->where('client_id', $userId);
        } elseif (!$user->hasPermission('view-overdue-active-licenses-and-renewal-open')) {
            abort(403, 'Unauthorized access.');
        }

        switch ($type) {
            case 'overdue':
                $title = 'Overdue Licenses';
                $subtitle = 'Licenses that have passed their expiration date';
                $query->where('expiration_date', '<', $now)
                      ->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED]);
                break;

            case 'active':
                $title = 'Active Licenses';
                $subtitle = 'Licenses with more than 2 months until expiration';
                $query->where('expiration_date', '>', $twoMonthsFromNow)
                      ->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED, License::WORKFLOW_EXPIRED]);
                break;

            case 'renewal-open':
                $title = 'Renewal Open';
                $subtitle = 'Licenses currently in renewal window';
                $query->where('renewal_status', License::RENEWAL_OPEN);
                break;

            case 'renewal-closed':
                $title = 'Renewal Closed';
                $subtitle = 'Licenses with closed renewals';
                $query->where('renewal_status', License::RENEWAL_CLOSED);
                break;

            case 'renewal-expired':
                $title = 'Renewal Expired';
                $subtitle = 'Licenses with expired renewals';
                $query->where('renewal_status', License::RENEWAL_EXPIRED);
                break;

            case 'due-this-week':
                $title = 'Due This Week';
                $subtitle = 'Licenses expiring within the next 7 days';
                $query->whereBetween('expiration_date', [$now, $now->copy()->addWeek()])
                      ->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED]);
                break;

            case 'due-this-month':
                $title = 'Due This Month';
                $subtitle = 'Licenses expiring within the next 30 days';
                $query->whereBetween('expiration_date', [$now, $now->copy()->addMonth()])
                      ->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED]);
                break;

            case 'items-needing-attention':
                $title = 'Items Needing Attention';
                $subtitle = 'Requirements that need your action - pending or rejected submissions';
                
                $requirementsQuery = LicenseRequirement::with(['license.client', 'creator']);
                if ($isClient) {
                    $requirementsQuery->whereHas('license', fn($q) => $q->where('client_id', $userId));
                }
                $requirements = $requirementsQuery
                    ->whereIn('status', [LicenseRequirement::STATUS_PENDING, LicenseRequirement::STATUS_REJECTED])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
                    
                return view('files.dashboard-details-requirements', compact('requirements', 'title', 'subtitle', 'type', 'isClient'));

            case 'my-stores':
                if (!$isClient) {
                    abort(404, 'Invalid filter type');
                }
                $title = 'My Stores';
                $subtitle = 'All stores registered under your account';
                $query->whereNotNull('store_name')
                      ->orderBy('store_name');
                break;

            case 'my-licenses':
                if (!$isClient) {
                    abort(404, 'Invalid filter type');
                }
                $title = 'My Licenses';
                $subtitle = 'All licenses under your account';
                break;

            case 'new-users':
                if ($isClient) {
                    abort(403, 'Unauthorized access.');
                }
                $title = 'New Users This Month';
                $subtitle = 'Users who signed up this month';
                $startOfMonth = $now->copy()->startOfMonth();
                $users = User::with('role')
                    ->whereHas('role', fn($q) => $q->where('slug', 'client'))
                    ->where('created_at', '>=', $startOfMonth)
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);
                return view('files.dashboard-details-users', compact('users', 'title', 'subtitle', 'type'));

            case 'new-stores':
                if ($isClient) {
                    abort(403, 'Unauthorized access.');
                }
                $title = 'New Stores This Month';
                $subtitle = 'Stores registered this month';
                $startOfMonth = $now->copy()->startOfMonth();
                $query->where('created_at', '>=', $startOfMonth)
                      ->whereNotNull('store_name')
                      ->orderBy('created_at', 'desc');
                break;

            default:
                abort(404, 'Invalid filter type');
        }

        $licenses = $query->orderBy('expiration_date', 'asc')->paginate(15);

        return view('files.dashboard-details', compact('licenses', 'title', 'subtitle', 'type', 'isClient'));
    }
}
