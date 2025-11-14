<?php
/**
 * This file initializes the admin environment.
 * It loads configuration, database, and authentication helpers,
 * and then checks if the user is logged in.
 * This should be the first file included in any secure admin page.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin(); // This will now work because auth.php is loaded.