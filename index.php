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
    include 'includes/header.php';
    echo '<main><div class="container" style="padding: 40px 20px; text-align: center;">';

    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        // The database does not exist. Show a helpful message.
        echo '<h1>Welcome to Osei Serwaa Kitchen Setup</h1>';
        echo '<p style="font-size: 1.2rem; color: #555;">It looks like the database is not set up yet.</p>';
        echo '<p>Please run the setup script to create the database and tables.</p>';
        echo '<a href="setup.php" class="btn btn-primary" style="margin-top: 20px; font-size: 1.1rem;">Run Database Setup</a>';
    } else {
        // Handle other database connection errors (e.g., wrong password)
        echo '<h1>Database Connection Error</h1>';
        echo '<p style="font-size: 1.2rem; color: #dc3545;">Could not connect to the database.</p>'; 
        echo '<p>Please check your database configuration in <code>includes/config.php</code> and ensure your database server (e.g., XAMPP) is running.</p>';
        echo '<p style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-top: 20px;"><strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    }

    echo '</div></main>';
    include 'includes/footer.php';
    exit; // Stop further execution for any database error
}

// --- Fetch Featured Dishes ---
// We need the Database class to fetch items.
require_once 'includes/database.php';
$db = new Database();
// Get the first 3 active menu items to feature as specialties.
$featured_dishes = $db->getMenuItems(null, 3);

include 'includes/header.php';
?>

<style>
/* --- Hero Slideshow Styles --- */
.hero {
    position: relative;
    height: 90vh; /* Adjust height as needed */
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-align: center;
    overflow: hidden; /* Ensures slides don't overflow */
}

.hero-slideshow {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
    transform: scale(1.1); /* Start slightly zoomed in */
    animation: ken-burns 20s infinite;
}

.hero-slide.active {
    opacity: 1;
}

/* Add variations for the animation on different slides */
.hero-slide:nth-child(2) {
    animation-name: ken-burns-alt;
}

.hero-slide:nth-child(3) {
    /* You can add a third variation if needed */
    animation-duration: 25s; /* Example: make it slower */
}

.hero-content {
    position: relative;
    z-index: 3; /* Ensure content is above the overlay */
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background for readability */
    padding: 40px 60px;
    border-radius: 10px;
}

/* Keyframes for the Ken Burns effect */
@keyframes ken-burns {
    0% {
        transform: scale(1.1) translate(-5%, 0%);
    }
    100% {
        /* Zoom out and pan to the opposite corner */
        transform: scale(1) translate(5%, 0%);
    }
}

/* A second, alternative animation for variety */
@keyframes ken-burns-alt {
    0% {
        transform: scale(1) translate(0%, 5%);
    }
    100% {
        transform: scale(1.1) translate(0%, -5%);
    }
}

/* Styles for the menu filter on the homepage */
.menu-filter {
    padding: 40px 0;
    background-color: #f9f9f9;
}

.filter-buttons {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}


</style>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Osei Serwaa Kitchen</h1>
            <p class="hero-subtitle">Experience Authentic Ghanaian Cuisine</p>
            <div class="hero-buttons">
                <a href="menu.php" class="btn btn-primary">View Menu</a>
                <a href="reservation.php" class="btn btn-secondary">Make Reservation</a>
            </div>
        </div>
        <div class="hero-slideshow">
            <?php
                // Load hero slides from data/home.json if available
                $homeDataFile = __DIR__ . '/data/home.json';
                $slides = [];
                if (file_exists($homeDataFile)) {
                    $json = file_get_contents($homeDataFile);
                    $parsed = json_decode($json, true);
                    if (is_array($parsed) && !empty($parsed['hero'])) {
                        $slides = $parsed['hero'];
                    }
                }

                // Fallback to default local images if no JSON slides available
                if (empty($slides)) {
                    $slides = [
                        ['image' => 'images/hero/hero1.jpg', 'title' => '', 'subtitle' => ''],
                        ['image' => 'images/hero/hero2.jpg', 'title' => '', 'subtitle' => ''],
                        ['image' => 'images/hero/hero3.jpg', 'title' => '', 'subtitle' => ''],
                    ];
                }

                foreach ($slides as $s) {
                    $img = htmlspecialchars($s['image'] ?? '');
                    echo '<div class="hero-slide" style="background-image: url(\'' . $img . '\');"></div>';
                }
            ?>
        </div>
    </section>

    <!-- The "Our Specialties" section is commented out for now. -->

    <style>
        .about-preview {
            position: relative;
            padding: 100px 0;
            text-align: center;
            background: url('images/about/about-bg.jpg') no-repeat center center/cover;
            color: #fff; /* Ensure text is white */
        }

        .about-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Dark overlay for readability */
            z-index: 1;
        }

        .about-preview .about-content {
            position: relative;
            z-index: 2; /* Place content above the overlay */
        }
    </style>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length === 0) return;

    let currentSlide = 0;

    function showSlide(index) {
        // Remove active class from all slides
        slides.forEach(slide => {
            slide.classList.remove('active');
        });

        // Add active class to the new current slide
        slides[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Show the first slide initially
    showSlide(currentSlide);

    // Change slide every 5 seconds (5000 milliseconds)
    setInterval(nextSlide, 5000);
});
</script>

<?php include 'includes/footer.php'; ?>