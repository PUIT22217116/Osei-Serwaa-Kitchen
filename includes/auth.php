<?php
/**
 * Checks if an admin is logged in. If not, redirects to the login page.
 */
function require_admin() {
    if (!is_admin()) {
        // Redirect to login page, preserving the current path for a potential redirect back.
        // Note: For security, ensure the redirect URL is validated if you implement this.
        header('Location: admin-login.php');
        exit;
    }
}

/**
 * Returns true if an admin is currently logged in, false otherwise.
 *
 * @return bool
 */
function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}
?>