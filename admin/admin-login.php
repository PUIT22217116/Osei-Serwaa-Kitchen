<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/Database.php';

// If user is already logged in, redirect to dashboard
if (is_admin()) {
    header('Location: dashboard.php');
    exit;
}

$error = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $db = new Database();
    $admin = $db->validateAdmin($username, $password);

    if ($admin) {
        // Set session to mark user as logged in
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_name'] = $admin['name']; // Use the name from the database
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Osei Serwa Kitchen</title>
    <link rel="stylesheet" href="../css/admin/admin.css">
    <link rel="stylesheet" href="../css/admin/admin-main.css"> <!-- For alerts -->
</head>
<body class="admin-body">
    <main>
        <div class="login-container">
            <h1>Admin Login</h1>
            <p>Welcome back, please login to your account.</p>
            <form method="post" action="admin-login.php" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit" class="btn-login">Login</button>

                <?php if ($error): ?>
                    <p class="error-message"><?php echo $error; ?></p>
                <?php endif; ?>

                <?php if (isset($_GET['logout'])): ?>
                    <div class="alert alert-success" style="margin-top: 1.5rem;">
                        You have been logged out successfully.
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </main>
</body>
</html>