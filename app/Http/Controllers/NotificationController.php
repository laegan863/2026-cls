<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the current user (paginated)
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        
        return view('files.notifications', compact('notifications'));
    }

    /**
     * Get notifications for header dropdown (API endpoint)
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'general',
                    'title' => $this->getNotificationTitle($notification),
                    'message' => $notification->data['message'] ?? '',
                    'url' => $notification->data['url'] ?? '#',
                    'icon' => $this->getNotificationIcon($notification->data['type'] ?? 'general'),
                    'icon_class' => $this->getNotificationIconClass($notification->data['type'] ?? 'general'),
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => !is_null($notification->read_at),
                    'created_at' => $notification->created_at->format('M d, Y h:i A'),
                ];
            });

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
        }

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification
     */
    public function destroy(string $id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->delete();
        }

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Get notification title based on type
     */
    private function getNotificationTitle($notification): string
    {
        $type = $notification->data['type'] ?? 'general';
        
        return match($type) {
            'payment_created' => 'Payment Required',
            'payment_completed' => 'Payment Completed',
            'payment_received' => 'Payment Received',
            'requirement_added' => 'New Requirement',
            'requirement_submitted' => 'Requirement Submitted',
            'requirement_approved' => 'Requirement Approved',
            'requirement_rejected' => 'Requirement Rejected',
            'requirement_status' => 'Requirement Status Update',
            'license_created' => 'New License Application',
            'license_status' => 'License Status Update',
            'license_approved' => 'License Approved',
            'license_rejected' => 'License Rejected',
            'license_expiring' => 'License Expiring Soon',
            'license_expired' => 'License Expired',
            'license_renewed' => 'License Renewed',
            'renewal_status' => 'Renewal Status Update',
            'renewal_open' => 'Renewal Window Open',
            'billing_status' => 'Billing Status Update',
            default => 'Notification',
        };
    }

    /**
     * Get icon based on notification type
     */
    private function getNotificationIcon(string $type): string
    {
        return match($type) {
            'payment_created' => 'bi-credit-card-fill',
            'payment_completed', 'payment_received' => 'bi-check-circle-fill',
            'requirement_added' => 'bi-file-earmark-plus-fill',
            'requirement_submitted' => 'bi-file-earmark-arrow-up-fill',
            'requirement_approved' => 'bi-file-earmark-check-fill',
            'requirement_rejected' => 'bi-file-earmark-x-fill',
            'requirement_status' => 'bi-file-earmark-text-fill',
            'license_created' => 'bi-file-earmark-text-fill',
            'license_status' => 'bi-arrow-repeat',
            'license_approved' => 'bi-patch-check-fill',
            'license_rejected' => 'bi-x-circle-fill',
            'license_expiring' => 'bi-exclamation-triangle-fill',
            'license_expired' => 'bi-calendar-x-fill',
            'license_renewed' => 'bi-award-fill',
            'renewal_status', 'renewal_open' => 'bi-arrow-repeat',
            'billing_status' => 'bi-receipt',
            default => 'bi-bell-fill',
        };
    }

    /**
     * Get icon class (color) based on notification type
     */
    private function getNotificationIconClass(string $type): string
    {
        return match($type) {
            'payment_created' => 'primary',
            'payment_completed', 'payment_received' => 'success',
            'requirement_added' => 'info',
            'requirement_submitted' => 'primary',
            'requirement_approved', 'license_approved' => 'success',
            'requirement_rejected', 'license_rejected' => 'danger',
            'requirement_status' => 'info',
            'license_created' => 'gold',
            'license_status' => 'primary',
            'license_expiring' => 'warning',
            'license_expired' => 'danger',
            'license_renewed' => 'success',
            'renewal_status', 'renewal_open' => 'warning',
            'billing_status' => 'info',
            default => 'secondary',
        };
    }
}
