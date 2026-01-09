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