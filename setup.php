<?php

echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Database Setup</title>";
echo "<style>body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 40px auto; padding: 20px; background-color: #f4f4f9; border: 1px solid #ddd; border-radius: 8px; } h1 { color: #0056b3; } .success { color: #28a745; font-weight: bold; } .error { color: #dc3545; font-weight: bold; } code { background-color: #e9ecef; padding: 2px 4px; border-radius: 4px; } a { color: #0056b3; } pre { background-color: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 5px; white-space: pre-wrap; word-wrap: break-word; }</style>";
echo "</head><body>";

echo "<h1>Osei Serwa Kitchen Database Setup</h1>";

// Include your configuration but suppress its output
ob_start();
require_once 'includes/config.php';
ob_end_clean();

// --- Step 1: Connect to MySQL Server (without specifying a database) ---
try {
    // DSN for connecting to the server, not a specific DB
    $dsn = 'mysql:host=' . DB_HOST . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "<p class='success'>✅ Successfully connected to MySQL server.</p>";

} catch (PDOException $e) {
    echo "<p class='error'>❌ Connection to MySQL server failed.</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database credentials (DB_USER, DB_PASS) in <code>includes/config.php</code> and ensure your MySQL server (XAMPP) is running.</p>";
    echo "</body></html>";
    exit; // Stop if we can't connect to MySQL at all
}

// --- Step 2: Create the Database ---
$dbName = DB_NAME; // 'osei_serwa_kitchen'
try {
    // Use backticks to safely quote the database name
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
    echo "<p class='success'>✅ Database '<code>" . htmlspecialchars($dbName) . "</code>' created successfully (or already exists).</p>";

    // Select the database for subsequent operations
    $pdo->exec("USE `$dbName`");
    echo  "<p class='success'>✅ Successfully selected database: <code>" . htmlspecialchars($dbName) . "</code>.</p>";

} catch (PDOException $e) {
    echo "<p class='error'>❌ An error occurred while trying to create or select the database.</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</body></html>";
    exit; // Stop if we can't create the database
}

// --- Step 3: Create the Tables ---
try {
    $pdo->exec("
        -- Table structure for table `admin_users`
        CREATE TABLE IF NOT EXISTS `admin_users` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `username` varchar(50) NOT NULL,
          `password` varchar(255) NOT NULL,
          `name` varchar(100) NOT NULL,
          `email` varchar(100) NOT NULL,
          `is_active` tinyint(1) NOT NULL DEFAULT 1,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`),
          UNIQUE KEY `username` (`username`),
          UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

        -- Dumping data for table `admin_users`
        -- The password is 'password'
        INSERT INTO `admin_users` (`username`, `password`, `name`, `email`, `is_active`) VALUES
        ('admin', '$2y$10$9jArfS.t1LdC7b.Gk3fL5uT2dYfLwLgqQ8.N.zG.h.I.j.K.l.M.n', 'Administrator', 'admin@example.com', 1)
        ON DUPLICATE KEY UPDATE password = VALUES(password);

        -- Table structure for table `contact_messages`
        CREATE TABLE IF NOT EXISTS `contact_messages` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `email` varchar(100) NOT NULL,
          `phone` varchar(20) DEFAULT NULL,
          `subject` varchar(100) NOT NULL,
          `message` text NOT NULL,
          `status` varchar(20) NOT NULL DEFAULT 'unread',
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

        -- Table structure for table `gallery_images`
        CREATE TABLE IF NOT EXISTS `gallery_images` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(100) NOT NULL,
          `description` text DEFAULT NULL,
          `image_path` varchar(255) NOT NULL,
          `category` varchar(50) DEFAULT NULL,
          `tags` varchar(255) DEFAULT NULL,
          `is_active` tinyint(1) NOT NULL DEFAULT 1,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

        -- Table structure for table `menu_items`
        CREATE TABLE IF NOT EXISTS `menu_items` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `description` text DEFAULT NULL,
          `price` decimal(10,2) NOT NULL,
          `category` varchar(50) NOT NULL,
          `image` varchar(255) DEFAULT NULL,
          `tags` varchar(255) DEFAULT NULL,
          `is_active` tinyint(1) NOT NULL DEFAULT 1,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

        -- Table structure for table `reservations`
        CREATE TABLE IF NOT EXISTS `reservations` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `email` varchar(100) NOT NULL,
          `phone` varchar(20) NOT NULL,
          `guests` varchar(50) NOT NULL,
          `date` date NOT NULL,
          `time` time NOT NULL,
          `occasion` varchar(50) DEFAULT NULL,
          `notes` text DEFAULT NULL,
          `status` varchar(20) NOT NULL DEFAULT 'pending',
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ");

    echo "<p class='success'>✅ All tables created successfully.</p>";
    echo "<h2>Next Steps</h2>";
    echo "<p>The database and tables are now set up. You should be able to access the website without errors.</p>";
    echo "<p>You can now try accessing the website again: <a href='index.php'>Go to Homepage</a></p>";

} catch (PDOException $e) {
    echo "<p class='error'>❌ An error occurred while trying to create the tables.</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</body></html>";

?>