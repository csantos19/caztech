<?php
// Dynamic page meta — set $page_title / $page_description before including this file.
$_head_title = $page_title       ?? 'CAZTech Solutions | Build Smarter. Ship Faster.';
$_head_desc  = $page_description ?? 'CAZTech Solutions – Student-founded team building websites, systems, and apps for small and big companies.';

// Resolve correct root path for assets
$_depth      = substr_count(str_replace('\\', '/', $_SERVER['PHP_SELF']), '/') - 1;
$_root       = str_repeat('../', max(0, $_depth - 1));
if ($_depth <= 1) $_root = ''; 
?>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($_head_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($_head_desc); ?>" />

  <script>
    // Use the saved preference; default to dark mode for first-time visitors.
    const savedTheme = localStorage.getItem('theme');
    document.documentElement.classList.toggle('dark', savedTheme !== 'light');
  </script>

  <!-- Tailwind Configuration -->
  <script>
    window.tailwind = window.tailwind || {};
    tailwind.config = {
      darkMode: ["class"],
      theme: {
        extend: {
          colors: {
            border: "hsl(var(--border) / <alpha-value>)",
            input: "hsl(var(--input) / <alpha-value>)",
            ring: "hsl(var(--ring) / <alpha-value>)",
            background: "hsl(var(--background) / <alpha-value>)",
            foreground: "hsl(var(--foreground) / <alpha-value>)",
            primary: {
              DEFAULT: "hsl(var(--primary) / <alpha-value>)",
              foreground: "hsl(var(--primary-foreground) / <alpha-value>)",
            },
            secondary: {
              DEFAULT: "hsl(var(--secondary) / <alpha-value>)",
              foreground: "hsl(var(--secondary-foreground) / <alpha-value>)",
            },
            destructive: {
              DEFAULT: "hsl(var(--destructive) / <alpha-value>)",
              foreground: "hsl(var(--destructive-foreground) / <alpha-value>)",
            },
            muted: {
              DEFAULT: "hsl(var(--muted) / <alpha-value>)",
              foreground: "hsl(var(--muted-foreground) / <alpha-value>)",
            },
            accent: {
              DEFAULT: "hsl(var(--accent) / <alpha-value>)",
              foreground: "hsl(var(--accent-foreground) / <alpha-value>)",
            },
            popover: {
              DEFAULT: "hsl(var(--popover) / <alpha-value>)",
              foreground: "hsl(var(--popover-foreground) / <alpha-value>)",
            },
            card: {
              DEFAULT: "hsl(var(--card) / <alpha-value>)",
              foreground: "hsl(var(--card-foreground) / <alpha-value>)",
            },
          },
          borderRadius: {
            lg: "var(--radius)",
            md: "calc(var(--radius) - 2px)",
            sm: "calc(var(--radius) - 4px)",
          },
          fontFamily: {
            sans: ["Geist", "Inter", "sans-serif"],
          }
        }
      }
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">

  <style>
    :root {
      --background: 0 0% 100%;
      --foreground: 240 10% 3.9%;
      --card: 0 0% 100%;
      --card-foreground: 240 10% 3.9%;
      --popover: 0 0% 100%;
      --popover-foreground: 240 10% 3.9%;
      --primary: 240 5.9% 10%;
      --primary-foreground: 0 0% 98%;
      --secondary: 240 4.8% 95.9%;
      --secondary-foreground: 240 5.9% 10%;
      --muted: 240 4.8% 95.9%;
      --muted-foreground: 240 3.8% 46.1%;
      --accent: 240 4.8% 95.9%;
      --accent-foreground: 240 5.9% 10%;
      --destructive: 0 84.2% 60.2%;
      --destructive-foreground: 0 0% 98%;
      --border: 240 5.9% 90%;
      --input: 240 5.9% 90%;
      --ring: 240 10% 3.9%;
      --radius: 0.75rem;
    }

    .dark {
      --background: 240 10% 3.9%;
      --foreground: 0 0% 98%;
      --card: 240 10% 3.9%;
      --card-foreground: 0 0% 98%;
      --popover: 240 10% 3.9%;
      --popover-foreground: 0 0% 98%;
      --primary: 217.2 91.2% 59.8%;
      --primary-foreground: 222.2 47.4% 11.2%;
      --secondary: 240 3.7% 15.9%;
      --secondary-foreground: 0 0% 98%;
      --muted: 240 3.7% 15.9%;
      --muted-foreground: 240 5% 64.9%;
      --accent: 240 3.7% 15.9%;
      --accent-foreground: 0 0% 98%;
      --destructive: 0 62.8% 30.6%;
      --destructive-foreground: 0 0% 98%;
      --border: 240 3.7% 15.9%;
      --input: 240 3.7% 15.9%;
      --ring: 240 4.9% 83.9%;
    }

    body {
      background-color: hsl(var(--background));
      color: hsl(var(--foreground));
      font-family: 'Geist', 'Inter', sans-serif;
    }

    /* Keep contact-form labels and controls readable in both themes. */
    #contact label,
    #contact input:not([type="hidden"]),
    #contact textarea {
      background-color: hsl(var(--background));
      border-color: hsl(var(--input));
      color: hsl(var(--foreground));
      -webkit-text-fill-color: hsl(var(--foreground));
    }

    .dark #contact input:not([type="hidden"]),
    .dark #contact textarea {
      background-color: hsl(var(--secondary)) !important;
      border-color: hsl(var(--border)) !important;
      color: hsl(var(--foreground)) !important;
      -webkit-text-fill-color: hsl(var(--foreground)) !important;
    }

    #contact input::placeholder,
    #contact textarea::placeholder {
      color: hsl(var(--muted-foreground)) !important;
      -webkit-text-fill-color: hsl(var(--muted-foreground)) !important;
      opacity: 1;
    }

    /* Keep admin project-form controls readable while typing in both themes. */
    .admin-project-form input:not([type="file"]),
    .admin-project-form textarea,
    .admin-project-form select {
      background-color: hsl(var(--background)) !important;
      border-color: hsl(var(--input)) !important;
      color: hsl(var(--foreground)) !important;
      -webkit-text-fill-color: hsl(var(--foreground)) !important;
      caret-color: hsl(var(--foreground)) !important;
    }

    .dark .admin-project-form input:not([type="file"]),
    .dark .admin-project-form textarea,
    .dark .admin-project-form select {
      background-color: #1e293b !important;
      border-color: #475569 !important;
      color: #f8fafc !important;
      -webkit-text-fill-color: #f8fafc !important;
      caret-color: #f8fafc !important;
    }

    .admin-project-form input::placeholder,
    .admin-project-form textarea::placeholder {
      color: hsl(var(--muted-foreground)) !important;
      -webkit-text-fill-color: hsl(var(--muted-foreground)) !important;
      opacity: 1 !important;
    }

    .dark .admin-project-form input::placeholder,
    .dark .admin-project-form textarea::placeholder {
      color: #cbd5e1 !important;
      -webkit-text-fill-color: #cbd5e1 !important;
    }

    .admin-project-form select option {
      background-color: hsl(var(--background));
      color: hsl(var(--foreground));
    }

    .dark .admin-project-form select option {
      background-color: #1e293b;
      color: #f8fafc;
    }

    .admin-project-form input:focus,
    .admin-project-form textarea:focus,
    .admin-project-form select:focus {
      border-color: hsl(var(--ring)) !important;
    }

    /* Keep the CAZ Assistant input readable while typing in both themes. */
    #ai-chat-window input[type="text"] {
      background-color: hsl(var(--background)) !important;
      border-color: hsl(var(--input)) !important;
      color: hsl(var(--foreground)) !important;
      -webkit-text-fill-color: hsl(var(--foreground)) !important;
      caret-color: hsl(var(--foreground)) !important;
    }

    .dark #ai-chat-window input[type="text"] {
      background-color: #1e293b !important;
      border-color: #475569 !important;
      color: #f8fafc !important;
      -webkit-text-fill-color: #f8fafc !important;
      caret-color: #f8fafc !important;
    }

    #ai-chat-window input[type="text"]::placeholder {
      color: hsl(var(--muted-foreground)) !important;
      -webkit-text-fill-color: hsl(var(--muted-foreground)) !important;
      opacity: 1 !important;
    }

    .dark #ai-chat-window input[type="text"]::placeholder {
      color: #cbd5e1 !important;
      -webkit-text-fill-color: #cbd5e1 !important;
    }

    #ai-chat-window input[type="text"]:-webkit-autofill,
    #ai-chat-window input[type="text"]:-webkit-autofill:hover,
    #ai-chat-window input[type="text"]:-webkit-autofill:focus {
      -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
      -webkit-text-fill-color: #f8fafc !important;
      caret-color: #f8fafc !important;
    }

    * {
      border-color: hsl(var(--border));
    }

    .bg-hero-glow {
      background: radial-gradient(circle at 50% 120%, hsl(var(--primary) / 0.15) 0%, hsl(var(--primary) / 0.05) 50%, rgba(255, 255, 255, 0) 100%);
    }
    .dark .bg-hero-glow {
      background: radial-gradient(circle at 50% 120%, hsl(var(--primary) / 0.2) 0%, hsl(var(--primary) / 0.05) 50%, rgba(0, 0, 0, 0) 100%);
    }

    /* Fix autofill styling in dark mode */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
      -webkit-box-shadow: 0 0 0 30px hsl(var(--background)) inset !important;
      -webkit-text-fill-color: hsl(var(--foreground)) !important;
      caret-color: hsl(var(--foreground)) !important;
      transition: background-color 5000s ease-in-out 0s;
    }

    /* ── Marquee / News Ticker ── */
    @keyframes marquee-left {
      0%   { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }
    @keyframes marquee-right {
      0%   { transform: translateX(-50%); }
      100% { transform: translateX(0); }
    }
    .animate-marquee-left {
      animation: marquee-left 40s linear infinite;
      width: max-content;
    }
    .animate-marquee-right {
      animation: marquee-right 40s linear infinite;
      width: max-content;
    }
    .animate-marquee-left:hover,
    .animate-marquee-right:hover {
      animation-play-state: paused;
    }
    .marquee-wrapper {
      overflow: hidden;
      width: 100%;
    }
    .animate-marquee-left > div,
    .animate-marquee-right > div {
      min-width: 320px;
      max-width: 360px;
      flex-shrink: 0;
    }
  </style>

  <!-- Shared CSS utilities (login etc) -->
  <link rel="stylesheet" href="<?php echo $_root; ?>assets/css/style.css">
</head>
