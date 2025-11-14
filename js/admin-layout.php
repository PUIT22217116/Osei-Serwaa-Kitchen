<?php
// This is the master layout file for the admin panel.
// All other admin pages should include this file.

// It includes the header, which contains the sidebar and top navigation.
require_once __DIR__ . '/header.php';

// The main content of each specific page will be displayed here.
?>

<?php
// It includes the footer, which loads the necessary JavaScript for sidebar functionality.
// The sidebar overlay is also included here.
?>
<div class="sidebar-overlay"></div>
<?php require_once __DIR__ . '/admin-footer.php'; ?>