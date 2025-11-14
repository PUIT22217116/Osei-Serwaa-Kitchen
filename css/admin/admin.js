document.addEventListener('DOMContentLoaded', () => {
    // --- Admin Sidebar Toggle ---
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    if (sidebarToggle && adminSidebar && sidebarOverlay) {
        const toggleSidebar = () => {
            adminSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        };

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }

    // You can add other admin-specific JavaScript here in the future,
    // such as table sorting, modal popups, etc.
});