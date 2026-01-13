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
        <div class="dropdown" id="notificationDropdown">
            <button class="header-action-btn" title="Notifications" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                <span class="notification-dot" id="notificationDot" style="display: none;"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0 mt-2 shadow-lg border-0" style="width: 380px;">
                <!-- Header -->
                <div class="notification-header px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0 fw-semibold text-white">Notifications</h6>
                        <small class="text-white-50" id="notificationSubtitle">Loading...</small>
                    </div>
                    <span class="badge bg-white text-primary fw-semibold px-2 py-1" id="notificationBadge" style="display: none;">0 New</span>
                </div>
                
                <!-- Notification Items -->
                <div class="notification-list" id="notificationList" style="max-height: 360px; overflow-y: auto;">
                    <div class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="notification-footer px-4 py-3 border-top d-flex justify-content-between align-items-center" id="notificationFooter" style="display: none;">
                    <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline" id="markAllReadForm">
                        @csrf
                        <x-button type="button" variant="outline-primary" size="sm" icon="bi bi-check2-all" onclick="markAllNotificationsRead()">Mark all read</x-button>
                    </form>
                    <x-button href="{{ route('admin.notifications.index') }}" variant="primary" size="sm">View All <i class="bi bi-arrow-right ms-1"></i></x-button>
                </div>
            </div>
        </div>
        <div class="header-profile dropdown">
            <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="header-profile-avatar">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=d4a94c&color=1a2b4a&bold=true"
                        alt="{{ Auth::user()->name }}">
                </div>
                <div class="header-profile-info d-none d-md-block">
                    <span class="header-profile-name">{{ Auth::user()->name }}</span>
                    <span class="header-profile-role">{{ Auth::user()->role->name ?? 'User' }}</span>
                </div>
                <i class="bi bi-chevron-down text-muted small"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-lg">
                <li class="px-3 py-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar"
                            style="background: linear-gradient(135deg, var(--bs-gold) 0%, var(--bs-gold-dark) 100%); color: var(--bs-primary-dark);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                        <div>
                            <div class="fw-semibold">{{ Auth::user()->name }}</div>
                            <small class="text-muted">{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('admin.profile') }}">
                        <i class="fas fa-user me-2 text-muted"></i>
                        My Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('admin.profile.edit') }}">
                        <i class="fas fa-edit me-2 text-muted"></i>
                        Edit Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('admin.profile.password') }}">
                        <i class="fas fa-key me-2 text-muted"></i>
                        Change Password
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

