<?php
// Database configuration (to be updated)
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'osei_serwa_kitchen');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

// Site configuration
if (!defined('SITE_NAME')) define('SITE_NAME', 'Osei Serwa Kitchen');
if (!defined('SITE_URL')) define('SITE_URL', 'http://localhost/osei-serwa-kitchen');
if (!defined('CONTACT_EMAIL')) define('CONTACT_EMAIL', 'info@oseiserwakitchen.com');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Return configuration array for database connection
return [
    'db' => [
        'host' => DB_HOST,
        'name' => DB_NAME,
        'user' => DB_USER,
        'pass' => DB_PASS,
    ]
];
?>