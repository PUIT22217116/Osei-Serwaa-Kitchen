<?php
require_once __DIR__ . '/header.php';
require_admin();

$db = new Database();

// Handle delete request
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    // You might want to delete the actual image file from the server as well
    $db->deleteGalleryImage($id_to_delete);
    header('Location: manage-gallery.php?success=deleted');
    exit;
}

$gallery_items = $db->getGalleryItems();
?>

<div class="card">
    <div class="card-header">
        <h3>Manage Gallery</h3>
        <div class="card-header-actions">
            <a href="edit-gallery-item.php" class="btn btn-primary">Add New Image</a>
            <a href="manage-gallery-categories.php" class="btn btn-secondary">Manage Categories</a>
        </div>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Gallery item has been <?php echo htmlspecialchars($_GET['success']); ?> successfully.</div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($gallery_items)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No gallery items found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($gallery_items as $item): ?>
                            <tr>
                                <td><img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" width="80"></td>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($item['category'])); ?></td>
                                <td><span class="status <?php echo $item['is_active'] ? 'status-active' : 'status-inactive'; ?>"><?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?></span></td>
                                <td>
                                    <a href="edit-gallery-item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a>
                                    <a href="?delete=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this image?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>