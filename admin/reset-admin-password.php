<?php
/**
 * Quick script to reset admin password in the database.
 * Default Password: "password"
 * 
 * SECURITY: This script is a developer tool and should not be publicly accessible.
 * To use it, you must provide the correct token in the URL.
 * Example: /admin/reset-admin-password.php?token=set-new-password
 */

require_once __DIR__ . '/../includes/config.php';

// --- Security Check ---
$secret_token = 'set-new-password'; // You can change this token
if (!isset($_GET['token']) || $_GET['token'] !== $secret_token) {
    http_response_code(403); // Forbidden
    die('<h1>Forbidden</h1><p>You do not have permission to access this page.</p>');
}

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // Dynamically generate a valid bcrypt hash for "password"
    $password_hash = password_hash('password', PASSWORD_DEFAULT);

    // Use INSERT ... ON DUPLICATE KEY UPDATE to ensure the admin user exists.
    // This will create the user if they don't exist, or update the password if they do.
    $sql = "
        INSERT INTO admin_users (username, password, name, email, is_active)
        VALUES ('admin', ?, 'Administrator', 'admin@example.com', 1)
        ON DUPLICATE KEY UPDATE password = VALUES(password)
    ";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$password_hash]);

    // rowCount() returns 1 for an INSERT, 2 for an UPDATE, and 0 if no change was made.
    // Any of these mean the operation was successful in this context.
    if ($result) {
        echo "✅ Admin password has been reset successfully! You can now log in using:<br><strong>Username:</strong> admin<br><strong>Password:</strong> password<br><br><a href='admin-login.php?reset_success=true'>Go to Login</a>";
    } else {
        echo "❌ Could not reset the password. Please check database permissions or run setup.php again.";
    }

} catch (PDOException $e) {
    echo "❌ Database error: " . htmlspecialchars($e->getMessage());
}
?>
