<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Database.php';
require_once __DIR__ . '/../includes/auth.php';
$current_page = basename($_SERVER['PHP_SELF']);
$page_title = ucfirst(str_replace(['.php', '-'], ['', ' '], $current_page));
// Attempt to fetch admin profile data (avatar, email) if username is in session
$adminAvatar = '../images/icons/avatar-placeholder.png';
$adminEmail = '';
$adminUsername = $_SESSION['admin_username'] ?? null;

// Prioritize avatar from session first, as it's the most current after an update.
if (!empty($_SESSION['admin_avatar'])) {
    $adminAvatar = '../' . ltrim($_SESSION['admin_avatar'], '/');
} elseif ($adminUsername) {
    // If not in session, fetch from DB as a fallback.
    try {
        $db_for_header = new Database(); // This is fine for a fallback.
        $adminData = $db_for_header->getAdminByUsername($adminUsername);
        if ($adminData && !empty($adminData['avatar'])) {
            $adminAvatar = '../' . ltrim($adminData['avatar'], '/');
            $_SESSION['admin_avatar'] = $adminData['avatar']; // Store it in session for next time.
            if (empty($adminEmail)) {
                $adminEmail = $adminData['email'] ?? '';
            }
        }
    } catch (Exception $e) {
        // ignore DB errors in header to avoid breaking pages
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?> Admin</title>
    <link rel="stylesheet" href="../css/admin/admin-main.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Dropdown styles for action buttons */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none; /* Hidden by default */
            position: absolute;
            right: 0;
            background-color: var(--white);
            min-width: 180px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            z-index: 10;
            border-radius: 6px;
            padding: 0.5rem 0;
            border: 1px solid #eee;
        }

        .dropdown-menu.show {
            display: block; /* Show when active */
        }

        .dropdown-item {
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            text-decoration: none;
            display: block;
            font-size: 0.875rem;
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
        }

        /* Table action button improvements */
        .data-table .actions, .table .actions {
            text-align: right;
        }
        .data-table .btn, .table .btn {
            white-space: nowrap;
        }
    </style>
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
                <a href="manage-gallery.php" class="menu-item <?php echo ($current_page == 'manage-gallery.php') ? 'active' : ''; ?>">
                    <span class="menu-icon">🖼️</span>
                    <span class="menu-text">Gallery</span>
                </a>
                <a href="manage-about.php" class="menu-item <?php echo ($current_page == 'manage-about.php') ? 'active' : ''; ?>">
                    <span class="menu-icon">📝</span>
                    <span class="menu-text">About Page</span>
                </a>
                <a href="contact-messages.php" class="menu-item <?php echo ($current_page == 'contact-messages.php') ? 'active' : ''; ?>">
                    <span class="menu-icon">✉️</span>
                    <span class="menu-text">Messages</span>
                </a>
                <a href="analytics.php" class="menu-item <?php echo ($current_page == 'analytics.php') ? 'active' : ''; ?>">
                    <span class="menu-icon">📊</span>
                    <span class="menu-text">Analytics</span>
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
                <div class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </div>

                <div class="header-left">
                    <div class="brand">
                        <h1><?php echo SITE_NAME; ?></h1>
                        <small class="page-title"><?php echo $page_title; ?></small>
                    </div>
                    <div class="breadcrumb">Home / <?php echo $page_title; ?></div>
                </div>

                <div class="header-right">
                    <div class="notifications-wrapper">
                        <button class="notification-btn" id="notifBtn" aria-label="Notifications" title="Notifications">
                            <!-- simple bell icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                            <span class="notif-badge" id="notifBadge">0</span>
                        </button>
                        <div class="notifications-menu" id="notificationsMenu" role="menu">
                            <div class="notif-header">
                                <h4>Notifications</h4>
                            </div>
                            <div class="notif-list" id="notifList">
                                <div class="notif-item" style="padding:1rem;color:#666;">Loading...</div>
                            </div>
                            <div class="notif-footer">
                                <a href="contact-messages.php">View All Messages</a>
                            </div>
                        </div>
                    </div>

                    <div class="admin-user" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo htmlspecialchars($adminAvatar); ?>" alt="Admin avatar" class="avatar">
                        <div class="user-info">
                            <div class="user-name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                        </div>
                        <div class="user-menu" role="menu" aria-label="User menu">
                            <a href="profile.php">Profile</a>
                            <a href="settings.php">Settings</a>
                            <a href="admin-logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="admin-content">