<?php
// Step 1: Initialize the environment and perform authentication.
require_once __DIR__ . '/init.php';

$db = new Database();
$username = $_SESSION['admin_username'] ?? null;

// This check is technically redundant if init.php is used, but good for clarity.
if (!$username) {
    header('Location: admin-login.php?error=session_expired');
    exit;
}

$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'change_password') {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($current) || empty($new) || empty($confirm)) {
            $error = 'Please fill all password fields.';
        } elseif ($new !== $confirm) {
            $error = 'New password and confirmation do not match.';
        } elseif (strlen($new) < 8 || !preg_match('/[A-Z]/', $new) || !preg_match('/[a-z]/', $new) || !preg_match('/[0-9]/', $new)) {
            $error = 'New password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one number.';
        } elseif ($current === $new) {
            $error = 'New password cannot be the same as the current password.';
        } else {
            // Validate current password
            $admin = $db->getAdminByUsername($username);
            if ($admin && password_verify($current, $admin['password'] ?? '')) {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                if ($db->updateAdminPassword($username, $hash)) {
                    $message = 'Password updated successfully.';
                } else {
                    $error = 'Could not update password. Try again later.';
                }
            } else {
                $error = 'Current password is incorrect.';
            }
        }
    } elseif ($action === 'change_username') {
        $new_username = trim($_POST['new_username'] ?? '');
        $password_for_username = $_POST['password_for_username'] ?? '';

        if (empty($new_username) || empty($password_for_username)) {
            $error = 'New username and current password are required.';
        } elseif ($new_username === $username) {
            $error = 'New username cannot be the same as the current one.';
        } else {
            // Validate current password
            $admin = $db->getAdminByUsername($username);
            if ($admin && password_verify($password_for_username, $admin['password'] ?? '')) {
                // Attempt to update username
                if ($db->updateAdminUsername($username, $new_username)) {
                    $_SESSION['admin_username'] = $new_username; // IMPORTANT: Update session
                    $message = 'Username updated successfully. You may need to log in again for all changes to take effect.';
                    $username = $new_username; // Update for the current page
                } else {
                    $error = 'That username is already taken or an error occurred.';
                }
            } else {
                $error = 'Current password is incorrect.';
            }
        }
    }
}
?>
<?php
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Settings</h3>
    </div>
    <div class="card-body">
        <?php if ($message): ?><div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

        <form method="POST" class="mb-5">
            <input type="hidden" name="action" value="change_password">
            <h4>Change Password</h4>
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>

            <div class="modal-footer" style="border:none;padding:0">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>

        <hr class="my-4">

        <form method="POST">
            <input type="hidden" name="action" value="change_username">
            <h4>Change Username</h4>
            <div class="form-group">
                <label for="new_username">New Username</label>
                <input type="text" id="new_username" name="new_username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label for="password_for_username">Current Password (to confirm)</label>
                <input type="password" id="password_for_username" name="password_for_username" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Username</button>
        </form>
    </div>
</div>

            </div> <!-- This closes the .admin-content div from header.php -->
        </main> <!-- This closes the .admin-main div from header.php -->
    </div> <!-- This closes the .admin-container div from header.php -->

<?php require_once __DIR__ . '/footer.php'; ?>
