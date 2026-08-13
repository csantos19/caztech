<?php
include 'includes/db_connect.php';
include 'includes/components.php';
$review_success = isset($_GET['review']) && $_GET['review'] === 'success';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<?php include 'includes/head.php'; ?>
<body class="min-h-screen bg-background font-sans antialiased text-foreground">

  <!-- Reusable Hero/Top Bar -->
  <?php include 'includes/navbar.php'; ?>

  <main class="flex-1">
    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-24 pb-16 md:pt-32 md:pb-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
          
          <!-- Left Content -->
          <div class="flex flex-col space-y-8 relative z-10 animate-in fade-in slide-in-from-bottom-8 duration-700">
            <div class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80 w-fit">
              <span class="flex h-2 w-2 rounded-full bg-primary mr-2"></span>
              Available for New Projects
            </div>
            
            <div class="space-y-4">
              <h1 class="text-5xl md:text-7xl font-bold tracking-tight lg:leading-[1.1]">
                 Build Smarter.<br>
                 Ship <span class="text-primary">Faster.</span>
              </h1>
              <p class="max-w-[42rem] leading-normal text-muted-foreground sm:text-lg sm:leading-8">
                CAZTech Solutions — Student-founded developers building high-performance web applications, modern systems, and custom software for businesses. 
              </p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
              <a href="#projects" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-11 px-8">
                View Projects
              </a>
              <a href="#contact" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-11 px-8">
                Contact Us
              </a>
            </div>
            
            <!-- Social Links (mimicking Padilla) -->
            <div class="pt-4 flex items-center gap-4">
              <a href="https://github.com/caztechsolutions" target="_blank" rel="noopener noreferrer" class="h-10 w-10 flex items-center justify-center rounded-full border border-border/50 bg-background hover:bg-accent hover:text-primary transition-all shadow-sm group">
                <svg class="h-5 w-5 text-muted-foreground group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.87 8.17 6.84 9.5.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34-.45-1.16-1.1-1.48-1.1-1.48-.9-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.87 1.52 2.34 1.07 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.92 0-1.11.38-2 1.03-2.71-.1-.25-.45-1.29.1-2.64 0 0 .84-.27 2.75 1.02.79-.22 1.65-.33 2.5-.33.85 0 1.71.11 2.5.33 1.91-1.29 2.75-1.02 2.75-1.02.55 1.35.2 2.39.1 2.64.65.71 1.03 1.6 1.03 2.71 0 3.82-2.34 4.66-4.57 4.91.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0012 2z"/></svg>
                <span class="sr-only">GitHub</span>
              </a>
              <a href="https://www.facebook.com/caztechsolutions.works" target="_blank" rel="noopener noreferrer" class="h-10 w-10 flex items-center justify-center rounded-full border border-border/50 bg-background hover:bg-accent hover:text-primary transition-all shadow-sm group">
                <svg class="h-5 w-5 text-muted-foreground group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                <span class="sr-only">Facebook</span>
              </a>
              <a href="mailto:contact@caztechsolutions.works" class="h-10 w-10 flex items-center justify-center rounded-full border border-border/50 bg-background hover:bg-accent hover:text-primary transition-all shadow-sm group">
                <svg class="h-5 w-5 text-muted-foreground group-hover:text-primary transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4C2.9 4 2.01 4.9 2.01 6L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                <span class="sr-only">Email</span>
              </a>
            </div>
          </div>

          <!-- Right Content (The "J" Box clone -> "C") -->
          <div class="relative flex items-center justify-center lg:justify-end animate-in fade-in slide-in-from-right-8 duration-1000 delay-200">
            <div class="relative group">
              <!-- Glow Effect -->
              <div class="absolute -inset-4 bg-primary/20 blur-3xl rounded-full -z-10 transition duration-1000 group-hover:bg-primary/30 group-hover:blur-2xl"></div>
              
              <!-- Avatar Box -->
              <div class="h-64 w-64 sm:h-80 sm:w-80 rounded-3xl bg-card flex items-center justify-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-border/50 relative overflow-hidden group-hover:-translate-y-2 transition-transform duration-500">
                  <img src="image/CAZTECH.png" alt="CAZTech" class="w-full h-auto max-w-full object-contain relative z-10 transition-transform duration-500 group-hover:scale-110">
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16 md:py-24 bg-accent/30 border-y">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-4">
          <h2 class="text-3xl font-bold tracking-tight">Our Services</h2>
          <p class="text-muted-foreground">Comprehensive solutions tailored to your business needs.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Service 1: Web Development -->
          <div class="bg-card text-card-foreground border border-border p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6">
              <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3">Web Development</h3>
            <p class="text-muted-foreground text-sm leading-relaxed">
              Custom websites and web applications built with modern frameworks like React, Vue, and PHP. Responsive, fast, and SEO-optimized.
            </p>
          </div>

          <!-- Service 2: Business Systems -->
          <div class="bg-card text-card-foreground border border-border p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6">
              <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3">Business Systems</h3>
            <p class="text-muted-foreground text-sm leading-relaxed">
              Custom management systems, CRM solutions, inventory tracking, and workflow automation tailored to streamline your operations.
            </p>
          </div>

          <!-- Service 3: Mobile Apps -->
          <div class="bg-card text-card-foreground border border-border p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all group">
            <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6">
              <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-3">Mobile Apps</h3>
            <p class="text-muted-foreground text-sm leading-relaxed">
              Cross-platform mobile applications using React Native and Flutter. Deliver seamless experiences on iOS and Android devices.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Social Proof / What Clients Say Section -->
    <section id="testimonials" class="py-16 md:py-24 overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-4">
          <h2 class="text-3xl font-bold tracking-tight">What Clients Say</h2>
          <p class="text-muted-foreground">Trusted by businesses and project owners.</p>
        </div>

        <?php if ($review_success): ?>
        <div id="review-success" class="max-w-md mx-auto flex items-center gap-2 rounded-lg border border-primary/30 bg-primary/10 px-4 py-3 text-sm text-primary text-center justify-center">
          <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Thank you! Your review has been submitted and is pending approval.
        </div>
        <?php endif; ?>

        <?php
        $testimonials = [
            ['RC',   'RJV Clinic',      'Dental Partner',    'CAZTech delivered our clinic management system flawlessly. It completely transformed our document processing speeds. Highly recommended developer team.'],
            ['E',    'Storefront Inc.', 'E-Commerce Client', 'Fast execution and clean code. Their e-commerce solution helped us scale significantly during peak season. Excellent UI design.'],
            ['Tech', 'TechNova',        'Digital Agency',    'They handled our legacy codebase migration perfectly without any data loss. They act as actual partners rather than just freelancers.'],
            ['M',    'MediCore',        'Healthcare',        'The patient portal they built reduced our admin workload by 60%. Truly a game-changer for our clinic operations.'],
            ['J',    'JML Logistics',   'Transport Client',  'Their tracking system streamlined our entire fleet management. Real-time updates and zero downtime since launch.'],
            ['S',    'SparkDigital',    'Startup',           'From MVP to full product in just 4 weeks. CAZTech moved fast without cutting corners. Outstanding quality.'],
        ];
        ?>

        <!-- Marquee Row 1 — scrolls left -->
        <div class="relative marquee-wrapper">
          <!-- Fade edges -->
          <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-background to-transparent z-10 pointer-events-none"></div>
          <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-background to-transparent z-10 pointer-events-none"></div>

          <div class="flex gap-6 animate-marquee-left">
            <?php foreach ($testimonials as $t): ?>
              <?php render_testimonial($t[0], $t[1], $t[2], $t[3]); ?>
            <?php endforeach; ?>
            <?php foreach ($testimonials as $t): ?>
              <?php render_testimonial($t[0], $t[1], $t[2], $t[3]); ?>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Marquee Row 2 — scrolls right (reversed order) -->
        <div class="relative marquee-wrapper">
          <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-background to-transparent z-10 pointer-events-none"></div>
          <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-background to-transparent z-10 pointer-events-none"></div>

          <div class="flex gap-6 animate-marquee-right">
            <?php $reversed = array_reverse($testimonials); ?>
            <?php foreach ($reversed as $t): ?>
              <?php render_testimonial($t[0], $t[1], $t[2], $t[3]); ?>
            <?php endforeach; ?>
            <?php foreach ($reversed as $t): ?>
              <?php render_testimonial($t[0], $t[1], $t[2], $t[3]); ?>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Submit Review Form -->
        <div class="max-w-lg mx-auto">
          <details class="group">
            <summary class="flex items-center justify-center gap-2 cursor-pointer text-sm font-medium text-primary hover:text-primary/80 transition-colors list-none">
              <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Submit a Review
            </summary>
            <form action="includes/process.php" method="POST" class="mt-6 bg-card border rounded-2xl p-6 space-y-5 shadow-sm">
              <input type="hidden" name="form_type" value="review">
              <div class="space-y-2">
                <label for="review-name" class="text-sm font-medium leading-none">Your Name / Business</label>
                <input type="text" id="review-name" name="name" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="e.g. RJV Clinic" required>
              </div>
              <div class="space-y-2">
                <label for="review-email" class="text-sm font-medium leading-none">Email (optional)</label>
                <input type="email" id="review-email" name="business" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="john@example.com">
              </div>
              <div class="space-y-2">
                <label for="review-text" class="text-sm font-medium leading-none">Your Review</label>
                <textarea id="review-text" name="project" class="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="Share your experience working with CAZTech..." required></textarea>
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium leading-none">Rating</label>
                <div class="flex items-center gap-1 review-star-rating cursor-pointer">
                  <input type="hidden" name="rating" id="review-rating-value" value="5">
                  <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                  <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
              </div>
              <button type="submit" class="inline-flex w-full items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 bg-primary text-primary-foreground hover:bg-primary/90 h-11">
                Submit Review
              </button>
              <p class="text-xs text-muted-foreground text-center">Reviews are subject to admin approval before appearing publicly.</p>
            </form>
          </details>
        </div>
      </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="py-16 md:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center space-y-4 mb-10">
          <h2 class="text-3xl font-bold tracking-tight">My Projects</h2>
          <p class="text-muted-foreground max-w-2xl mx-auto">Explore the custom systems, scalable architectures, and beautiful responsive interfaces we’ve developed.</p>
        </div>

        <!-- Filter Pill Structure -->
        <div class="flex flex-wrap justify-center gap-2 mb-10" id="project-filters">
          <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2 active" data-filter="all">All</button>
          <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2" data-filter="Web Development">Web Development</button>
          <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2" data-filter="Business Systems">Business Systems</button>
          <button class="filter-btn inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2" data-filter="Mobile Apps">Mobile Apps</button>
        </div>

        <div id="projects-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
          // ── Project Skeletons — rendered via reusable function ──
          render_project_skeleton();
          render_project_skeleton('hidden md:flex');
          render_project_skeleton('hidden lg:flex');
          ?>
        </div>
      </div>
    </section>

    <!-- Meet The Team Section -->
    <section class="py-16 md:py-24 bg-accent/30 border-y">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-4">
          <h2 class="text-3xl font-bold tracking-tight">Meet the Team</h2>
          <p class="text-muted-foreground">The visionaries behind CAZTech core engine.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-3xl mx-auto">
          <?php
          // ── Team Members — rendered via reusable function ──
          $sql_team = "SELECT name, role, image_path FROM team_members ORDER BY id ASC";
          $res_team = $conn->query($sql_team);

          if ($res_team && $res_team->num_rows > 0) {
              while ($m = $res_team->fetch_assoc()) {
                  render_team_member($m['name'], $m['role'], $m['image_path']);
              }
          } else {
              // Fallback placeholder team if db is empty
              render_team_member('Christian', 'Full-Stack Developer');
              render_team_member('Ann', 'UI/UX Designer');
              render_team_member('Zildjan', 'Full-Stack Developer');
          }
          ?>
        </div>
      </div>
    </section>

    <!-- About & Skills Layout -->
    <section id="about" class="py-16 md:py-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-16">
        
        <!-- About Left -->
        <div class="space-y-6">
          <h2 class="text-3xl font-bold tracking-tight">About Us</h2>
          <p class="text-muted-foreground leading-relaxed">
            CAZTech is a premier technical collective built by driven students solving real-world challenges. We specialize in producing dynamic architectures alongside clean front-end frameworks.
          </p>
          <p class="text-muted-foreground leading-relaxed">
            Our mission is delivering zero-friction systems—whether that is an advanced clinical management suite or a high-velocity landing page. We code responsibly, ensuring our platforms stand the test of time and scale gracefully.
          </p>
          <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 mt-4">
            Download Profile
          </button>
        </div>

        <!-- Skills Right -->
        <div class="space-y-6">
          <h2 class="text-2xl font-bold tracking-tight">Skills</h2>
          
          <?php
          // ── Skills — rendered via reusable function ──
          $skills = [
              ['Frontend Dev (React, Tailwind)', 95, 0],
              ['Backend Systems (PHP, Node)',    85, 150],
              ['Architectural Design',           90, 300],
          ];
          ?>
          <div class="space-y-5">
            <?php foreach ($skills as $s): ?>
              <?php render_skill($s[0], $s[1], $s[2]); ?>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </section>

    <!-- Feedback & Contact Form -->
    <section id="contact" class="py-16 md:py-24 bg-accent/30 border-t">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-card border rounded-2xl p-8 sm:p-12 shadow-sm relative overflow-hidden">
           <!-- Subtle decor -->
           <div class="absolute -top-24 -right-24 h-48 w-48 bg-primary/5 rounded-full blur-3xl"></div>

           <div class="text-center space-y-2 mb-10 relative z-10">
             <h2 class="text-3xl font-bold tracking-tight">Leave a Feedback / Contact</h2>
             <p class="text-muted-foreground">We respond diligently to all inquiries.</p>
           </div>
           
           <form action="includes/process.php" method="POST" class="space-y-6 relative z-10">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label for="name" class="text-sm font-medium leading-none text-foreground">Your Name</label>
                  <input type="text" id="name" name="name" class="flex h-10 w-full rounded-md border border-input bg-background dark:bg-slate-800 text-foreground dark:border-slate-600 px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="John Doe" required>
                </div>
                <div class="space-y-2">
                  <label for="business" class="text-sm font-medium leading-none text-foreground">Email Address</label>
                  <input type="email" id="business" name="business" class="flex h-10 w-full rounded-md border border-input bg-background dark:bg-slate-800 text-foreground dark:border-slate-600 px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="john@example.com" required>
                </div>
              </div>
              
              <div class="space-y-2">
                <label for="project" class="text-sm font-medium leading-none text-foreground">How can we help?</label>
                <textarea id="project" name="project" class="flex min-h-[120px] w-full rounded-md border border-input bg-background dark:bg-slate-800 text-foreground dark:border-slate-600 px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50" placeholder="Tell us about your project or leave feedback..." required></textarea>
              </div>

              <!-- Rating Component -->
              <div class="space-y-2">
                 <label class="text-sm font-medium leading-none text-foreground">Optional Rating</label>
                 <div class="flex items-center gap-1 star-rating cursor-pointer">
                    <input type="hidden" name="rating" id="rating-value" value="5">
                    <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg class="w-6 h-6 text-yellow-400 hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                 </div>
              </div>
              
              <button class="inline-flex w-full items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-11">
                Send Message
              </button>
           </form>
        </div>
      </div>
    </section>

  </main>

  <!-- Reusable Footer -->
  <?php include 'includes/footer.php'; ?>

  <!-- ── Logo Lightbox Modal ─────────────────────────────────── -->
  <div id="logo-lightbox"
       class="fixed inset-0 z-[999] flex items-center justify-center p-6 opacity-0 pointer-events-none transition-opacity duration-300"
       role="dialog" aria-modal="true" aria-label="Logo preview">

    <!-- Backdrop -->
    <div id="lightbox-backdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

    <!-- Panel -->
    <div id="lightbox-panel"
         class="relative z-10 flex flex-col items-center gap-6 max-w-3xl w-full mx-4 bg-card border rounded-3xl shadow-2xl p-8 sm:p-10 transform scale-90 transition-transform duration-300">

      <!-- Close button -->
      <button id="lightbox-close"
              class="absolute top-4 right-4 h-8 w-8 rounded-full bg-muted flex items-center justify-center text-muted-foreground hover:bg-muted-foreground hover:text-background transition-colors"
              aria-label="Close" tabindex="-1">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <!-- Logo image -->
      <img id="lightbox-img" src="" alt="" class="max-h-[65vh] w-auto max-w-full object-contain rounded-2xl shadow-md">

      <!-- Caption -->
      <div class="text-center space-y-1">
        <p id="lightbox-caption" class="text-lg font-bold"></p>
        <p id="lightbox-category" class="text-xs font-semibold text-primary tracking-wider uppercase"></p>
      </div>
    </div>
  </div>

</body>
</html>
