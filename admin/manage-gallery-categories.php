<?php
require_once __DIR__ . '/header.php';
require_admin();

$db = new Database();

$is_edit = false;
$category_id = $_GET['edit'] ?? null;
$category_to_edit = null;

if ($category_id) {
    $is_edit = true;
    $category_to_edit = $db->getGalleryCategoryById($category_id);
}

// Handle form submission for add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $data = [
        'name' => $name,
        'slug' => $slug,
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];

    if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
        // Update
        $db->updateGalleryCategory($_POST['category_id'], $data);
        $message = 'updated';
    } else {
        // Create
        $db->createGalleryCategory($data);
        $message = 'created';
    }
    header("Location: manage-gallery-categories.php?success=$message");
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    // Note: You might want to handle what happens to images in this category.
    // For now, we just delete the category.
    $db->deleteGalleryCategory($id_to_delete);
    header('Location: manage-gallery-categories.php?success=deleted');
    exit;
}

$categories = $db->getGalleryCategories();
?>

<div class="grid-container" style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div class="card">
        <div class="card-header">
            <h3>All Gallery Categories</h3>
        </div>
        <div class="card-body">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                            <td><span class="status <?php echo $cat['is_active'] ? 'status-active' : 'status-inactive'; ?>"><?php echo $cat['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
                            <td>
                                <a href="?edit=<?php echo $cat['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                <a href="?delete=<?php echo $cat['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><?php echo $is_edit ? 'Edit Category' : 'Add New Category'; ?></h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="category_id" value="<?php echo $category_to_edit['id'] ?? ''; ?>">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category_to_edit['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label class="switch"><input type="checkbox" name="is_active" value="1" <?php echo (isset($category_to_edit['is_active']) && $category_to_edit['is_active'] == 1) || !$is_edit ? 'checked' : ''; ?>> <span class="slider"></span></label>
                    <span style="margin-left: 0.5rem;">Active</span>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Update Category' : 'Add Category'; ?></button>
                <?php if ($is_edit): ?><a href="manage-gallery-categories.php" class="btn btn-outline">Cancel</a><?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>