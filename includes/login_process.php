<?php
/**
 * login_process.php
 * Handles the POST request from login.php
 * Validates credentials and sets session on success.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// --- Credential Check ---
// Option A: Hardcoded credentials (simple, no DB needed)
// Change these to your real credentials.
$admin_email    = 'admin@caztech.com';
$admin_password = 'admin123'; // NOTE: in production, use password_hash/verify

// Option B (future): Query from DB
// $stmt = $conn->prepare("SELECT password_hash FROM admin_users WHERE email = ?");
// $stmt->bind_param("s", $email);
// $stmt->execute();
// ...

if ($email === $admin_email && $password === $admin_password) {
    // ✅ Auth success
    $_SESSION['caztech_admin']       = true;
    $_SESSION['caztech_admin_email'] = $email;
    $_SESSION['caztech_login_time']  = time();

    // Redirect to admin dashboard
    header('Location: ../admin/index.php');
    exit;

} else {
    // ❌ Auth failure — redirect back with error flag
    header('Location: ../login.php?error=invalid_credentials');
    exit;
}
?>
