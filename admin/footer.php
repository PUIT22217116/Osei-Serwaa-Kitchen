    <div class="sidebar-overlay"></div> <!-- This needs to be outside admin-container to cover it -->

    <!-- Admin JavaScript -->
    <script src="../js/admin.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dropdown functionality for action buttons
        document.querySelectorAll('.dropdown-toggle').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation();
                const menu = this.nextElementSibling;
                // Close all other open dropdowns
                document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                    if (openMenu !== menu) {
                        openMenu.classList.remove('show');
                    }
                });
                menu.classList.toggle('show');
            });
        });
        // Close dropdowns if clicking outside
        window.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu.show').forEach(openMenu => {
                openMenu.classList.remove('show');
            });
        });
    });
    </script>
</body>
</html>