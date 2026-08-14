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

    // ?? Project Filtering & Showcase Rendering ?????????????????????????????
    const projectFilters      = document.getElementById('project-filters');
    const projectCount        = document.getElementById('project-count');
    const projectFilterStatus = document.getElementById('project-filter-status');
    const projectsViewport    = document.getElementById('projects-viewport');
    const projectsPrev        = document.getElementById('projects-prev');
    const projectsNext        = document.getElementById('projects-next');
    const projectsDots        = document.getElementById('projects-dots');
    let allProjects           = [];
    let activeProjectFilter   = 'all';
    let projectCarouselIndex  = 0;

    const fallbackProjectIcon = '<svg class="h-10 w-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[character]));
    }

    function projectCategory(value) {
        return String(value ?? '').trim() || 'Uncategorized';
    }

    function projectCategoryKey(value) {
        return projectCategory(value).toLowerCase();
    }

    function safeClassList(value, fallback = 'bg-secondary') {
        const classes = String(value ?? '')
            .trim()
            .split(/\s+/)
            .filter(token => /^[a-zA-Z0-9_:/\.\-%\[\]]+$/.test(token))
            .slice(0, 6);
        return classes.length ? classes.join(' ') : fallback;
    }

    function safeAssetUrl(value) {
        const raw = String(value ?? '').trim();
        return raw && !/^(?:javascript|data|vbscript):/i.test(raw) ? raw : '';
    }

    function safeProjectUrl(value) {
        const raw = String(value ?? '').trim();
        if (!raw || /^(?:javascript|data|vbscript):/i.test(raw)) return '';
        return /^(?:https?:\/\/|\/|\.\/|\.\.\/|#)/i.test(raw) ? raw : '';
    }

    function safeIconSvg(value) {
        const raw = String(value ?? '').trim();
        if (!raw || !/^<svg[\s>]/i.test(raw) || /<script|on[a-z]+\s*=|javascript:/i.test(raw)) return '';
        return raw;
    }

    function projectCarouselVisibleCount() {
        if (window.innerWidth >= 1024) return 3;
        if (window.innerWidth >= 640) return 2;
        return 1;
    }

    function renderProjectDots(maxIndex) {
        if (!projectsDots) return;
        const pageCount = maxIndex >= 0 ? maxIndex + 1 : 0;
        projectsDots.innerHTML = Array.from({ length: pageCount }, (_, index) =>
            `<button type="button" data-carousel-index="${index}" class="h-2 rounded-full bg-muted-foreground/30 transition-all duration-300" aria-label="Go to project group ${index + 1}"></button>`
        ).join('');
        projectsDots.querySelectorAll('button').forEach(dot => {
            dot.addEventListener('click', () => setProjectCarouselIndex(Number(dot.dataset.carouselIndex)));
        });
    }

    function getProjectCarouselStep() {
        const firstCard = projectsContainer?.querySelector('.project-card');
        if (!firstCard) return 0;
        const styles = getComputedStyle(projectsContainer);
        const gap = parseFloat(styles.columnGap || styles.gap) || 0;
        return firstCard.getBoundingClientRect().width + gap;
    }

    function updateProjectCarousel(rebuildDots = false) {
        if (!projectsContainer) return;
        const cards = [...projectsContainer.querySelectorAll('.project-card')];
        if (!cards.length) {
            projectsContainer.style.transform = 'translate3d(0, 0, 0)';
            if (projectsPrev) projectsPrev.disabled = true;
            if (projectsNext) projectsNext.disabled = true;
            if (rebuildDots) renderProjectDots(-1);
            return;
        }

        const visibleCount = projectCarouselVisibleCount();
        const maxIndex = Math.max(0, cards.length - visibleCount);
        projectCarouselIndex = Math.max(0, Math.min(projectCarouselIndex, maxIndex));
        const step = getProjectCarouselStep();
        const offset = projectCarouselIndex * step;
        projectsContainer.style.transform = `translate3d(-${offset}px, 0, 0)`;

        if (rebuildDots || !projectsDots || projectsDots.children.length !== maxIndex + 1) {
            renderProjectDots(maxIndex);
        }
        projectsPrev?.toggleAttribute('disabled', projectCarouselIndex <= 0);
        projectsNext?.toggleAttribute('disabled', projectCarouselIndex >= maxIndex);
        projectsPrev?.setAttribute('aria-disabled', String(projectCarouselIndex <= 0));
        projectsNext?.setAttribute('aria-disabled', String(projectCarouselIndex >= maxIndex));
        projectsDots?.querySelectorAll('button').forEach(dot => {
            const active = Number(dot.dataset.carouselIndex) === projectCarouselIndex;
            dot.classList.toggle('w-6', active);
            dot.classList.toggle('w-2', !active);
            dot.classList.toggle('bg-primary', active);
            dot.classList.toggle('bg-muted-foreground/30', !active);
        });
    }

    function setProjectCarouselIndex(index) {
        const cards = projectsContainer ? [...projectsContainer.querySelectorAll('.project-card')] : [];
        const maxIndex = Math.max(0, cards.length - projectCarouselVisibleCount());
        projectCarouselIndex = Math.max(0, Math.min(index, maxIndex));
        updateProjectCarousel();
    }

    function emptyProjectsMarkup(title, message, isError = false) {
        const icon = isError
            ? '<svg class="h-7 w-7 text-destructive" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m0 3.75h.008M10.29 3.86 1.82 18a2 2 0 001.72 3h16.92a2 2 0 001.72-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>'
            : '<svg class="h-7 w-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7.5A2.5 2.5 0 016.5 5h11A2.5 2.5 0 0120 7.5v9a2.5 2.5 0 01-2.5 2.5h-11A2.5 2.5 0 014 16.5v-9zM8 9h8m-8 3h5"/></svg>';
        const titleClass = isError ? 'text-destructive' : 'text-foreground';
        return `<div class="w-full shrink-0 rounded-3xl border border-dashed border-border/80 bg-card/50 px-6 py-14 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">${icon}</div>
            <h3 class="mt-5 text-lg font-bold ${titleClass}">${escapeHtml(title)}</h3>
            <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-muted-foreground">${escapeHtml(message)}</p>
        </div>`;
    }

    function setFilterButtonState(button, isActive) {
        const count = button.querySelector('.filter-count');
        button.setAttribute('aria-pressed', String(isActive));
        button.classList.toggle('border-primary', isActive);
        button.classList.toggle('bg-primary', isActive);
        button.classList.toggle('text-primary-foreground', isActive);
        button.classList.toggle('shadow-sm', isActive);
        button.classList.toggle('border-border/70', !isActive);
        button.classList.toggle('bg-background/70', !isActive);
        button.classList.toggle('text-foreground', !isActive);
        button.classList.toggle('hover:bg-accent', !isActive);
        button.classList.toggle('hover:text-accent-foreground', !isActive);
        count?.classList.toggle('bg-primary-foreground/15', isActive);
        count?.classList.toggle('text-primary-foreground', isActive);
        count?.classList.toggle('bg-muted', !isActive);
        count?.classList.toggle('text-muted-foreground', !isActive);
    }

    function updateProjectStatus(visibleProjects = allProjects) {
        const total = allProjects.length;
        const visible = visibleProjects.length;
        if (projectCount) {
            projectCount.textContent = total === 0
                ? 'No projects yet'
                : `${total} ${total === 1 ? 'project' : 'projects'} available`;
        }
        if (projectFilterStatus) {
            projectFilterStatus.textContent = activeProjectFilter === 'all'
                ? `Showing all ${visible} ${visible === 1 ? 'project' : 'projects'}`
                : `Showing ${visible} ${visible === 1 ? 'project' : 'projects'} in this category`;
        }
    }

    function applyProjectFilter(filterKey) {
        activeProjectFilter = filterKey;
        const visibleProjects = filterKey === 'all'
            ? allProjects
            : allProjects.filter(project => projectCategoryKey(project.category) === filterKey);
        projectFilters?.querySelectorAll('.filter-btn').forEach(button => {
            setFilterButtonState(button, button.dataset.filter === activeProjectFilter);
        });
        renderProjects(visibleProjects);
        updateProjectStatus(visibleProjects);
    }

    function renderProjectFilters(projects) {
        if (!projectFilters) return;
        const categoryMap = new Map();
        projects.forEach(project => {
            const label = projectCategory(project.category);
            const key = projectCategoryKey(label);
            if (!categoryMap.has(key)) categoryMap.set(key, { label, count: 0 });
            categoryMap.get(key).count += 1;
        });

        projectFilters.innerHTML = '';
        const allButton = document.createElement('button');
        allButton.type = 'button';
        allButton.dataset.filter = 'all';
        allButton.className = 'filter-btn inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring';
        allButton.innerHTML = `<span>All projects</span><span class="filter-count rounded-full px-2 py-0.5 text-xs">${projects.length}</span>`;
        allButton.addEventListener('click', () => applyProjectFilter('all'));
        projectFilters.appendChild(allButton);

        [...categoryMap.entries()]
            .sort((a, b) => a[1].label.localeCompare(b[1].label))
            .forEach(([key, category]) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.dataset.filter = key;
                button.className = 'filter-btn inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring';
                button.innerHTML = `<span>${escapeHtml(category.label)}</span><span class="filter-count rounded-full px-2 py-0.5 text-xs">${category.count}</span>`;
                button.addEventListener('click', () => applyProjectFilter(key));
                projectFilters.appendChild(button);
                setFilterButtonState(button, false);
            });

        setFilterButtonState(allButton, true);
    }

    function renderProjects(projects) {
        if (!projectsContainer) return;
        projectsContainer.setAttribute('aria-busy', 'false');
        if (!projects.length) {
            projectsContainer.innerHTML = emptyProjectsMarkup(
                activeProjectFilter === 'all' ? 'Projects are on the way' : 'No projects in this category',
                activeProjectFilter === 'all' ? 'Our latest work will appear here soon.' : 'Try another category to explore more CAZTech work.'
            );
            projectCarouselIndex = 0;
            updateProjectCarousel(true);
            return;
        }

        projectsContainer.innerHTML = projects.map((project, index) => {
            const title = String(project.title ?? '').trim() || 'Untitled project';
            const category = projectCategory(project.category);
            const description = String(project.description ?? '').trim() || 'A tailored digital solution built by CAZTech.';
            const imageUrl = safeAssetUrl(project.icon_image);
            const hasImage = Boolean(imageUrl);
            const isFeatured = index === 0;
            const bgClass = safeClassList(project.bg_class);
            const iconSvg = safeIconSvg(project.icon_svg) || fallbackProjectIcon;
            const projectUrl = safeProjectUrl(project.project_url);
            const cardWidth = 'w-full shrink-0 sm:w-[calc(50%_-_0.625rem)] lg:w-[calc(33.333%_-_0.833rem)]';
            const visualLayout = 'flex-col';
            const visualWidth = 'min-h-[12rem]';
            const logoSize = 'h-20 w-20 sm:h-24 sm:w-24';
            const iconContent = hasImage
                ? `<img src="${escapeHtml(imageUrl)}" alt="${escapeHtml(title)} logo" class="h-full w-full object-contain">`
                : iconSvg;
            const logoPreview = hasImage
                ? `<button type="button" data-img="${escapeHtml(imageUrl)}" data-title="${escapeHtml(title)}" data-category="${escapeHtml(category)}" class="project-logo-btn ${logoSize} cursor-zoom-in rounded-[2rem] border border-border/60 bg-background/70 p-5 shadow-xl backdrop-blur-sm transition-transform duration-300 hover:scale-105 hover:ring-2 hover:ring-primary/50 focus:outline-none focus:ring-2 focus:ring-primary/70" aria-label="Preview ${escapeHtml(title)} logo">${iconContent}</button>`
                : `<div class="${logoSize} flex items-center justify-center rounded-[2rem] border border-border/60 bg-background/70 p-5 shadow-xl backdrop-blur-sm">${iconContent}</div>`;
            const linkLabel = projectUrl ? 'View live project' : 'Start a project';
            const linkHref = projectUrl || '#contact';
            const linkTarget = projectUrl ? ' target="_blank" rel="noopener noreferrer"' : '';

            return `<article class="${cardWidth} project-card group relative overflow-hidden rounded-3xl border border-border/80 bg-card/80 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-2xl hover:shadow-primary/10" data-category="${escapeHtml(projectCategoryKey(category))}">
                <div class="${visualLayout} flex h-full">
                    <div class="${visualWidth} relative overflow-hidden ${bgClass} p-6 sm:p-8">
                        <div class="absolute -right-16 -top-16 h-40 w-40 rounded-full bg-primary/10 blur-3xl"></div>
                        <div class="relative z-10 flex h-full flex-col justify-between gap-8">
                            <div class="flex items-center justify-between gap-3">
                                <span class="inline-flex items-center rounded-full border border-border/60 bg-background/60 px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.16em] text-foreground/70 backdrop-blur-sm">${isFeatured ? 'Featured project' : 'Project showcase'}</span>
                                ${hasImage ? '<span class="text-xs text-muted-foreground">Click logo to preview</span>' : ''}
                            </div>
                            <div class="flex items-end justify-between gap-4">
                                ${logoPreview}
                                <span class="text-6xl font-black tracking-tighter text-foreground/10">${String(index + 1).padStart(2, '0')}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex min-w-0 flex-1 flex-col p-6 sm:p-8">
                        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                            <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-primary">${escapeHtml(category)}</span>
                            <span class="text-xs font-medium text-muted-foreground">CAZTech build</span>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight">${escapeHtml(title)}</h3>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-muted-foreground">${escapeHtml(description)}</p>
                        <div class="mt-auto flex flex-wrap items-center justify-between gap-4 border-t border-border/70 pt-6">
                            <span class="inline-flex items-center gap-2 text-xs font-medium text-muted-foreground"><svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>Built for impact</span>
                            <a href="${escapeHtml(linkHref)}"${linkTarget} class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition-all hover:gap-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2">${linkLabel}<svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14m-6-6 6 6-6 6"/></svg></a>
                        </div>
                    </div>
                </div>
            </article>`;
        }).join('');

        projectsContainer.querySelectorAll('.project-logo-btn').forEach(button => {
            button.addEventListener('click', () => {
                openLightbox(button.dataset.img, button.dataset.title, button.dataset.category);
            });
        });
        projectCarouselIndex = 0;
        updateProjectCarousel(true);
    }

    projectsPrev?.addEventListener('click', () => setProjectCarouselIndex(projectCarouselIndex - 1));
    projectsNext?.addEventListener('click', () => setProjectCarouselIndex(projectCarouselIndex + 1));

    let carouselPointerActive = false;
    let carouselPointerId = null;
    let carouselPointerStartX = 0;
    let carouselPointerDeltaX = 0;
    let carouselPointerMoved = false;
    let carouselSuppressClick = false;

    function finishCarouselPointer(event, cancelled = false) {
        if (!carouselPointerActive || (event && event.pointerId !== carouselPointerId)) return;
        if (!cancelled && event) carouselPointerDeltaX = event.clientX - carouselPointerStartX;
        const shouldMove = !cancelled && Math.abs(carouselPointerDeltaX) >= 50;
        const direction = carouselPointerDeltaX < 0 ? 1 : -1;
        const pointerId = carouselPointerId;
        carouselPointerActive = false;
        carouselPointerId = null;
        projectsViewport?.classList.remove('cursor-grabbing', 'select-none');
        if (projectsContainer) projectsContainer.style.transition = '';
        if (event && projectsViewport?.hasPointerCapture(pointerId)) {
            projectsViewport.releasePointerCapture(pointerId);
        }
        if (carouselPointerMoved) {
            carouselSuppressClick = true;
        }
        if (shouldMove) {
            setProjectCarouselIndex(projectCarouselIndex + direction);
        } else {
            updateProjectCarousel();
        }
        if (carouselSuppressClick) {
            window.setTimeout(() => { carouselSuppressClick = false; }, 0);
        }
    }

    projectsViewport?.addEventListener('pointerdown', event => {
        if (event.pointerType === 'mouse' && event.button !== 0) return;
        if (!projectsContainer?.querySelector('.project-card')) return;
        carouselPointerActive = true;
        carouselPointerId = event.pointerId;
        carouselPointerStartX = event.clientX;
        carouselPointerDeltaX = 0;
        carouselPointerMoved = false;
        projectsViewport.classList.add('cursor-grabbing', 'select-none');
        projectsContainer.style.transition = 'none';
    });

    projectsViewport?.addEventListener('pointermove', event => {
        if (!carouselPointerActive || event.pointerId !== carouselPointerId) return;
        carouselPointerDeltaX = event.clientX - carouselPointerStartX;
        if (Math.abs(carouselPointerDeltaX) > 6) {
            if (!carouselPointerMoved) {
                carouselPointerMoved = true;
                projectsViewport.setPointerCapture?.(event.pointerId);
            }
            event.preventDefault();
        }
        const step = getProjectCarouselStep();
        if (!step) return;
        const cards = [...projectsContainer.querySelectorAll('.project-card')];
        const maxIndex = Math.max(0, cards.length - projectCarouselVisibleCount());
        const atEdge = (projectCarouselIndex <= 0 && carouselPointerDeltaX > 0)
            || (projectCarouselIndex >= maxIndex && carouselPointerDeltaX < 0);
        const adjustedDelta = atEdge ? carouselPointerDeltaX * 0.35 : carouselPointerDeltaX;
        const offset = (projectCarouselIndex * step) - adjustedDelta;
        projectsContainer.style.transform = `translate3d(-${offset}px, 0, 0)`;
    });

    projectsViewport?.addEventListener('pointerup', event => finishCarouselPointer(event));
    projectsViewport?.addEventListener('pointercancel', event => finishCarouselPointer(event, true));
    projectsViewport?.addEventListener('click', event => {
        if (!carouselSuppressClick) return;
        event.preventDefault();
        event.stopPropagation();
        carouselSuppressClick = false;
    }, true);
    window.addEventListener('resize', () => updateProjectCarousel());

    if (projectsContainer) {
        fetch('api/get_projects.php', { headers: { Accept: 'application/json' } })
            .then(response => {
                if (!response.ok) throw new Error(`Projects request failed with ${response.status}`);
                return response.json();
            })
            .then(projects => {
                allProjects = Array.isArray(projects) ? projects : [];
                renderProjectFilters(allProjects);
                renderProjects(allProjects);
                updateProjectStatus(allProjects);
            })
            .catch(error => {
                console.error('Error fetching projects:', error);
                if (projectFilters) projectFilters.innerHTML = '';
                if (projectsContainer) {
                    projectsContainer.setAttribute('aria-busy', 'false');
                    projectsContainer.innerHTML = emptyProjectsMarkup('Projects are temporarily unavailable', 'Please refresh the page or check back shortly.', true);
                }
                if (projectCount) projectCount.textContent = 'Unable to load projects';
                if (projectFilterStatus) projectFilterStatus.textContent = 'Please try again later';
                updateProjectCarousel(true);
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
                'We\'d love to hear from you! 📩<br><strong>Email:</strong> <a href="mailto:contact@caztechsolutions.works" class="text-primary underline underline-offset-2 hover:opacity-80">contact@caztechsolutions.works</a><br><strong>Official Gmail:</strong> <a href="mailto:caztechsolutions.works@gmail.com" class="text-primary underline underline-offset-2 hover:opacity-80">caztechsolutions.works@gmail.com</a><br><strong>Christian\'s Gmail:</strong> <a href="mailto:Santoschristian50.works@gmail.com" class="text-primary underline underline-offset-2 hover:opacity-80">Santoschristian50.works@gmail.com</a><br><strong>Official Facebook:</strong> <a href="https://www.facebook.com/caztechsolutions.works" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">@caztechsolutions.works</a><br><strong>Team Facebook:</strong> <a href="https://www.facebook.com/kramzssis" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">Christian</a> · <a href="https://www.facebook.com/anntricia.feliciano" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">Anntricia</a> · <a href="https://www.facebook.com/ctrl.zild" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">Zildjan</a><br>Or fill out the <a href="#contact" class="text-primary underline underline-offset-2 hover:opacity-80">contact form</a> below. We typically reply within <strong>24 hours</strong>! 📬',
                'Contact CAZTech through our <a href="mailto:contact@caztechsolutions.works" class="text-primary underline underline-offset-2 hover:opacity-80">official email</a> or <strong>official Gmail:</strong> <a href="mailto:caztechsolutions.works@gmail.com" class="text-primary underline underline-offset-2 hover:opacity-80">caztechsolutions.works@gmail.com</a>.<br><strong>Christian\'s Gmail:</strong> <a href="mailto:Santoschristian50.works@gmail.com" class="text-primary underline underline-offset-2 hover:opacity-80">Santoschristian50.works@gmail.com</a><br><strong>Official Facebook:</strong> <a href="https://www.facebook.com/caztechsolutions.works" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">official Facebook page</a>.<br>You may also reach <a href="https://www.facebook.com/kramzssis" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">Christian</a>, <a href="https://www.facebook.com/anntricia.feliciano" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">Anntricia</a>, or <a href="https://www.facebook.com/ctrl.zild" target="_blank" rel="noopener noreferrer" class="text-primary underline underline-offset-2 hover:opacity-80">Zildjan</a>.<br>We are based in Bulacan, Philippines. 🇵🇭 You can also use the <a href="#contact" class="text-primary underline underline-offset-2 hover:opacity-80">contact form</a>; expect a reply within <strong>24 hours</strong>!'
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


