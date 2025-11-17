<?php
// Admin-only DB status page. Requires admin login.
require_once __DIR__ . '/init.php';

header('Content-Type: text/plain; charset=utf-8');

// Use the db config returned by includes/config.php
$config = require __DIR__ . '/../includes/config.php';
$dbcfg = $config['db'] ?? null;
if (!$dbcfg) {
    echo "No DB configuration available.\n";
    exit;
}

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $dbcfg['host'], $dbcfg['name']);
try {
    $pdo = new PDO($dsn, $dbcfg['user'], $dbcfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    echo "PDO connection successful.\n";

    // Show current database name and user (helpful for diagnosing permissions)
    $stmt = $pdo->query("SELECT DATABASE() AS db, USER() AS user");
    $row = $stmt->fetch();
    if ($row) {
        echo "Database in use: " . ($row['db'] ?? 'N/A') . "\n";
        echo "Connected user: " . ($row['user'] ?? 'N/A') . "\n";
    }

    // Show a small sample query to ensure SELECT works
    try {
        $r = $pdo->query('SELECT 1 as ok')->fetch();
        echo "Simple query result: " . json_encode($r) . "\n";
    } catch (Exception $e) {
        echo "Simple query failed: " . $e->getMessage() . "\n";
    }

} catch (PDOException $e) {
    echo "PDO connection failed: " . $e->getMessage() . "\n";
    // echo additional hints
    echo "\nHints:\n";
    echo "- Make sure you created the MySQL database in the InfinityFree control panel.\n";
    echo "- Use the exact database name shown in the panel (it usually starts with your account prefix, e.g. if0_40441372_mydb).\n";
    echo "- Ensure `DB_NAME` and `DB_PASS` in `includes/config.php` match the values from the panel.\n";
    echo "- If you see 'Access denied for user' that usually means the username doesn't have permission to the named database; double-check the DB name.\n";
}

// Keep this page admin-only. It requires admin login via init.php.

