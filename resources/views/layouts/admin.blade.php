<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Blueside</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <span class="sidebar-brand-text">Blue<span>side</span></span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-title">Main Menu</div>

            <a href="{{ url('/admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ url('/admin/profile') }}" class="nav-link {{ request()->is('admin/profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i>
                <span>Profile</span>
            </a>

            <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#formsSubmenu"
                aria-expanded="{{ request()->is('admin/forms*') ? 'true' : 'false' }}">
                <i class="fab fa-wpforms"></i>
                <span>Forms</span>
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <ul class="nav-submenu collapse {{ request()->is('admin/forms*') ? 'show' : '' }}" id="formsSubmenu">
                <li>
                    <a href="{{ url('/admin/forms') }}"
                        class="nav-link {{ request()->is('admin/forms') ? 'active' : '' }}">Form Elements</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Form Layouts</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Validation</a>
                </li>
            </ul>

            <a href="{{ url('/admin/ui-components') }}" class="nav-link {{ request()->is('admin/ui-components') ? 'active' : '' }}">
                <i class="fas fa-puzzle-piece"></i>
                <span>Components</span>
            </a>

            <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#uiSubmenu"
                aria-expanded="{{ request()->is('admin/ui-components') ? 'true' : 'false' }}">
                <i class="bi bi-palette-fill"></i>
                <span>UI Elements</span>
                <i class="bi bi-chevron-right arrow"></i>
            </a>
            <ul class="nav-submenu collapse {{ request()->is('admin/ui-components') ? 'show' : '' }}" id="uiSubmenu">
                <li>
                    <a href="{{ url('/admin/ui-components') }}" class="nav-link {{ request()->is('admin/ui-components') ? 'active' : '' }}">Component Library</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Buttons</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Badges</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Alerts</a>
                </li>
                <li>
                    <a href="#" class="nav-link">Progress</a>
                </li>
            </ul>

            <div class="nav-section-title">Apps</div>

            <a href="#" class="nav-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-cart3"></i>
                <span>Orders</span>
                <span class="badge">24</span>
            </a>

            <a href="#" class="nav-link">
                <i class="fas fa-box-open"></i>
                <span>Products</span>
            </a>

            <a href="#" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span>Customers</span>
            </a>

            <a href="#" class="nav-link">
                <i class="fas fa-comments"></i>
                <span>Messages</span>
                <span class="badge">3</span>
            </a>

            <div class="nav-section-title">Settings</div>

            <a href="#" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>

            <a href="#" class="nav-link">
                <i class="fas fa-question-circle"></i>
                <span>Help Center</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-footer-user">
                <div class="sidebar-footer-avatar">
                    <img src="https://ui-avatars.com/api/?name=John+Doe&background=d4a94c&color=1a2b4a&bold=true"
                        alt="John Doe">
                </div>
                <div class="sidebar-footer-info">
                    <span class="sidebar-footer-name">John Doe</span>
                    <span class="sidebar-footer-role">Administrator</span>
                </div>
                <div class="sidebar-footer-actions">
                    <button class="sidebar-footer-btn" title="Settings">
                        <i class="fas fa-cog"></i>
                    </button>
                    <button class="sidebar-footer-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
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

        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5.3 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle?.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });

        sidebarOverlay?.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });

        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 &&
                !sidebar.contains(e.target) &&
                !sidebarToggle?.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });

        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(element) {
            element.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
