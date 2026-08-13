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
      <img src="<?php echo $_nav_root; ?>image/Logo1.png"
           alt="CAZTech Logo"
           class="h-28 w-auto object-contain">
    </a>

    <!-- Desktop Navigation Links -->
    <div class="hidden md:flex items-center gap-1">
      <?php nav_link($_nav_root . 'index.php#services', 'Services'); ?>
      <?php nav_link($_nav_root . 'index.php#projects', 'Projects'); ?>
      <?php nav_link($_nav_root . 'index.php#about', 'About'); ?>
      <?php nav_link($_nav_root . 'index.php#contact', 'Contact'); ?>

    </div>

    <!-- Mobile: right side (theme + hamburger) -->
    <div class="md:hidden flex items-center gap-1">
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
