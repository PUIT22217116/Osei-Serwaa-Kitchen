<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
include 'header.php';

$dataFile = __DIR__ . '/../data/home.json';
$home = [];
if (file_exists($dataFile)) {
    $home = json_decode(file_get_contents($dataFile), true) ?: [];
}
$slides = $home['hero'] ?? [];
?>

<div class="admin-content">
    <div class="card">
        <div class="card-header">
            <h3>Manage Homepage Hero</h3>
            <div>
                <a href="dashboard.php" class="btn btn-outline">Back</a>
            </div>
        </div>
        <div class="card-body">
            <form action="save-home.php" method="post" enctype="multipart/form-data">
                <p>You can manage up to 5 hero slides. Leave an image field blank to keep the current image.</p>
                <?php for ($i = 0; $i < 5; $i++):
                    $slide = $slides[$i] ?? ['image'=>'','title'=>'','subtitle'=>''];
                ?>
                    <h4>Slide <?php echo $i+1; ?></h4>
                    <div class="form-group">
                        <label>Image (current)</label>
                        <?php if (!empty($slide['image'])): ?>
                            <div style="margin-bottom:.5rem"><img src="<?php echo htmlspecialchars($slide['image']); ?>" style="max-width:300px;border-radius:6px"></div>
                        <?php endif; ?>
                        <input type="file" name="slide_image_<?php echo $i; ?>" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="slide_title_<?php echo $i; ?>" class="form-control" value="<?php echo htmlspecialchars($slide['title'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Subtitle</label>
                        <input type="text" name="slide_subtitle_<?php echo $i; ?>" class="form-control" value="<?php echo htmlspecialchars($slide['subtitle'] ?? ''); ?>">
                    </div>
                    <hr>
                <?php endfor; ?>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Save Hero Slides</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php';
