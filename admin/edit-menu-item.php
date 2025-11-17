<?php
// Step 1: Initialize the environment and handle all PHP logic BEFORE any HTML output.
require_once __DIR__ . '/init.php';

$db = new Database();
$is_edit = false;
$item_id = $_GET['id'] ?? null;
$item = null;

if ($item_id) {
    $is_edit = true;
    $item = $db->getMenuItemById($item_id);
    if (!$item) {
        // Item not found, redirect or show error
        header('Location: manage-menu.php?error=not_found');
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'price' => $_POST['price'] ?? 0.00,
        'category' => $_POST['category'] ?? 'main',
        'tags' => $_POST['tags'] ?? '',
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'image' => $item['image'] ?? null // Keep old image by default
    ];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/menu-items/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $filename = uniqid() . '-' . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $data['image'] = 'uploads/menu-items/' . $filename;
        }
    }

    if ($is_edit) {
        $db->updateMenuItem($item_id, $data);
        $redirect_message = 'updated';
    } else {
        $db->createMenuItem($data);
        $redirect_message = 'created';
    }

    header("Location: manage-menu.php?success=$redirect_message");
    exit;
}

// Step 2: Now that all PHP logic is done, include the HTML header.
require_once __DIR__ . '/header.php';


$page_title = $is_edit ? 'Edit Menu Item' : 'Add New Menu Item';
?>

<div class="card">
    <div class="card-header">
        <h3><?php echo $page_title; ?></h3>
        <a href="manage-menu.php" class="btn btn-outline btn-sm">Back to List</a>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Item Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (₵)</label>
                    <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($item['price'] ?? '0.00'); ?>" step="0.01" required>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" class="form-control" required>
                        <?php
                        $categories = ['main', 'soup', 'side', 'drink', 'dessert'];
                        foreach ($categories as $cat) {
                            $selected = (($item['category'] ?? '') === $cat) ? 'selected' : '';
                            echo "<option value=\"$cat\" $selected>" . ucfirst($cat) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tags">Tags (comma-separated)</label>
                    <input type="text" id="tags" name="tags" class="form-control" value="<?php echo htmlspecialchars($item['tags'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="image">Item Image</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <?php if ($is_edit && !empty($item['image'])): ?>
                        <div style="margin-top: 1rem;">
                            <p>Current Image:</p>
                            <img src="../<?php echo htmlspecialchars($item['image']); ?>" alt="Current Image" style="max-width: 150px; border-radius: 4px;">
                        </div>
                    <?php endif; ?>
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
            </div>

            <div class="modal-footer" style="padding: 1.5rem 0 0 0; border: none;">
                <a href="manage-menu.php" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?php echo $is_edit ? 'Update Item' : 'Create Item'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>