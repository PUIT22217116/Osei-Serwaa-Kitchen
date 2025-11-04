<?php
require_once __DIR__ . '/header.php';
require_admin();

$db = new Database();

$is_edit = false;
$item_id = $_GET['id'] ?? null;
$item = null;

if ($item_id) {
    $is_edit = true;
    $item = $db->getGalleryImageById($item_id);
    if (!$item) {
        header('Location: manage-gallery.php?error=not_found');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'category' => $_POST['category'] ?? '',
        'tags' => $_POST['tags'] ?? '',
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'image_path' => $item['image_path'] ?? null
    ];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '-' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $data['image_path'] = 'uploads/gallery/' . $filename;
            // Optionally delete the old image file if it exists
        }
    }

    if ($is_edit) {
        $db->updateGalleryImage($item_id, $data);
        $message = 'updated';
    } else {
        $db->createGalleryImage($data);
        $message = 'created';
    }

    header("Location: manage-gallery.php?success=$message");
    exit;
}

$page_title = $is_edit ? 'Edit Gallery Image' : 'Add New Gallery Image';
$categories = $db->getGalleryCategories();
?>

<div class="card">
    <div class="card-header">
        <h3><?php echo $page_title; ?></h3>
        <a href="manage-gallery.php" class="btn btn-outline btn-sm">Back to List</a>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Image Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['slug']; ?>" <?php echo (($item['category'] ?? '') === $cat['slug']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Image File</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" <?php echo !$is_edit ? 'required' : ''; ?>>
                    <?php if ($is_edit && !empty($item['image_path'])): ?>
                        <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="Current Image" style="max-width: 150px; margin-top: 1rem; border-radius: 4px;">
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="is_active">Status</label>
                <div style="margin-top: 0.5rem;">
                    <label class="switch">
                        <input type="checkbox" id="is_active" name="is_active" value="1" <?php echo (isset($item['is_active']) && $item['is_active'] == 1) || !$is_edit ? 'checked' : ''; ?>>
                        <span class="slider"></span>
                    </label>
                    <span style="margin-left: 0.5rem;">Active</span>
                </div>
            </div>

            <div class="modal-footer" style="padding: 1.5rem 0 0 0; border: none;">
                <a href="manage-gallery.php" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Update Image' : 'Add Image'; ?></button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>