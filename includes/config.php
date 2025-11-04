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

// WhatsApp Cloud API configuration
// Set WA_ENABLE to true to enable automatic server-side WhatsApp notifications.
// Provide WA_ACCESS_TOKEN and WA_PHONE_NUMBER_ID from your Facebook/Meta app.
// WA_ADMIN_NUMBER should be the destination number in international format without '+' or leading zeros (e.g., 233246103680)
if (!defined('WA_ENABLE')) define('WA_ENABLE', true); // Set to true to enable this feature

// IMPORTANT: You must get these credentials from your Meta for Developers App dashboard.
if (!defined('WA_ACCESS_TOKEN')) define('WA_ACCESS_TOKEN', 'YOUR_ACCESS_TOKEN_HERE'); // Replace with your actual token
if (!defined('WA_PHONE_NUMBER_ID')) define('WA_PHONE_NUMBER_ID', 'YOUR_PHONE_NUMBER_ID_HERE'); // Replace with your actual Phone Number ID

if (!defined('WA_ADMIN_NUMBER')) define('WA_ADMIN_NUMBER', '233246103680');

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