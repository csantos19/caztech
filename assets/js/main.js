document.addEventListener('DOMContentLoaded', () => {
    // ── 1. Theme Toggle ────────────────────────────────────────────────────
    const themeButtons = [
        document.getElementById('theme-toggle'),
        document.getElementById('theme-toggle-mobile')
    ].filter(Boolean);

    const themeIconPairs = [
        {
            dark: document.getElementById('theme-toggle-dark-icon'),
            light: document.getElementById('theme-toggle-light-icon')
        },
        {
            dark: document.getElementById('theme-toggle-mobile-dark-icon'),
            light: document.getElementById('theme-toggle-mobile-light-icon')
        }
    ];

    function applyThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');
        themeIconPairs.forEach(({ dark, light }) => {
            // Show the sun in dark mode and the moon in light mode.
            dark?.classList.toggle('hidden', isDark);
            light?.classList.toggle('hidden', !isDark);
        });
    }

    function toggleTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        document.documentElement.classList.toggle('dark', !isDark);
        localStorage.setItem('theme', isDark ? 'light' : 'dark');
        applyThemeIcons();
    }

    if (themeButtons.length) {
        applyThemeIcons();
        themeButtons.forEach(button => button.addEventListener('click', toggleTheme));
    }

    // ── 2. Mobile Menu Toggle ──────────────────────────────────────────────
    const mobileMenuBtn  = document.getElementById('mobile-menu-btn');
    const mobileMenu     = document.getElementById('mobile-menu');
    const hamburgerIcon  = document.getElementById('hamburger-icon');
    const closeMenuIcon  = document.getElementById('close-icon');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('hidden');
            mobileMenu.classList.toggle('hidden', isOpen);
            hamburgerIcon?.classList.toggle('hidden', !isOpen);
            closeMenuIcon?.classList.toggle('hidden', isOpen);
            mobileMenuBtn.setAttribute('aria-expanded', String(!isOpen));
        });

        // Close mobile menu when any link inside it is clicked
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                hamburgerIcon?.classList.remove('hidden');
                closeMenuIcon?.classList.add('hidden');
                mobileMenuBtn.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // ── 3. Skeleton Loading & Projects (Dynamic) ───────────────────────────
    const projectsContainer = document.getElementById('projects-container');

    // ── Lightbox helpers ────────────────────────────────────────────────────
    const lightbox     = document.getElementById('logo-lightbox');
    const lbImg        = document.getElementById('lightbox-img');
    const lbCaption    = document.getElementById('lightbox-caption');
    const lbCategory   = document.getElementById('lightbox-category');
    const lbPanel      = document.getElementById('lightbox-panel');
    const lbClose      = document.getElementById('lightbox-close');
    const lbBackdrop   = document.getElementById('lightbox-backdrop');

    function openLightbox(src, title, category) {
        if (!lightbox) return;
        lbImg.src      = src;
        lbImg.alt      = title;
        lbCaption.textContent  = title;
        lbCategory.textContent = category;
        lightbox.classList.remove('opacity-0', 'pointer-events-none');
        lightbox.classList.add('opacity-100');
        lbPanel.classList.remove('scale-90');
        lbPanel.classList.add('scale-100');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        if (!lightbox) return;
        lightbox.classList.add('opacity-0', 'pointer-events-none');
        lightbox.classList.remove('opacity-100');
        lbPanel.classList.add('scale-90');
        lbPanel.classList.remove('scale-100');
        document.body.style.overflow = '';
        setTimeout(() => { lbImg.src = ''; }, 300);
    }

    lbClose?.addEventListener('click', closeLightbox);
    lbBackdrop?.addEventListener('click', closeLightbox);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

    // Attach lightbox to server-rendered team photos
    document.querySelectorAll('.team-photo-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            openLightbox(btn.dataset.img, btn.dataset.title, btn.dataset.category);
        });
    });
    // ───────────────────────────────────────────────────────────────────────

    // ── Project Filtering Setup ──────────────────────────────────────────
    const filterButtons = document.querySelectorAll('#project-filters .filter-btn');
    let allProjects = [];

    function renderProjects(projects) {
        projectsContainer.innerHTML = '';
        if (projects.length === 0) {
            projectsContainer.innerHTML = '<p class="text-muted-foreground col-span-full text-center">No projects found.</p>';
            return;
        }
        projects.forEach(proj => {
            // Icon: clickable if uploaded image, otherwise plain SVG
            const hasImage = !!proj.icon_image;
            const iconContent = hasImage
                ? `<img src="${proj.icon_image}" alt="${proj.title}" class="w-10 h-10 object-contain">`
                : (proj.icon_svg || `<svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`);

            // Wrap in a button for accessibility+click only if there's a real image
            const badgeTag    = hasImage ? 'button' : 'div';
            const badgeAttrs  = hasImage
                ? `type="button" data-img="${proj.icon_image}" data-title="${proj.title}" data-category="${proj.category}"
                   class="w-16 h-16 ${proj.bg_class || 'bg-secondary'} rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:-translate-y-1 transition-transform duration-300 cursor-zoom-in hover:ring-2 hover:ring-primary/50 focus:outline-none focus:ring-2 focus:ring-primary/70 project-logo-btn"`
                : `class="w-16 h-16 ${proj.bg_class || 'bg-secondary'} rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:-translate-y-1 transition-transform duration-300"`;

            const viewDetailsHref = proj.project_url ? proj.project_url : '#';
            const viewDetailsTarget = proj.project_url ? 'target="_blank" rel="noopener noreferrer"' : '';

            projectsContainer.innerHTML += `
                <div class="bg-card text-card-foreground border border-border p-8 rounded-2xl shadow-sm hover:shadow-xl transition-all group relative flex flex-col h-full hover:border-primary/50 project-card" data-category="${proj.category}">
                    <${badgeTag} ${badgeAttrs}>
                        ${iconContent}
                    </${badgeTag}>
                    <div class="flex-grow">
                        <span class="text-xs font-bold text-primary tracking-wider uppercase mb-2 block">${proj.category}</span>
                        <h3 class="text-xl font-bold mb-3">${proj.title}</h3>
                        <p class="text-muted-foreground text-sm leading-relaxed">${proj.description || ''}</p>
                    </div>
                    <div class="mt-8 pt-6 border-t">
                        <a href="${viewDetailsHref}" ${viewDetailsTarget} class="inline-flex items-center text-sm font-semibold text-primary gap-2 transition-all group-hover:gap-3">
                            View Details
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            `;
        });

        // Attach lightbox click handlers after DOM is populated
        projectsContainer.querySelectorAll('.project-logo-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                openLightbox(btn.dataset.img, btn.dataset.title, btn.dataset.category);
            });
        });
    }

    // Filter button click handlers
    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button styling
            filterButtons.forEach(b => {
                b.classList.remove('bg-primary', 'text-primary-foreground');
                b.classList.add('bg-background', 'border', 'border-input');
            });
            btn.classList.remove('bg-background', 'border', 'border-input');
            btn.classList.add('bg-primary', 'text-primary-foreground');

            // Filter projects
            const filter = btn.dataset.filter;
            const filteredProjects = filter === 'all' 
                ? allProjects 
                : allProjects.filter(p => p.category === filter);
            renderProjects(filteredProjects);
        });
    });

    if (projectsContainer) {
        fetch('api/get_projects.php')
            .then(res => res.json())
            .then(projects => {
                allProjects = projects; // Store for filtering
                renderProjects(projects);
            })
            .catch(err => {
                console.error('Error fetching projects:', err);
                projectsContainer.innerHTML = '<p class="text-destructive col-span-full text-center">Failed to load projects.</p>';
            });
    }

    // ── 4. Skills Progress Bar (Intersection Observer) ─────────────────────
    const skillBars = document.querySelectorAll('.skill-progress');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const targetWidth = entry.target.getAttribute('data-width');
                entry.target.style.width = targetWidth;
                entry.target.classList.remove('w-0');
                observer.unobserve(entry.target); // only animate once
            }
        });
    }, { threshold: 0.1 });
    skillBars.forEach(bar => observer.observe(bar));

    // ── 5. AI Chat Widget ──────────────────────────────────────────────────
    const aiToggle = document.getElementById('ai-chat-toggle');
    const aiWindow = document.getElementById('ai-chat-window');
    const aiClose  = document.getElementById('ai-chat-close');
    const aiInput  = document.querySelector('#ai-chat-window input[type="text"]');
    const aiSend   = document.querySelector('#ai-chat-window button:last-of-type');
    const aiBody   = document.querySelector('#ai-chat-window .overflow-y-auto');

    // Chat memory for context
    let chatHistory = [];
    let isTyping = false;

    function toggleChat(open) {
        if (open) {
            aiWindow?.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            aiWindow?.classList.add('opacity-100', 'translate-y-0');
            aiInput?.focus();
        } else {
            aiWindow?.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            aiWindow?.classList.remove('opacity-100', 'translate-y-0');
        }
    }

    aiToggle?.addEventListener('click', () => toggleChat(true));
    aiClose?.addEventListener('click', () => toggleChat(false));

    // Smart responses database
    const smartResponses = {
        greeting: {
            patterns: ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening', 'what\'s up', 'sup'],
            responses: [
                'Hey there! 👋 Welcome to CAZTech! How can I help you today?',
                'Hello! Great to meet you! I\'m here to help with anything about our services, projects, or team. What can I do for you?',
                'Hi! Thanks for reaching out to CAZTech! What brings you here today?'
            ]
        },
        services: {
            patterns: ['service', 'services', 'what do you do', 'what you do', 'offer', 'provides', 'help with', 'solutions'],
            responses: [
                'We offer three main services: **Web Development** (React, Vue, PHP), **Business Systems** (CRM, inventory, workflow automation), and **Mobile Apps** (React Native, Flutter). Which one interests you most?',
                'At CAZTech, we build: 🌐 Custom websites & web apps, 🏢 Business management systems, 📱 Cross-platform mobile apps. Would you like details on any specific service?'
            ]
        },
        pricing: {
            patterns: ['price', 'pricing', 'cost', 'how much', 'budget', 'quote', 'fee', 'charge', 'rates'],
            responses: [
                'Our pricing varies based on project scope and complexity. Small websites start around ₱15,000, while larger systems are custom quoted. Would you like to share your project details so I can give you a better estimate?',
                'Great question! We offer competitive student-friendly rates. 🎓 Web projects typically range ₱15k-50k, business systems ₱30k-100k+, and mobile apps ₱40k+. Every project is unique - want to tell me more about yours?'
            ]
        },
        techstack: {
            patterns: ['tech', 'tech stack', 'technology', 'framework', 'tools', 'languages', 'react', 'vue', 'php', 'database'],
            responses: [
                'Our tech stack is modern and robust! 💪 **Frontend:** React, Vue, Tailwind CSS | **Backend:** PHP, Node.js, MySQL | **Mobile:** React Native, Flutter | **Cloud:** AWS, Firebase. We pick the best tools for each project!',
                'We love using cutting-edge tech! For web apps: React/Vue with Tailwind. Backend: PHP/Node.js with MySQL. Mobile: React Native or Flutter. Everything deployed securely to AWS or Firebase. What tech are you using?'
            ]
        },
        timeline: {
            patterns: ['time', 'timeline', 'how long', 'duration', 'when', 'deadline', 'how soon', 'fast', 'quick'],
            responses: [
                'Timeline depends on complexity! 🚀 Simple websites: 1-2 weeks | Business systems: 3-6 weeks | Mobile apps: 4-8 weeks. Rush projects? We can often accommodate - tell me about your deadline!',
                'Great question! Most landing pages take 1-2 weeks, web apps 2-4 weeks, and mobile apps 1-2 months. We always communicate progress weekly. When do you need your project completed?'
            ]
        },
        portfolio: {
            patterns: ['portfolio', 'project', 'projects', 'work', 'example', 'past', 'previous', 'clients', 'showcase'],
            responses: [
                'Check out our projects section! We\'ve built clinic management systems, e-commerce platforms, and custom business tools. Want me to scroll to the projects section for you?',
                'We\'re proud of our work! 🏆 Recent projects include RJV Clinic Management System, an e-commerce platform for Storefront Inc., and legacy migration for TechNova. Want to see more?'
            ]
        },
        contact: {
            patterns: ['contact', 'email', 'phone', 'call', 'reach', 'message', 'send', 'form', 'talk to', 'speak'],
            responses: [
                'You can reach us at **contact@caztechsolutions.works** or use the contact form at the bottom of the page. We typically respond within 24 hours! 📧',
                'We\'d love to hear from you! 📩 Email: contact@caztechsolutions.works | Facebook: @caztechsolutions.works | Or fill out the contact form below. Expect a reply within 24 hours!'
            ]
        },
        team: {
            patterns: ['team', 'who are you', 'members', 'developers', 'founders', 'caztech', 'about you', 'company'],
            responses: [
                'CAZTech is a student-founded team of passionate developers! 👨‍💻👩‍💻 Our core team includes Christian (Full-Stack), Ann (UI/UX), and Zildjan (Full-Stack). We\'re based in Bulacan, Philippines and love solving real-world problems!',
                'We\'re CAZTech Solutions - a tech collective founded by driven students! 🎓 Our mission: deliver zero-friction systems that actually work. Based in Bulacan, PH. Want to know more about any team member?'
            ]
        },
        process: {
            patterns: ['process', 'how it works', 'steps', 'workflow', 'start', 'begin', 'hire', 'engage'],
            responses: [
                'Our process is simple: 1️⃣ **Discovery** - We learn about your needs | 2️⃣ **Proposal** - Detailed quote & timeline | 3️⃣ **Development** - Weekly progress updates | 4️⃣ **Launch** - Deploy & train | 5️⃣ **Support** - 30-day free maintenance!',
                'Here\'s how we work: 📋 First, we chat about your goals | 📝 Then we send a detailed proposal | 💻 Development with weekly check-ins | 🚀 Launch with training | 🛠️ 30 days free support. Ready to start?'
            ]
        },
        support: {
            patterns: ['support', 'maintenance', 'help', 'bug', 'fix', 'issue', 'problem', 'error', 'after', 'warranty'],
            responses: [
                'All our projects include 30 days of free support after launch! 🛠️ We also offer affordable maintenance packages (₱2k-5k/month) for ongoing updates, security patches, and feature additions.',
                'We don\'t disappear after launch! 😊 30 days free bug fixes included. Need ongoing help? Our maintenance plans cover updates, security, and new features. Your success is our success!'
            ]
        },
        location: {
            patterns: ['where', 'location', 'address', 'office', 'place', 'bulacan', 'philippines', 'meet', 'visit'],
            responses: [
                'We\'re based in **Bulacan, Philippines**! 🇵🇭 While we work primarily remote, we can arrange video calls or meetups for local clients. Distance is never a barrier to great work!',
                'CAZTech is proudly built in Bulacan, Philippines! 🌏 We work with clients worldwide remotely. For local projects, we\'re happy to meet up. Where are you located?'
            ]
        },
        thanks: {
            patterns: ['thanks', 'thank you', 'thx', 'ty', 'appreciate', 'grateful', 'helpful'],
            responses: [
                'You\'re very welcome! 😊 Happy I could help! Is there anything else you\'d like to know about CAZTech?',
                'Anytime! It\'s my pleasure to assist. Don\'t hesitate to reach out if you have more questions!'
            ]
        },
        bye: {
            patterns: ['bye', 'goodbye', 'see you', 'cya', 'later', 'exit', 'close'],
            responses: [
                'Thanks for chatting with CAZTech! 🚀 Feel free to come back anytime. Have a great day!',
                'Goodbye! Looking forward to working with you. Fill out our contact form when you\'re ready to start! 👋'
            ]
        }
    };

    function getSmartResponse(userMessage) {
        const lowerMsg = userMessage.toLowerCase();
        chatHistory.push({ role: 'user', message: userMessage });

        // Check for matches
        for (const category in smartResponses) {
            const data = smartResponses[category];
            if (data.patterns.some(pattern => lowerMsg.includes(pattern))) {
                // Pick random response from category
                const response = data.responses[Math.floor(Math.random() * data.responses.length)];
                chatHistory.push({ role: 'assistant', message: response });
                return response;
            }
        }

        // Context-aware fallback responses
        if (chatHistory.length > 2) {
            const followUps = [
                'That\'s interesting! Tell me more about what you\'re looking for. Are you interested in web development, business systems, or a mobile app?',
                'Got it! To give you the best answer, could you share a bit more about your project? What kind of solution do you need?',
                'I want to make sure I help you properly! 💡 Would you like me to connect you with our team via the contact form for a detailed discussion?'
            ];
            return followUps[Math.floor(Math.random() * followUps.length)];
        }

        // Default response
        const defaults = [
            'Thanks for reaching out! 🎯 I can help with questions about our services, pricing, timeline, or tech stack. What would you like to know?',
            'I\'m here to help! 💬 Try asking about: our services, portfolio, pricing, tech stack, or how to get started. What interests you?',
            'Hello! 👋 I can answer questions about CAZTech\'s web development, business systems, mobile apps, or connect you with our team. What do you need help with?'
        ];
        return defaults[Math.floor(Math.random() * defaults.length)];
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'flex gap-3';
        typingDiv.innerHTML = `
            <div class="w-8 h-8 rounded-full bg-white flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm overflow-hidden border">
                <img src="image/CAZTECH.png" alt="C" class="w-full h-full object-contain p-1">
            </div>
            <div class="bg-card border px-4 py-3 rounded-2xl rounded-tl-sm shadow-sm">
                <div class="flex gap-1">
                    <span class="w-2 h-2 bg-muted-foreground/50 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-2 h-2 bg-muted-foreground/50 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-2 h-2 bg-muted-foreground/50 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>
        `;
        aiBody?.appendChild(typingDiv);
        aiBody?.scrollTo({ top: aiBody.scrollHeight, behavior: 'smooth' });
        return typingDiv;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        indicator?.remove();
    }

    function showAIResponse(message, delay = 0) {
        setTimeout(() => {
            removeTypingIndicator();
            const responseDiv = document.createElement('div');
            responseDiv.className = 'flex gap-3';
            responseDiv.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-white flex-shrink-0 flex items-center justify-center mt-0.5 shadow-sm overflow-hidden border">
                    <img src="image/CAZTECH.png" alt="C" class="w-full h-full object-contain p-1">
                </div>
                <div class="bg-card border p-3 rounded-2xl rounded-tl-sm text-sm text-foreground shadow-sm leading-relaxed max-w-[85%]">
                    ${message}
                </div>
            `;
            aiBody?.appendChild(responseDiv);
            aiBody?.scrollTo({ top: aiBody.scrollHeight, behavior: 'smooth' });
        }, delay);
    }

    // Quick action buttons with smart responses
    const quickActions = {
        'View Portfolio Projects': () => { 
            showAIResponse('Great choice! 🎨 Let me show you our projects section. One moment...', 500);
            setTimeout(() => { window.location.href = '#projects'; toggleChat(false); }, 1500);
        },
        'What is your tech stack?': () => {
            showTypingIndicator();
            const response = smartResponses.techstack.responses[0];
            showAIResponse(response, 1500);
        },
        'Contact information': () => {
            showTypingIndicator();
            const response = smartResponses.contact.responses[0];
            showAIResponse(response, 1000);
            setTimeout(() => { window.location.href = '#contact'; }, 2500);
        }
    };

    document.querySelectorAll('#ai-chat-window .flex-col.gap-2 button').forEach(btn => {
        btn.addEventListener('click', () => {
            const text = btn.querySelector('span')?.textContent || '';
            if (quickActions[text]) {
                // Hide quick actions after click
                btn.parentElement.style.display = 'none';
                quickActions[text]();
            }
        });
    });

    function sendMessage() {
        const text = aiInput?.value.trim();
        if (!text || isTyping) return;

        // Show user message
        const userDiv = document.createElement('div');
        userDiv.className = 'flex gap-3 justify-end';
        userDiv.innerHTML = `
            <div class="bg-primary text-primary-foreground p-3 rounded-2xl rounded-tr-sm text-sm shadow-sm leading-relaxed max-w-[80%]">
                ${text}
            </div>
        `;
        aiBody?.appendChild(userDiv);
        aiInput.value = '';
        aiBody?.scrollTo({ top: aiBody.scrollHeight, behavior: 'smooth' });

        // Get intelligent response
        const response = getSmartResponse(text);
        
        // Show typing indicator then response
        isTyping = true;
        showTypingIndicator();
        const typingTime = Math.min(1000 + response.length * 20, 2500); // 1-2.5s based on length
        showAIResponse(response, typingTime);
        
        setTimeout(() => {
            isTyping = false;
        }, typingTime);
    }

    aiSend?.addEventListener('click', sendMessage);
    aiInput?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    // ── 6. Star Rating (Contact Form) ──────────────────────────────────────
    const stars = document.querySelectorAll('.star-rating svg');
    stars.forEach((star, idx) => {
        star.addEventListener('click', () => {
            stars.forEach((s, i) => {
                s.classList.toggle('text-yellow-400', i <= idx);
                s.classList.toggle('text-gray-200',   i > idx);
            });
            const ratingInput = document.getElementById('rating-value');
            if (ratingInput) ratingInput.value = idx + 1;
        });
        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => s.classList.toggle('scale-110', i <= idx));
        });
        star.addEventListener('mouseleave', () => {
            stars.forEach(s => s.classList.remove('scale-110'));
        });
    });

    // ── 7. Review Form Star Rating ─────────────────────────────────────────
    const reviewStars = document.querySelectorAll('.review-star-rating svg');
    reviewStars.forEach((star, idx) => {
        star.addEventListener('click', () => {
            reviewStars.forEach((s, i) => {
                s.classList.toggle('text-yellow-400', i <= idx);
                s.classList.toggle('text-gray-200',   i > idx);
            });
            const ratingInput = document.getElementById('review-rating-value');
            if (ratingInput) ratingInput.value = idx + 1;
        });
        star.addEventListener('mouseenter', () => {
            reviewStars.forEach((s, i) => s.classList.toggle('scale-110', i <= idx));
        });
        star.addEventListener('mouseleave', () => {
            reviewStars.forEach(s => s.classList.remove('scale-110'));
        });
    });

    // ── 8. Review Success Auto-Dismiss ─────────────────────────────────────
    const successMsg = document.getElementById('review-success');
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.transition = 'opacity 0.5s ease';
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 500);
        }, 5000);
    }
});


