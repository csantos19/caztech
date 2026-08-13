<?php
// Detect the current page for active-link highlighting
$_current_page = basename($_SERVER['PHP_SELF'], '.php'); // e.g. "index", "login"
$_is_index     = ($_current_page === 'index');

// Helper: returns nav link classes with active state if current hash/page matches
function nav_link(string $href, string $label): void {
    $base = 'px-3 py-2 rounded-md text-sm font-medium transition-colors';
    // On the index page, use simple anchor links for smooth scrolling
    global $_is_index, $_nav_root;
    if ($_is_index && str_contains($href, 'index.php#')) {
        $href = str_replace($_nav_root . 'index.php#', '#', $href);
    }
    echo '<a href="' . $href . '" class="' . $base . ' hover:bg-accent hover:text-accent-foreground text-foreground/60 hover:text-foreground">' . $label . '</a>';
}

// Image/asset root for navbar (always root-relative on index, fallback for others)
$_nav_root = (isset($_root) ? $_root : '');
?>
<nav class="sticky top-0 z-50 w-full border-b border-border/40 bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 transition-colors duration-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

    <!-- Logo -->
    <a href="<?php echo $_nav_root; ?>index.php"
       class="flex items-center group transition-transform hover:scale-105 active:scale-95 duration-200">
      <img src="<?php echo $_nav_root; ?>image/CAZTECH.png"
           alt="CAZTech Logo"
           class="h-14 sm:h-16 w-auto max-w-[280px] object-contain"
           style="transform: scale(1.5) !important; transform-origin: left center;">
    </a>

    <!-- Desktop Navigation Links -->
    <div class="hidden md:flex items-center gap-1">
      <!-- Theme Toggle: restored beside Services -->
      <button id="theme-toggle" type="button"
              class="inline-flex items-center justify-center rounded-md h-10 w-10 hover:bg-accent hover:text-accent-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              aria-label="Toggle theme">
        <svg id="theme-toggle-dark-icon" class="hidden h-[1.2rem] w-[1.2rem]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
        </svg>
        <svg id="theme-toggle-light-icon" class="hidden h-[1.2rem] w-[1.2rem]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="12" cy="12" r="4"/>
          <path d="M12 2v2M12 20v2m-7.07-14.07 1.41 1.41M18.66 18.66l1.41 1.41M2 12h2M20 12h2m-4.93-7.07-1.41 1.41M6.34 18.66l-1.41 1.41"/>
        </svg>
        <span class="sr-only">Toggle theme</span>
      </button>

      <?php nav_link($_nav_root . 'index.php#services', 'Services'); ?>

      <?php nav_link($_nav_root . 'index.php#projects', 'Projects'); ?>
      <?php nav_link($_nav_root . 'index.php#about', 'About'); ?>
      <?php nav_link($_nav_root . 'index.php#contact', 'Contact'); ?>

    </div>

    <!-- Mobile: theme + hamburger -->
    <div class="md:hidden flex items-center gap-1">
      <!-- Theme Toggle -->
      <button id="theme-toggle-mobile" type="button"
              class="inline-flex items-center justify-center rounded-md h-10 w-10 hover:bg-accent hover:text-accent-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              aria-label="Toggle theme">
        <svg id="theme-toggle-mobile-dark-icon" class="hidden h-[1.2rem] w-[1.2rem]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
        </svg>
        <svg id="theme-toggle-mobile-light-icon" class="hidden h-[1.2rem] w-[1.2rem]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
          <circle cx="12" cy="12" r="4"/>
          <path d="M12 2v2M12 20v2m-7.07-14.07 1.41 1.41M18.66 18.66l1.41 1.41M2 12h2M20 12h2m-4.93-7.07-1.41 1.41M6.34 18.66l-1.41 1.41"/>
        </svg>
        <span class="sr-only">Toggle theme</span>
      </button>

      <!-- Hamburger Button -->
      <button id="mobile-menu-btn" type="button"
              class="inline-flex items-center justify-center rounded-md h-10 w-10 hover:bg-accent hover:text-accent-foreground transition-colors"
              aria-expanded="false" aria-controls="mobile-menu" aria-label="Open menu">
        <svg id="hamburger-icon" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="18" y2="18"/>
        </svg>
        <svg id="close-icon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
        </svg>
      </button>
    </div>

  </div>

  <!-- Mobile Dropdown Menu -->
  <div id="mobile-menu" class="hidden md:hidden border-t border-border/40 bg-background/95 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 py-3 flex flex-col gap-1">
      <a href="<?php echo $_is_index ? '#services' : $_nav_root . 'index.php#services'; ?>"
         class="px-3 py-2.5 rounded-md text-sm font-medium text-foreground/70 hover:bg-accent hover:text-accent-foreground transition-colors">Services</a>
      <a href="<?php echo $_is_index ? '#projects' : $_nav_root . 'index.php#projects'; ?>"
         class="px-3 py-2.5 rounded-md text-sm font-medium text-foreground/70 hover:bg-accent hover:text-accent-foreground transition-colors">Projects</a>
      <a href="<?php echo $_is_index ? '#about' : $_nav_root . 'index.php#about'; ?>"
         class="px-3 py-2.5 rounded-md text-sm font-medium text-foreground/70 hover:bg-accent hover:text-accent-foreground transition-colors">About</a>
      <a href="<?php echo $_is_index ? '#contact' : $_nav_root . 'index.php#contact'; ?>"
         class="px-3 py-2.5 rounded-md text-sm font-medium text-foreground/70 hover:bg-accent hover:text-accent-foreground transition-colors">Contact</a>
    </div>
  </div>

</nav>
