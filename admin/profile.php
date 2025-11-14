<?php
// Step 1: Initialize the environment and perform authentication.
require_once __DIR__ . '/init.php';

$db = new Database();
$username = $_SESSION['admin_username'] ?? null;

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $username) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Handle avatar upload
    $avatar_path = null;
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/admin-avatars/';
        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }
        $filename = uniqid() . '-' . basename($_FILES['avatar']['name']);
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
            $avatar_path = 'uploads/admin-avatars/' . $filename;
        }
    }

    $success = $db->updateAdminProfile($username, $name, $email, $avatar_path);
    if ($success) {
        $_SESSION['admin_name'] = $name;
        if ($avatar_path) {
            $_SESSION['admin_avatar'] = $avatar_path;
        }
        // Redirect to prevent form resubmission and to show the new avatar
        header('Location: profile.php?success=true');
        exit;
    } else {
        $message = 'Could not update profile. Please try again.';
    }
}

// Step 2: Now that any updates are processed, load the header.
// The header will now fetch the latest user data, including the new avatar.
require_once __DIR__ . '/header.php';

// Step 3: Fetch the latest admin data to display in the form.
$admin = $db->getAdminByUsername($username);

?>

<div class="card">
    <div class="card-header">
        <h3>Profile</h3>
    </div>
    <div class="card-body">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Profile updated successfully.</div>
        <?php elseif ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($admin['name'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="avatar">Avatar</label>
                <input type="file" id="avatar" name="avatar" accept="image/*" class="form-control">
                <?php if (!empty($admin['avatar'])): ?>
                    <div style="margin-top:0.75rem;"><img src="../<?php echo htmlspecialchars($admin['avatar']); ?>" alt="Avatar" style="width:80px;border-radius:50%;"></div>
                <?php endif; ?>
            </div>

            <div class="modal-footer" style="border:none;padding:0">
                <button type="submit" class="btn btn-primary">Save Profile</button>
                <a href="dashboard.php" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

            </div> <!-- This closes the .admin-content div from header.php -->
        </main> <!-- This closes the .admin-main div from header.php -->
    </div> <!-- This closes the .admin-container div from header.php -->

<?php require_once __DIR__ . '/footer.php'; ?>