<script>
// Notification System - Real-time polling
const NotificationSystem = {
    pollInterval: 5000, // Poll every 5 seconds for near real-time updates
    pollTimer: null,
    lastUnreadCount: 0,
    isPolling: false,
    
    init() {
        this.fetchNotifications();
        this.startPolling();
        
        // Refresh when dropdown is opened
        document.getElementById('notificationDropdown')?.addEventListener('show.bs.dropdown', () => {
            this.fetchNotifications();
        });
        
        // Use visibility API to pause polling when tab is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.fetchNotifications();
                this.startPolling();
            }
        });
        
        // Also poll on window focus
        window.addEventListener('focus', () => {
            this.fetchNotifications();
        });
    },
    
    startPolling() {
        if (this.pollTimer) return; // Already polling
        this.pollTimer = setInterval(() => {
            this.fetchNotifications();
        }, this.pollInterval);
    },
    
    stopPolling() {
        if (this.pollTimer) {
            clearInterval(this.pollTimer);
            this.pollTimer = null;
        }
    },
    
    async fetchNotifications() {
        if (this.isPolling) return; // Prevent overlapping requests
        this.isPolling = true;
        
        try {
            const response = await fetch('{{ route("admin.notifications.get") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Failed to fetch notifications');
            
            const data = await response.json();
            this.renderNotifications(data.notifications, data.unread_count);
            
            // Show alert for new notifications
            if (data.unread_count > this.lastUnreadCount && this.lastUnreadCount !== 0) {
                this.showNewNotificationAlert(data.unread_count - this.lastUnreadCount);
            }
            this.lastUnreadCount = data.unread_count;
            
        } catch (error) {
            console.error('Error fetching notifications:', error);
        } finally {
            this.isPolling = false;
        }
    },
    
    renderNotifications(notifications, unreadCount) {
        const dot = document.getElementById('notificationDot');
        const badge = document.getElementById('notificationBadge');
        const subtitle = document.getElementById('notificationSubtitle');
        const list = document.getElementById('notificationList');
        const footer = document.getElementById('notificationFooter');
        const markAllForm = document.getElementById('markAllReadForm');
        
        // Update dot and badge
        if (unreadCount > 0) {
            dot.style.display = 'block';
            badge.style.display = 'block';
            badge.textContent = unreadCount + ' New';
            subtitle.textContent = `You have ${unreadCount} unread notification${unreadCount > 1 ? 's' : ''}`;
            markAllForm.style.display = 'inline';
        } else {
            dot.style.display = 'none';
            badge.style.display = 'none';
            subtitle.textContent = 'All caught up!';
            markAllForm.style.display = 'none';
        }
        
        // Render notification list
        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-bell-slash fs-1 text-muted"></i>
                    </div>
                    <h6 class="text-muted mb-1">No notifications</h6>
                    <small class="text-muted">You're all caught up!</small>
                </div>
            `;
            footer.style.display = 'none';
        } else {
            list.innerHTML = notifications.map(n => this.renderNotificationItem(n)).join('');
            footer.style.display = 'flex';
        }
    },
    
    renderNotificationItem(notification) {
        const titleMap = {
            'payment_created': 'Payment Required',
            'payment_completed': 'Payment Completed',
            'payment_received': 'Payment Received',
            'requirement_added': 'New Requirement',
            'requirement_submitted': 'Requirement Submitted',
            'requirement_approved': 'Requirement Approved',
            'requirement_rejected': 'Requirement Rejected',
            'license_created': 'New License Application',
            'license_approved': 'License Approved',
            'license_rejected': 'License Rejected',
            'license_expiring': 'License Expiring Soon',
            'license_expired': 'License Expired',
            'renewal_open': 'Renewal Window Open',
        };
        
        const title = titleMap[notification.type] || 'Notification';
        const message = notification.message ? (notification.message.length > 50 ? notification.message.substring(0, 50) + '...' : notification.message) : '';
        
        return `
            <a href="${notification.url}" 
               class="notification-item ${!notification.read ? 'unread' : ''}"
               onclick="NotificationSystem.markAsRead('${notification.id}')">
                <div class="notification-icon-wrapper">
                    <div class="notification-icon ${notification.icon_class}">
                        <i class="bi ${notification.icon}"></i>
                    </div>
                    ${!notification.read ? '<span class="notification-status"></span>' : ''}
                </div>
                <div class="notification-content">
                    <div class="notification-title">${title}</div>
                    <div class="notification-text">${message}</div>
                    <div class="notification-time">
                        <i class="bi bi-clock"></i> ${notification.time}
                    </div>
                </div>
                <div class="notification-actions">
                    ${!notification.read 
                        ? `<button class="btn-notification-action" title="Mark as read" onclick="event.preventDefault(); event.stopPropagation(); NotificationSystem.markAsRead('${notification.id}')">
                            <i class="bi bi-check2"></i>
                           </button>`
                        : `<button class="btn-notification-action" title="Delete" onclick="event.preventDefault(); event.stopPropagation(); NotificationSystem.deleteNotification('${notification.id}')">
                            <i class="bi bi-x"></i>
                           </button>`
                    }
                </div>
            </a>
        `;
    },
    
    async markAsRead(id) {
        try {
            const response = await fetch(`{{ url('admin/notifications') }}/${id}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                this.fetchNotifications();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    },
    
    async deleteNotification(id) {
        try {
            const response = await fetch(`{{ url('admin/notifications') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                this.fetchNotifications();
            }
        } catch (error) {
            console.error('Error deleting notification:', error);
        }
    },
    
    showNewNotificationAlert(count) {
        // Play notification sound
        this.playNotificationSound();
        
        // Show browser notification
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('New Notification', {
                body: `You have ${count} new notification${count > 1 ? 's' : ''}`,
                icon: '/favicon.ico',
                tag: 'cls-notification', // Prevents duplicate notifications
                requireInteraction: false
            });
        }
        
        // Add a subtle animation to the bell icon
        const bell = document.querySelector('#notificationDropdown .bi-bell');
        if (bell) {
            bell.classList.add('notification-shake');
            setTimeout(() => bell.classList.remove('notification-shake'), 1000);
        }
        
        // Flash the notification dot
        const dot = document.getElementById('notificationDot');
        if (dot) {
            dot.classList.add('notification-pulse');
        }
    },
    
    playNotificationSound() {
        // Create a simple notification beep using Web Audio API
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 800;
            oscillator.type = 'sine';
            
            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.3);
        } catch (e) {
            // Audio not supported or blocked
            console.log('Notification sound not available');
        }
    }
};

// Global functions for backward compatibility
function markNotificationRead(id) {
    NotificationSystem.markAsRead(id);
}

function deleteNotification(id) {
    NotificationSystem.deleteNotification(id);
}

async function markAllNotificationsRead() {
    try {
        const response = await fetch('{{ route("admin.notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            NotificationSystem.fetchNotifications();
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

// Request browser notification permission
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    NotificationSystem.init();
});
</script>

<style>
@keyframes notification-shake {
    0%, 100% { transform: rotate(0deg); }
    10%, 30%, 50%, 70%, 90% { transform: rotate(-10deg); }
    20%, 40%, 60%, 80% { transform: rotate(10deg); }
}

@keyframes notification-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.5); opacity: 0.7; }
}

.notification-shake {
    animation: notification-shake 0.5s ease-in-out;
}

.notification-pulse {
    animation: notification-pulse 0.5s ease-in-out 3;
}
</style>