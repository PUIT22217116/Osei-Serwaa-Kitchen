<?php
require_once 'includes/config.php';

// --- Database Connection Check ---
// Try to connect to the database. If it fails, guide the user to the setup script.
try {
    // Use the database credentials from config.php
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo_check = new PDO($dsn, DB_USER, DB_PASS);
} catch (PDOException $e) {
    // Check if the error is "Unknown database"
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        // The database does not exist. Show a helpful message.
        include 'includes/header.php';
        echo '<main><div class="container" style="padding: 40px 20px; text-align: center;">';
        echo '<h1>Welcome to Osei Serwa Kitchen Setup</h1>';
        echo '<p style="font-size: 1.2rem; color: #555;">It looks like the database is not set up yet.</p>';
        echo '<p>Please run the setup script to create the database and tables.</p>';
        echo '<a href="setup.php" class="btn btn-primary" style="margin-top: 20px; font-size: 1.1rem;">Run Database Setup</a>';
        echo '</div></main>';
        include 'includes/footer.php';
        exit; // Stop further execution
    }
}

include 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Osei Serwa Kitchen</h1>
            <p class="hero-subtitle">Experience Authentic Ghanaian Cuisine</p>
            <div class="hero-buttons">
                <a href="menu.php" class="btn btn-primary">View Menu</a>
                <a href="reservation.php" class="btn btn-secondary">Make Reservation</a>
            </div>
        </div>
        <div class="hero-image">
            <!-- Background image will be set in CSS -->
        </div>
    </section>

    <!-- Featured Dishes -->
    <section class="featured-dishes">
        <div class="container">
            <h2>Our Specialties</h2>
            <div class="dishes-grid">
                <!-- Dishes will be loaded dynamically -->
            </div>
        </div>
    </section>

    <!-- About Preview -->
    <section class="about-preview">
        <div class="container">
            <div class="about-content">
                <h2>Our Story</h2>
                <p>Discover the rich flavors and traditions of Ghanaian cuisine...</p>
                <a href="about.php" class="btn btn-outline">Learn More</a>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>