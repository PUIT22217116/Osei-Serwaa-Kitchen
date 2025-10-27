<?php
require_once __DIR__ . '/header.php';
require_admin();

$db = new Database();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $db->deleteMenuItem($_GET['id']);
    // Redirect to avoid re-deleting on refresh
    header('Location: manage-menu.php?deleted=true');
    exit;
}

$menu_items = $db->getMenuItems();
?>

<div class="card">
    <div class="card-header">
        <h3>All Menu Items</h3>
        <a href="edit-menu-item.php" class="btn btn-primary">
            <span>➕</span> Add New Item
        </a>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success">Menu item deleted successfully.</div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <?php
            $message = '';
            if ($_GET['success'] === 'created') $message = 'Menu item created successfully.';
            if ($_GET['success'] === 'updated') $message = 'Menu item updated successfully.';
            ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($menu_items)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No menu items found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($menu_items as $item): ?>
                            <tr>
                                <td>#<?php echo $item['id']; ?></td>
                                <td>
                                    <a href="../<?php echo htmlspecialchars($item['image'] ?: 'images/menu/placeholder.jpg'); ?>" target="_blank" title="View full image">
                                        <img src="../<?php echo htmlspecialchars($item['image'] ?: 'images/menu/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="table-image">
                                    </a>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                </td>
                                <td><?php echo ucfirst(htmlspecialchars($item['category'])); ?></td>
                                <td>₵<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $item['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-menu-item.php?id=<?php echo $item['id']; ?>" class="btn btn-warning btn-sm">
                                            <span>✏️</span> Edit
                                        </a>
                                        <a href="manage-menu.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm btn-delete">
                                            <span>🗑️</span> Delete
                                        </a>
                                    </div>
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

<script>
// Add confirmation to delete buttons
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', (e) => {
        if (!confirm('Are you sure you want to delete this menu item? This action cannot be undone.')) {
            e.preventDefault();
        }
    });
});
</script>