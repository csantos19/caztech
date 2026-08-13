<?php
/**
 * auth.php
 * Session-based authentication helper.
 * Include this at the top of any protected page.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require the user to be logged in.
 * If not, redirect to login page.
 * @param string $redirect_to  URL to redirect unauthenticated users to
 */
function require_auth(string $redirect_to = '../login.php'): void {
    if (!is_logged_in()) {
        header('Location: ' . $redirect_to);
        exit;
    }
}

/**
 * Check if the admin user is currently logged in.
 * @return bool
 */
function is_logged_in(): bool {
    return isset($_SESSION['caztech_admin']) && $_SESSION['caztech_admin'] === true;
}

/**
 * Destroy the admin session (logout).
 */
function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
?>
