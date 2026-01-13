@extends('layouts.index')

@section('title', 'Notifications')

@section('content')
    <x-page-header title="Notifications" subtitle="View all your notifications">
        @if(Auth::user()->unreadNotifications()->count() > 0)
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <x-button type="submit" variant="outline-gold" icon="bi bi-check2-all">Mark All as Read</x-button>
            </form>
        @endif
    </x-page-header>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            @forelse($notifications as $notification)
                @php
                    $type = $notification->data['type'] ?? 'general';
                    $iconClass = match($type) {
                        'payment_created' => 'primary',
                        'payment_completed', 'payment_received' => 'success',
                        'requirement_added' => 'info',
                        'requirement_submitted' => 'primary',
                        'requirement_approved', 'license_approved' => 'success',
                        'requirement_rejected', 'license_rejected' => 'danger',
                        'license_created' => 'warning',
                        'license_expiring' => 'warning',
                        'license_expired' => 'danger',
                        'renewal_open' => 'warning',
                        default => 'secondary',
                    };
                    $icon = match($type) {
                        'payment_created' => 'bi-credit-card-fill',
                        'payment_completed', 'payment_received' => 'bi-check-circle-fill',
                        'requirement_added' => 'bi-file-earmark-plus-fill',
                        'requirement_submitted' => 'bi-file-earmark-arrow-up-fill',
                        'requirement_approved' => 'bi-file-earmark-check-fill',
                        'requirement_rejected' => 'bi-file-earmark-x-fill',
                        'license_created' => 'bi-file-earmark-text-fill',
                        'license_approved' => 'bi-patch-check-fill',
                        'license_rejected' => 'bi-x-circle-fill',
                        'license_expiring' => 'bi-exclamation-triangle-fill',
                        'license_expired' => 'bi-calendar-x-fill',
                        'renewal_open' => 'bi-arrow-repeat',
                        default => 'bi-bell-fill',
                    };
                    $title = match($type) {
                        'payment_created' => 'Payment Required',
                        'payment_completed' => 'Payment Completed',
                        'payment_received' => 'Payment Received',
                        'requirement_added' => 'New Requirement',
                        'requirement_submitted' => 'Requirement Submitted',
                        'requirement_approved' => 'Requirement Approved',
                        'requirement_rejected' => 'Requirement Rejected',
                        'license_created' => 'New License Application',
                        'license_approved' => 'License Approved',
                        'license_rejected' => 'License Rejected',
                        'license_expiring' => 'License Expiring Soon',
                        'license_expired' => 'License Expired',
                        'renewal_open' => 'Renewal Window Open',
                        default => 'Notification',
                    };
                @endphp
                <div class="card mb-3 {{ is_null($notification->read_at) ? 'border-start border-primary border-4' : '' }}">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-{{ $iconClass }} bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                                <i class="bi {{ $icon }} fs-5 text-{{ $iconClass }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 {{ is_null($notification->read_at) ? 'fw-bold' : '' }}">
                                            {{ $title }}
                                            @if(is_null($notification->read_at))
                                                <span class="badge bg-primary ms-2">New</span>
                                            @endif
                                        </h6>
                                        <p class="mb-2 text-muted">{{ $notification->data['message'] ?? '' }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                            <span class="mx-2">•</span>
                                            {{ $notification->created_at->format('M d, Y h:i A') }}
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($notification->data['url'] ?? null)
                                            <x-button href="{{ $notification->data['url'] }}" variant="outline-primary" size="sm" icon="bi bi-eye"></x-button>
                                        @endif
                                        @if(is_null($notification->read_at))
                                            <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <x-button type="submit" variant="outline-success" size="sm" icon="bi bi-check2" title="Mark as read"></x-button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="outline-danger" size="sm" icon="bi bi-trash" title="Delete"></x-button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <x-card>
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash fs-1 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">No notifications</h5>
                        <p class="text-muted mb-0">You're all caught up! Check back later for new updates.</p>
                    </div>
                </x-card>
            @endforelse

            @if($notifications->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
