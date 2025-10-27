<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/auth.php';
$current_page = basename($_SERVER['PHP_SELF']);
$page_title = ucfirst(str_replace(['.php', '-'], ['', ' '], $current_page));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?> Admin</title>
    <link rel="stylesheet" href="../css/admin/admin-main.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2><?php echo SITE_NAME; ?></h2>
                <p>Admin Panel</p>
            </div>
            <nav class="sidebar-menu">
                <a href="dashboard.php" class="menu-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                    <span class="menu-icon">🏠</span>
                    <span class="menu-text">Dashboard</span>
                </a>
                <a href="manage-menu.php" class="menu-item <?php echo ($current_page == 'manage-menu.php') ? 'active' : ''; ?>">
                    <span class="menu-icon">🍔</span>
                    <span class="menu-text">Menu Items</span>
                </a>
                <a href="manage-reservations.php" class="menu-item <?php echo ($current_page == 'manage-reservations.php') ? 'active' : ''; ?>">
                    <span class="menu-icon">📅</span>
                    <span class="menu-text">Reservations</span>
                </a>
                <a href="../index.php" class="menu-item">
                    <span class="menu-icon">🌐</span>
                    <span class="menu-text">View Site</span>
                </a>
                <a href="admin-logout.php" class="menu-item">
                    <span class="menu-icon">🚪</span>
                    <span class="menu-text">Logout</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <header class="admin-header">
                <div class="header-left">
                    <h1><?php echo $page_title; ?></h1>
                    <div class="breadcrumb">Home / <?php echo $page_title; ?></div>
                </div>
                <div class="header-right">
                    <div class="admin-user">
                        Welcome, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?>
                    </div>
                    <a href="admin-logout.php" class="btn btn-outline btn-sm">Logout</a>
                </div>
            </header>
            <div class="admin-content">