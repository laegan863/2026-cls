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
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        @include('partials.header')

        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5.3 JS -->
    @include('partials.script')
    
    <!-- Notification Functions -->
    <script>
        function markNotificationRead(id) {
            fetch(`/admin/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      // Update UI - remove unread styling
                      const item = document.querySelector(`[onclick*="${id}"]`);
                      if (item) {
                          item.classList.remove('unread');
                          const status = item.querySelector('.notification-status');
                          if (status) status.remove();
                      }
                      // Update badge count
                      updateNotificationCount();
                  }
              });
        }

        function deleteNotification(id) {
            if (!confirm('Delete this notification?')) return;
            
            fetch(`/admin/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      // Remove the notification item from DOM
                      const item = document.querySelector(`[onclick*="${id}"]`);
                      if (item) item.remove();
                      updateNotificationCount();
                  }
              });
        }

        function updateNotificationCount() {
            fetch('/admin/notifications/get', {
                headers: {
                    'Accept': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  const dot = document.querySelector('.notification-dot');
                  const badge = document.querySelector('.notification-header .badge');
                  const subtitle = document.querySelector('.notification-header small');
                  
                  if (data.unread_count > 0) {
                      if (!dot) {
                          const btn = document.querySelector('.header-action-btn');
                          const newDot = document.createElement('span');
                          newDot.className = 'notification-dot';
                          btn.appendChild(newDot);
                      }
                      if (badge) badge.textContent = data.unread_count + ' New';
                      if (subtitle) subtitle.textContent = `You have ${data.unread_count} unread notification${data.unread_count > 1 ? 's' : ''}`;
                  } else {
                      if (dot) dot.remove();
                      if (badge) badge.remove();
                      if (subtitle) subtitle.textContent = 'All caught up!';
                  }
              });
        }
    </script>
    
    @stack('scripts')
</body>

</html>
