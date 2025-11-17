<?php

echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Database Setup</title>";
echo "<style>body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 40px auto; padding: 20px; background-color: #f4f4f9; border: 1px solid #ddd; border-radius: 8px; } h1 { color: #0056b3; } .success { color: #28a745; font-weight: bold; } .error { color: #dc3545; font-weight: bold; } code { background-color: #e9ecef; padding: 2px 4px; border-radius: 4px; } a { color: #0056b3; } pre { background-color: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 5px; white-space: pre-wrap; word-wrap: break-word; }</style>";
echo "</head><body>";

echo "<h1>Osei Serwaa Kitchen Database Setup</h1>";

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
          `avatar` varchar(255) DEFAULT NULL,
          `is_active` tinyint(1) NOT NULL DEFAULT 1,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`),
          UNIQUE KEY `username` (`username`),
          UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    ");

    // Now, insert/update the admin user with a dynamically generated hash
    $admin_password_hash = password_hash('password', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO `admin_users` (`username`, `password`, `name`, `email`, `is_active`) VALUES ('admin', ?, 'Administrator', 'admin@example.com', 1) ON DUPLICATE KEY UPDATE password = VALUES(password);");
    $stmt->execute([$admin_password_hash]);

    $pdo->exec("

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

        -- Table structure for table `gallery_categories`
        CREATE TABLE IF NOT EXISTS `gallery_categories` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `slug` varchar(100) NOT NULL,
          `is_active` tinyint(1) NOT NULL DEFAULT 1,
          PRIMARY KEY (`id`),
          UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

        -- Dumping sample data for table `gallery_categories`
        INSERT IGNORE INTO `gallery_categories` (`name`, `slug`, `is_active`) VALUES ('Food', 'food', 1), ('Restaurant', 'restaurant', 1), ('Events', 'events', 1), ('Our Team', 'team', 1);

        -- Dumping sample data for table `gallery_images`
        INSERT IGNORE INTO `gallery_images` (`title`, `description`, `image_path`, `category`, `tags`, `is_active`) VALUES
        ('Delicious Jollof Rice', 'Our signature Jollof rice, a feast for the eyes and the palate.', 'https://placehold.co/400x300/e67e22/white?text=Jollof', 'food', 'classic,spicy', 1),
        ('Cozy Restaurant Interior', 'The warm and inviting atmosphere of our main dining area.', 'https://placehold.co/400x300/2c3e50/white?text=Interior', 'restaurant', 'ambience,cozy', 1),
        ('Banku with Tilapia', 'Freshly grilled tilapia served with hot banku.', 'https://placehold.co/400x300/e67e22/white?text=Banku', 'food', 'fresh,fish', 1), 
        ('A Special Birthday Event', 'Celebrating a special moment at Osei Serwa Kitchen.', 'https://placehold.co/400x300/f39c12/white?text=Event', 'events', 'celebration,party', 1),
        ('Our Culinary Team', 'The talented chefs behind our delicious meals.', 'https://placehold.co/400x300/34495e/white?text=Team', 'team', 'chefs,kitchen', 1),
        ('Waakye Delight', 'A classic Waakye platter with all the accompaniments.', 'https://placehold.co/400x300/e67e22/white?text=Waakye', 'food', 'traditional,hearty', 1);

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

        -- Clear existing menu items and insert new ones
        TRUNCATE TABLE `menu_items`;
        INSERT INTO `menu_items` (`name`, `description`, `price`, `category`, `image`, `tags`, `is_active`) VALUES
        ('Jollof Rice', 'A classic West African one-pot rice dish, slow-cooked in a flavorful tomato-based sauce.', '40.00', 'main', 'jollof-rice.jpg', 'classic,spicy', 1),
        ('Waakye', 'A popular Ghanaian dish of cooked rice and beans, served with traditional accompaniments.', '50.00', 'main', 'waakye.jpg', 'hearty,traditional', 1),
        ('Banku with Tilapia', 'Freshly grilled tilapia served with hot banku and spicy pepper sauce.', '60.00', 'main', 'banku-tilapia.jpg', 'fish,traditional', 1),
        ('Fried Rice with Chicken', 'Flavorful fried rice served with tender grilled chicken and vegetables.', '45.00', 'main', 'fried-rice-chicken.jpg', 'rice,chicken', 1);

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

        -- Add the avatar column to admin_users if it doesn't exist
        ALTER TABLE `admin_users` ADD COLUMN IF NOT EXISTS `avatar` VARCHAR(255) DEFAULT NULL AFTER `email`;

        -- Table structure for table `site_visits`
        CREATE TABLE IF NOT EXISTS `site_visits` (
          `visit_date` date NOT NULL,
          `visit_count` int(11) NOT NULL DEFAULT 0,
          PRIMARY KEY (`visit_date`)
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