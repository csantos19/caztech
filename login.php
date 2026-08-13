<?php
/**
 * login.php
 * Admin Login page — styled after padilla.vercel.app/admin/login
 * Uses shared head.php for Tailwind + theme setup.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, skip to dashboard
if (isset($_SESSION['caztech_admin']) && $_SESSION['caztech_admin'] === true) {
    header('Location: admin/index.php');
    exit;
}

// Read query-string messages
$error      = $_GET['error']      ?? '';
$logged_out = $_GET['logged_out'] ?? '';

$page_title       = 'Admin Login | CAZTech Solutions';
$page_description = 'CAZTech Solutions admin portal login.';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<?php include 'includes/head.php'; ?>
<body class="login-page bg-background font-sans antialiased text-foreground">

  <div class="min-h-screen flex flex-col items-center justify-center px-4">

    <!-- Code Icon (matching padilla's logo block) -->
    <div class="mb-8">
      <div class="h-20 w-20 rounded-2xl bg-primary flex items-center justify-center overflow-hidden shadow-sm">
        <svg class="h-10 w-10 text-primary-foreground" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <polyline points="16 18 22 12 16 6"></polyline>
          <polyline points="8 6 2 12 8 18"></polyline>
        </svg>
      </div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md rounded-xl border bg-card shadow-sm text-card-foreground">

      <div class="flex flex-col space-y-1.5 p-6 text-center">
        <h3 class="text-2xl font-bold tracking-tight text-foreground">Admin Login</h3>
      </div>

      <div class="px-6 pb-6 pt-0 space-y-6">

      <!-- Flash Messages -->
      <?php if ($error === 'invalid_credentials'): ?>
        <div id="flash-error" class="flex items-center gap-2 rounded-lg border border-destructive/30 bg-destructive/10 px-4 py-3 text-sm text-destructive">
          <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
          </svg>
          Invalid email or password. Please try again.
        </div>
      <?php endif; ?>

      <?php if ($logged_out === '1'): ?>
        <div id="flash-success" class="flex items-center gap-2 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-600 dark:text-green-400">
          <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="20 6 9 17 4 12"/>
          </svg>
          You have been logged out successfully.
        </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="includes/login_process.php" method="POST" class="space-y-4" id="login-form" novalidate>

        <!-- Email Field -->
        <div class="space-y-2">
          <label for="email" class="text-sm font-medium leading-none">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            autocomplete="email"
            autofocus
            placeholder="admin@caztech.com"
            value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>"
            class="flex h-10 w-full rounded-md border border-input bg-white dark:bg-slate-900 text-black dark:text-white px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 transition-colors"
            required
          >
          <p class="text-xs text-destructive hidden" id="email-error">Please enter a valid email.</p>
        </div>

        <!-- Password Field with toggle -->
        <div class="space-y-2">
          <label for="password" class="text-sm font-medium leading-none">Password</label>
          <div class="relative">
            <input
              type="password"
              id="password"
              name="password"
              autocomplete="current-password"
              placeholder="••••••••"
              class="flex h-10 w-full rounded-md border border-input bg-white dark:bg-slate-900 text-black dark:text-white px-3 py-2 pr-10 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 transition-colors"
              required
            >
            <!-- Show/Hide Password Toggle -->
            <button
              type="button"
              id="pw-toggle"
              aria-label="Toggle password visibility"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring rounded-sm"
            >
              <!-- Eye open icon (shown by default = password hidden) -->
              <svg id="pw-icon-show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              <!-- Eye off icon (hidden by default) -->
              <svg id="pw-icon-hide" class="h-4 w-4 hidden" fill="none" stroke="currentColor" stroke-width="2"
                   stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
          <p class="text-xs text-destructive hidden" id="password-error">Password cannot be empty.</p>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          id="sign-in-btn"
          class="inline-flex w-full mt-2 items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
        >
          Sign In
          <!-- Arrow right icon -->
          <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
          </svg>
        </button>

      </form>

      </div>
    </div>

    <!-- Back to site link -->
    <p class="mt-6 text-xs text-muted-foreground">
      <a href="index.php" class="hover:text-foreground transition-colors underline underline-offset-4">← Back to CAZTech</a>
    </p>

  </div>

  <!-- Theme toggle (floating, top-right) -->
  <div class="fixed top-4 right-4 z-50">
    <button id="theme-toggle" type="button"
      class="inline-flex items-center justify-center h-9 w-9 rounded-md hover:bg-accent hover:text-accent-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">
      <svg id="theme-toggle-dark-icon" class="hidden h-[1.2rem] w-[1.2rem]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
      </svg>
      <svg id="theme-toggle-light-icon" class="hidden h-[1.2rem] w-[1.2rem]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="4"/>
        <path d="M12 2v2M12 20v2m-7.07-14.07 1.41 1.41M18.66 18.66l1.41 1.41M2 12h2M20 12h2m-4.93-7.07-1.41 1.41M6.34 18.66l-1.41 1.41"/>
      </svg>
      <span class="sr-only">Toggle theme</span>
      <span class="sr-only">Toggle theme</span>
      <span class="sr-only">Toggle theme</span>
    </button>
  </div>

  <!-- Login page JavaScript -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/login.js"></script>

</body>
</html>
