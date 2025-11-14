<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
include 'includes/header.php';

$db = new Database();
$menu_items = $db->getActiveMenuItemsData();
?>

<main>
    <!-- Hero Section -->
    <section class="menu-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Our Menu</h1>
                <p>A culinary journey through the heart of Ghana</p>
            </div>
        </div>
    </section>

    <!-- Menu Filter -->
    <section class="menu-filter">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="main">Main Dishes</button>
            </div>
        </div>
    </section>

    <!-- Menu Grid -->
    <section class="menu-items">
        <div class="container">
            <div class="menu-grid" id="menuGrid">
                <!-- Menu items will be loaded dynamically by JS -->
            </div>
        </div>
    </section>
</main>

<!-- Pass PHP data to JavaScript -->
<script>
    const menuData = <?php echo json_encode($menu_items); ?>;
</script>

<!-- Include the menu-specific JavaScript -->
<script src="js/menu-filter.js"></script>

<?php include 'includes/footer.php'; ?>