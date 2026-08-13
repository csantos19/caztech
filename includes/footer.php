<?php
// Resolve asset root for footer (same logic as head.php / navbar.php)
$_foot_root = (isset($_root) ? $_root : '');
?>
<footer class="border-t bg-background relative z-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
      <!-- Brand & Description -->
      <div class="md:col-span-2 space-y-4">
        <a href="<?php echo $_foot_root; ?>index.php" class="flex items-center">
          <img src="<?php echo $_foot_root; ?>image/CAZTECH.png" alt="CAZTech Logo" class="h-16 sm:h-20 w-auto max-w-[280px] object-contain">
        </a>
        <p class="text-sm text-muted-foreground leading-relaxed max-w-sm">
          A team of dedicated software developers focused on building performant web applications, modern systems, and scalable digital solutions for businesses.
        </p>
      </div>

      <!-- Quick Links -->
      <div class="space-y-4">
        <h4 class="text-sm font-semibold tracking-tight">Quick Links</h4>
        <ul class="space-y-3 pl-0 list-none">
          <li><a href="<?php echo $_foot_root; ?>index.php#services" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Services</a></li>
          <li><a href="<?php echo $_foot_root; ?>index.php#projects" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Projects</a></li>
          <li><a href="<?php echo $_foot_root; ?>index.php#about" class="text-sm text-muted-foreground hover:text-foreground transition-colors">About Us</a></li>
        </ul>
      </div>

      <!-- Connect -->
      <div class="space-y-4">
        <h4 class="text-sm font-semibold tracking-tight">Connect</h4>
        <ul class="space-y-3 pl-0 list-none">
          <li>
            <a href="https://www.facebook.com/caztechsolutions.works" target="_blank"
               class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition-colors group">
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
              </svg>
              Facebook
              <svg class="h-3 w-3 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
              </svg>
            </a>
          </li>
          <li>
            <a href="<?php echo $_foot_root; ?>index.php#contact" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Send an Email</a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-4 pb-4">
      <p class="text-sm text-muted-foreground">&copy; <?php echo date('Y'); ?> CAZTech Solutions. All rights reserved.</p>

      <div class="flex items-center gap-4">
        <p class="text-sm text-muted-foreground flex items-center gap-1">
          Built with
          <svg class="h-4 w-4 text-red-500 fill-red-500" viewBox="0 0 24 24">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
          </svg>
          in Bulacan, PH
        </p>
        <!-- Admin link (matching padilla footer pattern) -->
        <a href="/CAZTECH/admin/index.php"
           class="text-sm text-muted-foreground hover:text-foreground transition-colors underline underline-offset-2 hover:underline-offset-4 relative z-20 cursor-pointer">
          Admin
        </a>
      </div>
    </div>
  </div>
</footer>

<!-- Same Floating AI Assistant but themed precisely -->
<div class="fixed bottom-6 right-6 z-40 pointer-events-none">
<div class="pointer-events-auto">
    <div id="ai-chat-window" class="opacity-0 translate-y-4 pointer-events-none transition-all duration-300 transform mb-4 w-[340px] bg-card border rounded-xl shadow-xl overflow-hidden flex flex-col origin-bottom-right">
        <!-- Header -->
        <div class="bg-primary p-4 text-primary-foreground flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-background/20 rounded-full flex items-center justify-center backdrop-blur-sm border-white/10 border">
                    <svg class="w-4 h-4 text-primary-foreground" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm leading-none">CAZ Assistant</h4>
                    <p class="text-[11px] text-primary-foreground/70 mt-1">AI Powered Support</p>
                </div>
            </div>
            <button id="ai-chat-close" class="text-primary-foreground/70 hover:text-primary-foreground transition-colors rounded-sm opacity-70 ring-offset-background hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        
        <!-- Body bg-muted/50 -->
        <div class="p-4 h-72 overflow-y-auto bg-muted/30 flex flex-col gap-4">
            <div class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-white flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm overflow-hidden border">
                    <img src="image/CAZTECH.png" alt="C" class="w-full h-full object-contain p-1">
                </div>
                <div class="bg-card border p-3 rounded-2xl rounded-tl-sm text-sm text-foreground shadow-sm leading-relaxed">
                    Hello! I'm the CAZTech AI Assistant. How can I help you regarding our services, tech stack, or portfolio?
                </div>
            </div>
            
            <div class="flex flex-col gap-2 mt-1 ml-10">
                <button class="text-left text-xs bg-card border border-primary/20 hover:border-primary/50 text-foreground px-3 py-2 rounded-lg transition-all shadow-sm active:scale-95 group">
                    <span class="group-hover:text-primary transition-colors">View Portfolio Projects</span>
                </button>
                <button class="text-left text-xs bg-card border border-primary/20 hover:border-primary/50 text-foreground px-3 py-2 rounded-lg transition-all shadow-sm active:scale-95 group">
                    <span class="group-hover:text-primary transition-colors">What is your tech stack?</span>
                </button>
                <button class="text-left text-xs bg-card border border-primary/20 hover:border-primary/50 text-foreground px-3 py-2 rounded-lg transition-all shadow-sm active:scale-95 group">
                    <span class="group-hover:text-primary transition-colors">Contact information</span>
                </button>
            </div>
        </div>
        
        <!-- Input -->
        <div class="p-3 bg-card border-t flex gap-2">
            <input type="text" placeholder="Ask anything about our service..." class="flex h-10 w-full rounded-full border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 transition-all">
            <button class="inline-flex items-center justify-center whitespace-nowrap rounded-full text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 w-10 flex-shrink-0 active:scale-95">
                <svg class="h-4 w-4 ml-0.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
            </button>
        </div>
    </div>
    
    <!-- Bubble Button -->
    <button id="ai-chat-toggle" class="h-14 w-14 rounded-full bg-primary text-primary-foreground shadow-xl hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 flex items-center justify-center float-right focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 group border border-primary-foreground/10">
        <svg class="h-6 w-6 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
    </button>
</div>
</div>

<!-- Main Scripts -->
<script src="<?php echo $_foot_root; ?>assets/js/main.js?v=<?php echo time(); ?>"></script>
