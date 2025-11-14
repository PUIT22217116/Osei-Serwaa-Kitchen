<?php
// This is the master layout file for the admin panel.
// All other admin pages should include this file at the very top.

// 1. It includes the header, which contains the sidebar and top navigation.
require_once __DIR__ . '/header.php';

// 2. The main content of each specific page (like dashboard.php) will be displayed
//    immediately after this file is included.

// 3. The page that includes this layout will then need to include the admin-footer.php
//    at the very end to load the necessary JavaScript.
?>