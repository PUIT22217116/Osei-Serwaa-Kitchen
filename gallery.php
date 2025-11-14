<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
include 'includes/header.php';

$db = new Database();
$gallery_items = $db->getActiveGalleryItems();
$gallery_categories = $db->getActiveGalleryCategories();
?>

<main>
    <!-- Hero Section -->
    <section class="gallery-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Our Gallery</h1>
                <p>Experience the vibrant flavors and atmosphere of Osei Serwaa Kitchen</p>
            </div>
        </div>
    </section>

    <!-- Gallery Filter -->
    <section class="gallery-filter">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">All</button>
                <?php foreach ($gallery_categories as $category): ?>
                    <button class="filter-btn" data-filter="<?php echo htmlspecialchars($category['slug']); ?>"><?php echo htmlspecialchars($category['name']); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Grid -->
    <section class="gallery-main">
        <div class="container">
            <div class="gallery-grid" id="galleryGrid">
                <!-- Gallery items will be loaded dynamically -->
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <span class="lightbox-close">&times;</span>
            <div class="lightbox-image">
                <img src="" alt="" id="lightbox-img">
            </div>
            <div class="lightbox-caption">
                <h3 id="lightbox-title"></h3>
                <p id="lightbox-desc"></p>
            </div>
            <div class="lightbox-nav">
                <button class="lightbox-prev" id="lightbox-prev">❮</button>
                <button class="lightbox-next" id="lightbox-next">❯</button>
            </div>
        </div>
    </div>
</main>

<!-- Pass PHP data to JavaScript -->
<script>
    const galleryData = <?php echo json_encode($gallery_items); ?>;
</script>

<!-- Include the gallery-specific JavaScript -->
<script src="js/gallery-slider.js" defer></script>

<?php include 'includes/footer.php'; ?>