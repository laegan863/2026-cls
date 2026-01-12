<header class="top-header">
    <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none p-2" id="sidebarToggle" type="button">
            <i class="bi bi-list fs-4"></i>
        </button>
        <div class="header-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search anything...">
        </div>
    </div>

    <div class="header-actions">
        <!-- Notifications Dropdown -->
        @php
            $notifications = Auth::user()->notifications()->latest()->take(10)->get();
            $unreadCount = Auth::user()->unreadNotifications()->count();
        @endphp
        <div class="dropdown">
            <button class="header-action-btn" title="Notifications" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                @if($unreadCount > 0)
                    <span class="notification-dot"></span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0 mt-2 shadow-lg border-0" style="width: 380px;">
                <!-- Header -->
                <div class="notification-header px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">Notifications</h6>
                        <small class="text-white-50">
                            @if($unreadCount > 0)
                                You have {{ $unreadCount }} unread {{ Str::plural('notification', $unreadCount) }}
                            @else
                                All caught up!
                            @endif
                        </small>
                    </div>
                    @if($unreadCount > 0)
                        <span class="badge bg-white text-primary fw-semibold px-2 py-1">{{ $unreadCount }} New</span>
                    @endif
                </div>
                
                <!-- Notification Items -->
                <div class="notification-list" style="max-height: 360px; overflow-y: auto;">
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
                                'license_created' => 'gold',
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
                        <a href="{{ $notification->data['url'] ?? '#' }}" 
                           class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}"
                           onclick="markNotificationRead('{{ $notification->id }}')">
                            <div class="notification-icon-wrapper">
                                <div class="notification-icon {{ $iconClass }}">
                                    <i class="bi {{ $icon }}"></i>
                                </div>
                                @if(is_null($notification->read_at))
                                    <span class="notification-status"></span>
                                @endif
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">{{ $title }}</div>
                                <div class="notification-text">{{ Str::limit($notification->data['message'] ?? '', 50) }}</div>
                                <div class="notification-time">
                                    <i class="bi bi-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="notification-actions">
                                @if(is_null($notification->read_at))
                                    <button class="btn-notification-action" title="Mark as read" onclick="event.preventDefault(); markNotificationRead('{{ $notification->id }}')">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                @else
                                    <button class="btn-notification-action" title="Delete" onclick="event.preventDefault(); deleteNotification('{{ $notification->id }}')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-bell-slash fs-1 text-muted"></i>
                            </div>
                            <h6 class="text-muted mb-1">No notifications</h6>
                            <small class="text-muted">You're all caught up!</small>
                        </div>
                    @endforelse
                </div>
                
                <!-- Footer -->
                @if($notifications->count() > 0)
                <div class="notification-footer px-4 py-3 border-top d-flex justify-content-between align-items-center">
                    @if($unreadCount > 0)
                        <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-check2-all me-1"></i> Mark all read
                            </button>
                        </form>
                    @else
                        <span></span>
                    @endif
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-primary">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
        <div class="header-profile dropdown">
            <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="header-profile-avatar">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=d4a94c&color=1a2b4a&bold=true"
                        alt="John Doe">
                </div>
                <div class="header-profile-info d-none d-md-block">
                    <span class="header-profile-name">John Doe</span>
                    <span class="header-profile-role">Administrator</span>
                </div>
                <i class="bi bi-chevron-down text-muted small"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-lg">
                <li class="px-3 py-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar"
                            style="background: linear-gradient(135deg, var(--bs-gold) 0%, var(--bs-gold-dark) 100%); color: var(--bs-primary-dark);">
                            JD</div>
                        <div>
                            <div class="fw-semibold">John Doe</div>
                            <small class="text-muted">john@blueside.com</small>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ url('/admin/profile') }}">
                        <i class="fas fa-user me-2 text-muted"></i>
                        My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="#"><i class="fas fa-cog me-2 text-muted"></i>
                        Account Settings
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="#">
                        <i class="fas fa-file-invoice me-2 text-muted"></i> 
                        Billing
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> 
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>