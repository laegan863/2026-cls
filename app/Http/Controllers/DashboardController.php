<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicensePayment;
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
        if (!$user->hasPermission('view-overdue-active-licenses-and-renewal-open')) {
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
        
        // Stats for charts
        $allLicenses = License::query();
        
        // Renewal Status Stats
        $renewalStatusStats = [
            'open' => License::where('renewal_status', License::RENEWAL_OPEN)->count(),
            'closed' => License::where('renewal_status', License::RENEWAL_CLOSED)->count(),
            'expired' => License::where('renewal_status', License::RENEWAL_EXPIRED)->count(),
        ];
        
        // Billing Status Stats - based on payment statuses to match the renewal queue table
        $billingStatusStats = [
            'closed' => LicensePayment::where('status', LicensePayment::STATUS_CANCELLED)->count(),
            'pending' => LicensePayment::where('status', LicensePayment::STATUS_DRAFT)->count(),
            'open' => LicensePayment::where('status', LicensePayment::STATUS_OPEN)->count(),
            'invoiced' => LicensePayment::where('status', LicensePayment::STATUS_OPEN)->count(), // invoiced same as open for payments
            'paid' => LicensePayment::where('status', LicensePayment::STATUS_PAID)->count(),
            'overridden' => LicensePayment::where('status', LicensePayment::STATUS_OVERRIDDEN)->count(),
        ];
        
        // Workflow Status Stats
        $workflowStatusStats = [
            'pending_validation' => License::where('workflow_status', License::WORKFLOW_PENDING_VALIDATION)->count(),
            'requirements_pending' => License::where('workflow_status', License::WORKFLOW_REQUIREMENTS_PENDING)->count(),
            'requirements_submitted' => License::where('workflow_status', License::WORKFLOW_REQUIREMENTS_SUBMITTED)->count(),
            'approved' => License::where('workflow_status', License::WORKFLOW_APPROVED)->count(),
            'active' => License::where('workflow_status', License::WORKFLOW_ACTIVE)->count(),
            'payment_pending' => License::where('workflow_status', License::WORKFLOW_PAYMENT_PENDING)->count(),
            'payment_completed' => License::where('workflow_status', License::WORKFLOW_PAYMENT_COMPLETED)->count(),
            'completed' => License::where('workflow_status', License::WORKFLOW_COMPLETED)->count(),
            'rejected' => License::where('workflow_status', License::WORKFLOW_REJECTED)->count(),
            'expired' => License::where('workflow_status', License::WORKFLOW_EXPIRED)->count(),
        ];
        
        // Due Date Stats
        $dueDateStats = [
            'overdue' => License::where('expiration_date', '<', $now)->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED])->count(),
            'due_this_week' => License::whereBetween('expiration_date', [$now, $now->copy()->addWeek()])->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED])->count(),
            'due_this_month' => License::whereBetween('expiration_date', [$now, $now->copy()->addMonth()])->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED])->count(),
            'active' => License::where('expiration_date', '>', $twoMonthsFromNow)->whereNotIn('workflow_status', [License::WORKFLOW_COMPLETED, License::WORKFLOW_REJECTED, License::WORKFLOW_EXPIRED])->count(),
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
            'clients',
            'agents',
            'permitTypes',
            'permitSubTypes'
        ));
    }
}
