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
        <div class="dropdown">
            <button class="header-action-btn" title="Notifications" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                <span class="notification-dot"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0 mt-2 shadow-lg border-0" style="width: 380px;">
                <!-- Header -->
                <div class="notification-header px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">Notifications</h6>
                        <small class="text-white-50">You have 5 unread messages</small>
                    </div>
                    <span class="badge bg-white text-primary fw-semibold px-2 py-1">5 New</span>
                </div>
                
                <!-- Tabs -->
                <div class="notification-tabs d-flex border-bottom">
                    <button class="notification-tab active flex-fill py-2 border-0 bg-transparent">
                        <i class="bi bi-inbox me-1"></i> All
                    </button>
                    <button class="notification-tab flex-fill py-2 border-0 bg-transparent">
                        <i class="bi bi-cart me-1"></i> Orders
                    </button>
                    <button class="notification-tab flex-fill py-2 border-0 bg-transparent">
                        <i class="bi bi-bell me-1"></i> Alerts
                    </button>
                </div>
                
                <!-- Notification Items -->
                <div class="notification-list" style="max-height: 360px; overflow-y: auto;">
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon-wrapper">
                            <div class="notification-icon primary">
                                <i class="bi bi-cart-check-fill"></i>
                            </div>
                            <span class="notification-status"></span>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New order received</div>
                            <div class="notification-text">Order #1234 from Emma Wilson - $299.00</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> 5 minutes ago
                            </div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-notification-action" title="Mark as read">
                                <i class="bi bi-check2"></i>
                            </button>
                        </div>
                    </a>
                    
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon-wrapper">
                            <div class="notification-icon gold">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <span class="notification-status"></span>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New customer registered</div>
                            <div class="notification-text">Sarah Johnson created an account</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> 15 minutes ago
                            </div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-notification-action" title="Mark as read">
                                <i class="bi bi-check2"></i>
                            </button>
                        </div>
                    </a>
                    
                    <a href="#" class="notification-item unread">
                        <div class="notification-icon-wrapper">
                            <div class="notification-icon success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <span class="notification-status"></span>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Payment confirmed</div>
                            <div class="notification-text">Payment for Order #1230 received</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> 32 minutes ago
                            </div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-notification-action" title="Mark as read">
                                <i class="bi bi-check2"></i>
                            </button>
                        </div>
                    </a>
                    
                    <a href="#" class="notification-item">
                        <div class="notification-icon-wrapper">
                            <div class="notification-icon info">
                                <i class="bi bi-box-seam-fill"></i>
                            </div>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Product shipped</div>
                            <div class="notification-text">Order #1228 is on its way</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> 1 hour ago
                            </div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-notification-action" title="Delete">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </a>
                    
                    <a href="#" class="notification-item">
                        <div class="notification-icon-wrapper">
                            <div class="notification-icon warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            </div>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Low stock alert</div>
                            <div class="notification-text">Premium Widget has only 5 items left</div>
                            <div class="notification-time">
                                <i class="bi bi-clock"></i> 2 hours ago
                            </div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-notification-action" title="Delete">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </a>
                </div>
                
                <!-- Footer -->
                <div class="notification-footer px-4 py-3 border-top d-flex justify-content-between align-items-center">
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-check2-all me-1"></i> Mark all read
                    </a>
                    <a href="#" class="btn btn-sm btn-primary">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
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
                    <a class="dropdown-item py-2 text-danger" href="#">
                        <i class="fas fa-sign-out-alt me-2"></i> 
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>