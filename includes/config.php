<?php
// Database configuration (to be updated)
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'osei_serwa_kitchen');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

// Site configuration
if (!defined('SITE_NAME')) define('SITE_NAME', 'Osei Serwaa Kitchen');
if (!defined('SITE_URL')) define('SITE_URL', 'http://localhost/osei-serwa-kitchen');
if (!defined('CONTACT_EMAIL')) define('CONTACT_EMAIL', 'info@oseiserwakitchen.com');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// WhatsApp Cloud API configuration
// Load WhatsApp configuration from environment variables when available to avoid committing secrets.
// To enable, set the following in your environment or a local .env file (do NOT commit .env):
// WA_ENABLE=true
// WA_ACCESS_TOKEN=your_long_lived_access_token
// WA_PHONE_NUMBER_ID=your_phone_number_id
// WA_ADMIN_NUMBER=233246103680

// Helper to interpret boolean-like env values
function env_bool($v, $default = false) {
    if ($v === null) return $default;
    $v = strtolower(trim($v));
    return in_array($v, ['1','true','yes','on'], true);
}

// Define WA_ENABLE from environment if available, otherwise keep existing constant or default to false
$wa_enable_env = getenv('WA_ENABLE');
if ($wa_enable_env !== false) {
    if (!defined('WA_ENABLE')) define('WA_ENABLE', env_bool($wa_enable_env, false));
} else {
    if (!defined('WA_ENABLE')) define('WA_ENABLE', false);
}

// Load token and phone number id from environment if present
$wa_access_token_env = getenv('WA_ACCESS_TOKEN');
if ($wa_access_token_env !== false) {
    if (!defined('WA_ACCESS_TOKEN')) define('WA_ACCESS_TOKEN', $wa_access_token_env);
} else {
    if (!defined('WA_ACCESS_TOKEN')) define('WA_ACCESS_TOKEN', '');
}

$wa_phone_id_env = getenv('WA_PHONE_NUMBER_ID');
if ($wa_phone_id_env !== false) {
    if (!defined('WA_PHONE_NUMBER_ID')) define('WA_PHONE_NUMBER_ID', $wa_phone_id_env);
} else {
    if (!defined('WA_PHONE_NUMBER_ID')) define('WA_PHONE_NUMBER_ID', '');
}

$wa_admin_number_env = getenv('WA_ADMIN_NUMBER');
if ($wa_admin_number_env !== false) {
    if (!defined('WA_ADMIN_NUMBER')) define('WA_ADMIN_NUMBER', $wa_admin_number_env);
} else {
    if (!defined('WA_ADMIN_NUMBER')) define('WA_ADMIN_NUMBER', '233246103680');
}

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