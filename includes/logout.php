<?php
/**
 * logout.php
 * Destroys the admin session and redirects to login page.
 * Called via href="includes/logout.php" from admin pages.
 */

require_once __DIR__ . '/auth.php';

logout();

header('Location: ../login.php?logged_out=1');
exit;
?>
