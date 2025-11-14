<?php
// Use absolute paths so this file works reliably when included from anywhere.
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/database.php';

$db = new Database();
// Increment today's visit. This method is resilient if the table isn't present.
$db->incrementSiteVisit();

// No output needed, this is a background process
?>