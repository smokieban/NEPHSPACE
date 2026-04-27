/**
 * Nephspace Elite Construction and Interiors Hub Ltd - Main JavaScript
 * Building Excellence, Defining Spaces
 */

(function() {
    'use strict';

    const COMPANY_NAME = 'Nephspace Elite Construction';
    const COMPANY_NAME_ENCODED = 'Nephspace%20Elite%20Construction%20and%20Interiors%20Hub%20Ltd';

    const navbarContactDetails = [
        { label: 'Call us', iconClass: 'fas fa-phone-alt', href: 'tel:+254700903141', value: '+254700903141' },
        { label: 'Email us', iconClass: 'fas fa-envelope', href: 'mailto:nephspaceconstruction1@gmail.com', value: 'nephspaceconstruction1@gmail.com' }
    ];

    const serviceDropdownLinks = [
        { page: 'services.html', iconClass: 'fas fa-layer-group', href: 'services.html', label: 'All Services' },
        { page: 'pre-feed.html', iconClass: 'fas fa-lightbulb', href: 'pre-feed.html', label: 'Preliminary Front-End Engineering and Design (Pre-FEED)' },
        { page: 'feed.html', iconClass: 'fas fa-sitemap', href: 'feed.html', label: 'Front-End Engineering and Design (FEED)' },
        { page: 'pre-construction.html', iconClass: 'fas fa-clipboard-list', href: 'pre-construction.html', label: 'Pre-Construction & Early Contractor Engagement' },
        { page: 'construction-services.html', iconClass: 'fas fa-hard-hat', href: 'construction-services.html', label: 'Construction Services' },
        { page: 'procurement-services.html', iconClass: 'fas fa-truck', href: 'procurement-services.html', label: 'Procurement Services' },
        { page: 'construction-management.html', iconClass: 'fas fa-diagram-project', href: 'construction-management.html', label: 'Construction Management Services' },
        { page: 'facilities-maintenance.html', iconClass: 'fas fa-screwdriver-wrench', href: 'facilities-maintenance.html', label: 'Facilities Maintenance' }
    ];

    const insightsDropdownLinks = [
        { page: 'articles.html', iconClass: 'fas fa-newspaper', href: 'articles.html', label: 'Articles' },
        { page: 'testimonials.html', iconClass: 'fas fa-comments', href: 'testimonials.html', label: 'Testimonials' }
    ];

    const serviceContextPages = new Set([
        'services.html',
        'pre-feed.html',
        'feed.html',
        'pre-construction.html',
        'construction-services.html',
        'procurement-services.html',
        'construction-management.html',
        'facilities-maintenance.html',
        'architecture.html',
        'quantity-surveying.html',
        'design-build.html',
        'materials.html',
        'sourcing.html'
    ]);

    const expertiseAreas = [
        { filter: 'industrial', iconClass: 'fas fa-industry', href: 'projects.html?category=industrial#projects', label: 'Industrial' },
        { filter: 'technology', iconClass: 'fas fa-microchip', href: 'projects.html?category=technology#projects', label: 'Technology' },
        { filter: 'interior-fit-outs', iconClass: 'fas fa-couch', href: 'projects.html?category=interior-fit-outs#projects', label: 'Interior Fit Outs' },
        { filter: 'commercial', iconClass: 'fas fa-building', href: 'projects.html?category=commercial#projects', label: 'Commercial' },
        { filter: 'residential', iconClass: 'fas fa-home', href: 'projects.html?category=residential#projects', label: 'Residential' },
        { filter: 'institutions', iconClass: 'fas fa-landmark', href: 'projects.html?category=institutions#projects', label: 'Institutions' },
        { filter: 'healthcare', iconClass: 'fas fa-heart-pulse', href: 'projects.html?category=healthcare#projects', label: 'Healthcare' },
        { filter: 'hospitality', iconClass: 'fas fa-hotel', href: 'projects.html?category=hospitality#projects', label: 'Hospitality' }
    ];

    const projectsDropdownLinks = [
        { filter: 'all', iconClass: 'fas fa-briefcase', href: 'projects.html#projects', label: 'All Projects' },
        ...expertiseAreas
    ];

    // ===== Initialize AOS Animation =====
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });

    // ===== Navbar Scroll Effect =====
    const navbar = document.getElementById('mainNav');
    const navLinks = document.querySelectorAll('.nav-link');
    const hashNavLinks = Array.from(navLinks).filter(link => {
        const href = link.getAttribute('href') || '';
        return href.startsWith('#');
    });

    normalizeBranding();
    renderSharedFooter();
    normalizeDocumentCopy();
    window.addEventListener('load', normalizeDocumentCopy);

    window.addEventListener('scroll', function() {
        if (navbar && window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else if (navbar) {
            navbar.classList.remove('scrolled');
        }

        // Update active nav link based on scroll position
        if (hashNavLinks.length > 0) {
            updateActiveNavLink();
        }
    });

    // ===== Smooth Scrolling for Navigation Links =====
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href') || '';

            if (href.startsWith('#') && href.length > 1) {
                const targetId = href.substring(1);
                const targetSection = document.getElementById(targetId);

                if (targetSection) {
                    e.preventDefault();
                    const offsetTop = targetSection.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse && navbarCollapse.classList.contains('show')) {
                        navbarCollapse.classList.remove('show');
                    }
                }
            }
        });
    });

    // ===== Update Active Navigation Link =====
    function updateActiveNavLink() {
        if (hashNavLinks.length === 0) {
            return;
        }

        const sections = document.querySelectorAll('section[id]');
        const scrollPosition = window.scrollY + 150;

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');

            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                hashNavLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }

    function normalizeBranding() {
        document.querySelectorAll('.brand-copy').forEach(element => element.remove());

        document.querySelectorAll('.navbar').forEach(navbarElement => {
            const container = navbarElement.querySelector('.container');
            const brand = navbarElement.querySelector('.navbar-brand');
            const collapse = navbarElement.querySelector('.navbar-collapse');
            const navList = navbarElement.querySelector('.navbar-nav');

            if (brand) {
                brand.setAttribute('aria-label', COMPANY_NAME);
                brand.setAttribute('title', COMPANY_NAME);
                brand.setAttribute('href', 'index.html');

                const brandLogo = brand.querySelector('.brand-logo');
                if (brandLogo) {
                    brandLogo.setAttribute('alt', `${COMPANY_NAME} logo`);
                }

                brand.insertAdjacentHTML('beforeend', `
                    <span class="brand-copy">
                        <span class="brand-name">Nephspace Elite Construction</span>
                        <span class="brand-tagline">and Interiors Hub Ltd</span>
                    </span>
                `);
            }

            if (!container || !collapse || !navList) {
                return;
            }

            container.classList.add('navbar-layout');
            collapse.classList.add('navbar-menu-shell');
            navList.classList.remove('ms-auto');
            navList.classList.add('navbar-menu-list');

            removeHomeNavItem(navList);
            normalizeServicesDropdown(navList);
            normalizeProjectsDropdown(navList);
            normalizeInsightsDropdown(navList);
            normalizeContactLink(navList);

            navbarElement.querySelectorAll('.navbar-social').forEach(element => element.remove());

            let contactWrapper = collapse.querySelector('.navbar-meta');
            if (!contactWrapper) {
                contactWrapper = document.createElement('div');
                contactWrapper.className = 'navbar-meta';
                collapse.appendChild(contactWrapper);
            }

            collapse.insertBefore(contactWrapper, navList);
            contactWrapper.innerHTML = getNavbarContactMarkup();
        });

        document.querySelectorAll('.floating-whatsapp').forEach(link => {
            link.setAttribute('aria-label', `Chat with ${COMPANY_NAME} on WhatsApp`);
            link.setAttribute('title', `Chat with ${COMPANY_NAME} on WhatsApp`);

            const href = link.getAttribute('href') || '';
            link.setAttribute('href', href.replace('NephSpace%20Elite%20Construction', COMPANY_NAME_ENCODED));
        });

        setupDesktopDropdowns();
    }

    function getNavbarContactMarkup() {
        return navbarContactDetails.map(link => {
            return `
                <a href="${link.href}" class="navbar-contact-link" aria-label="${link.label}" title="${link.value}">
                    <i class="${link.iconClass}"></i>
                    <span>${link.value}</span>
                </a>
            `;
        }).join('');
    }

    function removeHomeNavItem(navList) {
        const homeNavItem = Array.from(navList.children).find(item => {
            const link = item.querySelector(':scope > .nav-link');
            return link && link.textContent.trim().toLowerCase() === 'home';
        });

        if (homeNavItem) {
            homeNavItem.remove();
        }
    }

    function findTopLevelNavItem(navList, label, requireDropdown = false) {
        return Array.from(navList.children).find(item => {
            const selector = requireDropdown ? ':scope > .nav-link.dropdown-toggle' : ':scope > .nav-link';
            const link = item.querySelector(selector);
            return link && link.textContent.trim().toLowerCase() === label;
        });
    }

    function normalizeServicesDropdown(navList) {
        const servicesNavItem = findTopLevelNavItem(navList, 'services', true);

        if (!servicesNavItem) {
            return;
        }

        const toggle = servicesNavItem.querySelector(':scope > .nav-link.dropdown-toggle');
        const menu = servicesNavItem.querySelector(':scope > .dropdown-menu');

        if (!toggle || !menu) {
            return;
        }

        toggle.textContent = 'Services';
        toggle.setAttribute('href', 'services.html');
        toggle.classList.toggle('active', isServicesContextPage());
        menu.innerHTML = getServicesDropdownMarkup();
    }

    function getServicesDropdownMarkup() {
        const currentPage = getCurrentPage();

        return serviceDropdownLinks.map((link, index) => {
            const activeClass = link.page === currentPage ? ' active' : '';
            const dividerMarkup = index === 0 ? '<li><hr class="dropdown-divider"></li>' : '';

            return `
                <li>
                    <a class="dropdown-item${activeClass}" href="${link.href}">
                        <i class="${link.iconClass} me-2"></i>${link.label}
                    </a>
                </li>
                ${dividerMarkup}
            `;
        }).join('');
    }

    function normalizeProjectsDropdown(navList) {
        const projectsNavItem = findTopLevelNavItem(navList, 'projects', true);

        if (!projectsNavItem) {
            return;
        }

        const toggle = projectsNavItem.querySelector(':scope > .nav-link.dropdown-toggle');
        const menu = projectsNavItem.querySelector(':scope > .dropdown-menu');

        if (!toggle || !menu) {
            return;
        }

        toggle.textContent = 'Projects';
        toggle.setAttribute('href', 'projects.html');
        toggle.classList.toggle('active', isProjectsContextPage());
        menu.innerHTML = getProjectsDropdownMarkup();
    }

    function normalizeInsightsDropdown(navList) {
        let insightsNavItem = findTopLevelNavItem(navList, 'insights', true);

        if (!insightsNavItem) {
            insightsNavItem = document.createElement('li');
            insightsNavItem.className = 'nav-item dropdown';
            insightsNavItem.innerHTML = `
                <a class="nav-link dropdown-toggle" href="#" id="insightsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Insights
                </a>
                <ul class="dropdown-menu" aria-labelledby="insightsDropdown"></ul>
            `;

            const contactNavItem = findTopLevelNavItem(navList, 'contact');
            if (contactNavItem) {
                navList.insertBefore(insightsNavItem, contactNavItem);
            } else {
                navList.appendChild(insightsNavItem);
            }
        }

        const toggle = insightsNavItem.querySelector(':scope > .nav-link.dropdown-toggle');
        const menu = insightsNavItem.querySelector(':scope > .dropdown-menu');

        if (!toggle || !menu) {
            return;
        }

        toggle.textContent = 'Insights';
        toggle.setAttribute('href', '#');
        toggle.classList.toggle('active', isInsightsContextPage());
        menu.innerHTML = getInsightsDropdownMarkup();
    }

    function getInsightsDropdownMarkup() {
        const currentPage = getCurrentPage();

        return insightsDropdownLinks.map(link => `
            <li>
                <a class="dropdown-item${link.page === currentPage ? ' active' : ''}" href="${link.href}">
                    <i class="${link.iconClass} me-2"></i>${link.label}
                </a>
            </li>
        `).join('');
    }

    function normalizeContactLink(navList) {
        let contactNavItem = findTopLevelNavItem(navList, 'contact');

        if (!contactNavItem) {
            contactNavItem = document.createElement('li');
            contactNavItem.className = 'nav-item';
            contactNavItem.innerHTML = '<a class="nav-link" href="contact.html">Contact</a>';
            navList.appendChild(contactNavItem);
        }

        const contactLink = contactNavItem.querySelector(':scope > .nav-link');
        if (!contactLink) {
            return;
        }

        contactLink.textContent = 'Contact';
        contactLink.setAttribute('href', 'contact.html');
        contactLink.classList.toggle('active', getCurrentPage() === 'contact.html');
    }

    function getProjectsDropdownMarkup() {
        const currentPage = getCurrentPage();
        const currentFilter = getCurrentProjectFilter();
        const isProjectsPage = currentPage === 'projects.html';

        return projectsDropdownLinks.map(link => {
            const isActive = isProjectsContextPage() && ((isProjectsPage && currentFilter === link.filter) || (!isProjectsPage && link.filter === 'all'));

            return `
                <li>
                    <a class="dropdown-item${isActive ? ' active' : ''}" href="${link.href}">
                        <i class="${link.iconClass} me-2"></i>${link.label}
                    </a>
                </li>
            `;
        }).join('');
    }

    function getCurrentPage() {
        return window.location.pathname.split('/').pop() || 'index.html';
    }

    function getCurrentProjectFilter() {
        const params = new URLSearchParams(window.location.search);
        return params.get('category') || 'all';
    }

    function isServicesContextPage() {
        return serviceContextPages.has(getCurrentPage());
    }

    function isProjectsContextPage() {
        const currentPage = getCurrentPage();
        return currentPage === 'projects.html'
            || currentPage === 'project-detail.html'
            || currentPage === 'ongoing-projects.html'
            || currentPage.startsWith('projects-');
    }

    function isInsightsContextPage() {
        const currentPage = getCurrentPage();
        return currentPage === 'articles.html' || currentPage === 'testimonials.html';
    }

    function closeNavbarDropdownSelection(sourceLink) {
        const dropdownMenu = sourceLink ? sourceLink.closest('.dropdown-menu') : null;
        const dropdownItem = dropdownMenu ? dropdownMenu.closest('.nav-item.dropdown') : null;
        const dropdownToggle = dropdownItem ? dropdownItem.querySelector(':scope > .nav-link.dropdown-toggle') : null;
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const navbarToggler = document.querySelector('.navbar-toggler');

        if (dropdownToggle && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            bootstrap.Dropdown.getOrCreateInstance(dropdownToggle).hide();
        } else {
            if (dropdownMenu) {
                dropdownMenu.classList.remove('show');
            }

            if (dropdownItem) {
                dropdownItem.classList.remove('show');
            }

            if (dropdownToggle) {
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
        }

        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
                bootstrap.Collapse.getOrCreateInstance(navbarCollapse, { toggle: false }).hide();
            } else {
                navbarCollapse.classList.remove('show');
            }
        }

        if (navbarToggler) {
            navbarToggler.setAttribute('aria-expanded', 'false');
        }

        if (sourceLink) {
            sourceLink.blur();
        }

        if (dropdownToggle) {
            dropdownToggle.blur();
        }
    }

    function setupDesktopDropdowns() {
        document.querySelectorAll('.navbar .nav-item.dropdown').forEach(dropdown => {
            if (dropdown.dataset.hoverReady === 'true') {
                return;
            }

            const toggle = dropdown.querySelector(':scope > .nav-link.dropdown-toggle');
            const menu = dropdown.querySelector(':scope > .dropdown-menu');

            if (!toggle || !menu) {
                return;
            }

            const openDropdown = () => {
                if (window.innerWidth < 992) {
                    return;
                }

                dropdown.classList.add('show');
                menu.classList.add('show');
                toggle.setAttribute('aria-expanded', 'true');
            };

            const closeDropdown = () => {
                dropdown.classList.remove('show');
                menu.classList.remove('show');
                toggle.setAttribute('aria-expanded', 'false');
            };

            dropdown.addEventListener('mouseenter', openDropdown);
            dropdown.addEventListener('mouseleave', closeDropdown);
            toggle.addEventListener('click', event => {
                if (window.innerWidth >= 992) {
                    event.preventDefault();
                    closeDropdown();
                }
            });
            menu.addEventListener('click', event => {
                const dropdownLink = event.target.closest('.dropdown-item');

                if (!dropdownLink) {
                    return;
                }

                closeNavbarDropdownSelection(dropdownLink);
            });

            dropdown.dataset.hoverReady = 'true';
        });
    }

    function renderSharedFooter() {
        const footer = document.querySelector('.footer');
        if (!footer) {
            return;
        }

        const currentYear = new Date().getFullYear();
        footer.innerHTML = `
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-widget">
                            <h4 class="footer-title">${COMPANY_NAME}</h4>
                            <p class="footer-tagline">Building Excellence, Defining Spaces</p>
                            <p>We deliver end to end solutions across Pre FEED, FEED, pre construction, construction, procurement, construction management, and facilities maintenance.</p>
                            <div class="footer-social">
                                <a href="https://www.instagram.com/nephspaceelite_?igsh=bDBhYmhhb200Nmxz" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Instagram" title="Follow us on Instagram"><i class="fab fa-instagram"></i></a>
                                <a href="https://www.facebook.com/share/1DtiCUxpNU/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Facebook" title="Follow us on Facebook"><i class="fab fa-facebook-f"></i></a>
                                <span class="footer-social-placeholder" role="img" aria-label="LinkedIn link coming soon" title="LinkedIn link coming soon"><i class="fab fa-linkedin-in"></i></span>
                                <span class="footer-social-placeholder" role="img" aria-label="Twitter link coming soon" title="Twitter link coming soon"><i class="fab fa-twitter"></i></span>
                                <a href="https://www.tiktok.com/@nephspaceconstruction?_r=1&_t=ZS-94q9AA4xno0" target="_blank" rel="noopener noreferrer" aria-label="Follow us on TikTok" title="Follow us on TikTok"><i class="fab fa-tiktok"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-widget-title">Quick Links</h5>
                            <ul class="footer-links">
                                <li><a href="index.html">Home</a></li>
                                <li><a href="about.html">About Us</a></li>
                                <li><a href="services.html">Services</a></li>
                                <li><a href="projects.html">Projects</a></li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-widget-title">Our Services</h5>
                            <ul class="footer-links">
                                ${getFooterServicesMarkup()}
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-widget-title">Contact Info</h5>
                            <ul class="footer-links">
                                <li><a href="https://www.google.com/maps?q=PRM6%2B22W%2C%20Mogotio%20Rd%2C%20Nairobi" class="footer-location-link" target="_blank" rel="noopener noreferrer"><i class="fas fa-map-marker-alt"></i>Royal Offices Mogotio Road</a></li>
                                <li><a href="tel:+254700903141"><i class="fas fa-phone"></i>+254700903141</a></li>
                                <li><a href="mailto:nephspaceconstruction1@gmail.com"><i class="fas fa-envelope"></i>nephspaceconstruction1@gmail.com</a></li>
                                <li><span><i class="fas fa-clock"></i>Mon-Fri 8am-5pm, Sat 8am-12pm</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="footer-divider">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="copyright">&copy; ${currentYear} ${COMPANY_NAME}. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <ul class="footer-bottom-links">
                            <li><a href="about.html">About</a></li>
                            <li><a href="articles.html">Articles</a></li>
                            <li><a href="testimonials.html">Testimonials</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>`;
    }

    function getCanonicalUrl() {
        const currentPage = getCurrentPage();
        const path = currentPage === 'index.html' ? '/' : `/${currentPage}`;

        if (currentPage === 'project-detail.html') {
            const params = new URLSearchParams(window.location.search);
            const project = params.get('project');
            const query = project ? `?project=${encodeURIComponent(project)}` : '';
            return `https://nephspaceelite.com${path}${query}`;
        }

        const query = '';
        return `https://nephspaceelite.com${path}${query}`;
    }

    function replaceKnownCopy(value) {
        if (typeof value !== 'string' || value.length === 0) {
            return value;
        }

        let normalized = value
            .replace(/NephSpace Elite Construction(?: &amp; Interiors Hub Limited| & Interiors Hub Limited| &amp; Interiors Hub| & Interiors Hub)/g, COMPANY_NAME)
            .replace(/Nephspace Elite Construction(?: &amp; Interiors Hub Limited| & Interiors Hub Limited| &amp; Interiors Hub| & Interiors Hub)/g, COMPANY_NAME)
            .replace(/NEPHSPACE Elite Construction(?! and Interiors Hub Ltd)(?! &amp; Interiors Hub(?: Limited)?)(?! & Interiors Hub(?: Limited)?)/g, COMPANY_NAME)
            .replace(/NephSpace Elite Construction(?! and Interiors Hub Ltd)(?! &amp; Interiors Hub(?: Limited)?)(?! & Interiors Hub(?: Limited)?)/g, COMPANY_NAME)
            .replace(/Nephspace Elite Construction(?! and Interiors Hub Ltd)(?! &amp; Interiors Hub(?: Limited)?)(?! & Interiors Hub(?: Limited)?)/g, COMPANY_NAME);

        const replacements = [
            ['https://nephspace.co.ke', 'https://nephspaceelite.com'],
            ['nephspace.co.ke', 'nephspaceelite.com'],
            ['NephSpace Elite Construction and Interiors Hub Ltd', COMPANY_NAME],
            ['NephSpace Elite Construction & Interiors Hub Limited', COMPANY_NAME],
            ['NephSpace Elite Construction & Interiors Hub', COMPANY_NAME],
            ['Nephspace Elite Construction & Interiors Hub Limited', COMPANY_NAME],
            ['Nephspace Elite Construction & Interiors Hub', COMPANY_NAME],
            ['End-to-End', 'End to End'],
            ['end-to-end', 'end to end'],
            ['Design-Build', 'Design Build'],
            ['design-build', 'design build'],
            ['Fit-Outs', 'Fit Outs'],
            ['fit-outs', 'fit outs'],
            ['Fit-Out', 'Fit Out'],
            ['fit-out', 'fit out'],
            ['Fitout', 'Fit Out'],
            ['fitout', 'fit out'],
            ['World-Class', 'World Class'],
            ['world-class', 'world class'],
            ['Long-Term', 'Long Term'],
            ['long-term', 'long term'],
            ['High-Quality', 'High Quality'],
            ['high-quality', 'high quality'],
            ['High-Value', 'High Value'],
            ['high-value', 'high value'],
            ['High-Definition', 'High Definition'],
            ['high-definition', 'high definition'],
            ['Mixed-Use', 'Mixed Use'],
            ['mixed-use', 'mixed use'],
            ['Client-Focused', 'Client Focused'],
            ['client-focused', 'client focused'],
            ['Client-Facing', 'Client Facing'],
            ['client-facing', 'client facing'],
            ['Customer-Facing', 'Customer Facing'],
            ['customer-facing', 'customer facing'],
            ['Customer-Friendly', 'Customer Friendly'],
            ['customer-friendly', 'customer friendly'],
            ['Expert-Led', 'Expert Led'],
            ['expert-led', 'expert led'],
            ['Healthcare-Oriented', 'Healthcare Oriented'],
            ['healthcare-oriented', 'healthcare oriented'],
            ['Member-Oriented', 'Member Oriented'],
            ['member-oriented', 'member oriented'],
            ['Institutional-Grade', 'Institutional Grade'],
            ['institutional-grade', 'institutional grade'],
            ['Risk-Aware', 'Risk Aware'],
            ['risk-aware', 'risk aware'],
            ['Decision-Ready', 'Decision Ready'],
            ['decision-ready', 'decision ready'],
            ['Decision-Making', 'Decision Making'],
            ['decision-making', 'decision making'],
            ['Early-Stage', 'Early Stage'],
            ['early-stage', 'early stage'],
            ['Cost-Efficient', 'Cost Efficient'],
            ['cost-efficient', 'cost efficient'],
            ['Cost-Conscious', 'Cost Conscious'],
            ['cost-conscious', 'cost conscious'],
            ['Hand-in-Hand', 'Hand in Hand'],
            ['hand-in-hand', 'hand in hand'],
            ['On-Time', 'On Time'],
            ['on-time', 'on time'],
            ['On-Budget', 'On Budget'],
            ['on-budget', 'on budget'],
            ['Well-Positioned', 'Well Positioned'],
            ['well-positioned', 'well positioned'],
            ['Well-Informed', 'Well Informed'],
            ['well-informed', 'well informed'],
            ['Well-Structured', 'Well Structured'],
            ['well-structured', 'well structured'],
            ['Future-Ready', 'Future Ready'],
            ['future-ready', 'future ready'],
            ['Rapid-Response', 'Rapid Response'],
            ['rapid-response', 'rapid response'],
            ['Street-Facing', 'Street Facing'],
            ['street-facing', 'street facing'],
            ['Back-of-House', 'Back of House'],
            ['back-of-house', 'back of house'],
            ['Site-Ready', 'Site Ready'],
            ['site-ready', 'site ready'],
            ['Low-Maintenance', 'Low Maintenance'],
            ['low-maintenance', 'low maintenance'],
            ['Day-to-Day', 'Day to Day'],
            ['day-to-day', 'day to day'],
            ['Performance-Driven', 'Performance Driven'],
            ['performance-driven', 'performance driven'],
            ['Post-Construction', 'Post Construction'],
            ['post-construction', 'post construction'],
            ['Post-Installation', 'Post Installation'],
            ['post-installation', 'post installation'],
            ['Quality-Led', 'Quality Led'],
            ['quality-led', 'quality led'],
            ['Top-Rated', 'Top Rated'],
            ['top-rated', 'top rated'],
            ['In-House', 'In House'],
            ['in-house', 'in house'],
            ['White-Glove', 'White Glove'],
            ['white-glove', 'white glove'],
            ['Game-Changer', 'Game Changer'],
            ['game-changer', 'game changer'],
            ['Escrow-Style', 'Escrow Style'],
            ['escrow-style', 'escrow style'],
            ['Zero-Harm', 'Zero Harm'],
            ['zero-harm', 'zero harm'],
            ['Audit-Ready', 'Audit Ready'],
            ['audit-ready', 'audit ready'],
            ['Compliance-Conscious', 'Compliance Conscious'],
            ['compliance-conscious', 'compliance conscious'],
            ['Technical-Economic', 'Technical and Economic'],
            ['technical-economic', 'technical and economic'],
            ['High-Level', 'High Level'],
            ['high-level', 'high level'],
            ['Grade-A', 'Grade A'],
            ['grade-a', 'grade a'],
            ['3-Pillar', '3 Pillar'],
            ['3-pillar', '3 pillar'],
            ['Multi-Storey', 'Multi Storey'],
            ['multi-storey', 'multi storey'],
            ['Multi-Story', 'Multi Story'],
            ['multi-story', 'multi story'],
            ['Single-Storey', 'Single Storey'],
            ['single-storey', 'single storey'],
            ['High-Rise', 'High Rise'],
            ['high-rise', 'high rise'],
            ['Energy-Efficient', 'Energy Efficient'],
            ['energy-efficient', 'energy efficient'],
            ['Open-Plan', 'Open Plan'],
            ['open-plan', 'open plan'],
            ['Garden-Facing', 'Garden Facing'],
            ['garden-facing', 'garden facing'],
            ['Built-Right', 'Built Right'],
            ['built-right', 'built right'],
            ['Millimeter-Level', 'Millimeter Level'],
            ['millimeter-level', 'millimeter level'],
            ['OSHA-Aligned', 'OSHA Aligned'],
            ['OSHA-aligned', 'OSHA aligned']
        ];

        replacements.forEach(([from, to]) => {
            normalized = normalized.split(from).join(to);
        });

        return normalized;
    }

    function normalizeDocumentCopy() {
        document.title = replaceKnownCopy(document.title);

        const canonicalUrl = getCanonicalUrl();
        const canonicalLink = document.querySelector('link[rel="canonical"]');
        if (canonicalLink) {
            canonicalLink.setAttribute('href', canonicalUrl);
        }

        document.querySelectorAll('meta[content]').forEach(meta => {
            const property = meta.getAttribute('property') || meta.getAttribute('name') || '';
            let value = meta.getAttribute('content') || '';

            if (property === 'og:url') {
                value = canonicalUrl;
            } else {
                value = replaceKnownCopy(value);
            }

            meta.setAttribute('content', value);
        });

        document.querySelectorAll('script[type="application/ld+json"]').forEach(script => {
            script.textContent = replaceKnownCopy(script.textContent);
        });

        document.querySelectorAll('[title], [alt], [aria-label], [placeholder]').forEach(element => {
            ['title', 'alt', 'aria-label', 'placeholder'].forEach(attributeName => {
                if (!element.hasAttribute(attributeName)) {
                    return;
                }

                element.setAttribute(attributeName, replaceKnownCopy(element.getAttribute(attributeName)));
            });
        });

        document.querySelectorAll('[href], [src]').forEach(element => {
            ['href', 'src'].forEach(attributeName => {
                if (!element.hasAttribute(attributeName)) {
                    return;
                }

                const currentValue = element.getAttribute(attributeName) || '';
                if (currentValue.includes('nephspace.co.ke')) {
                    element.setAttribute(attributeName, currentValue.split('nephspace.co.ke').join('nephspaceelite.com'));
                }
            });
        });

        if (document.body) {
            const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
                acceptNode(node) {
                    const parentTag = node.parentElement?.tagName;
                    if (!node.nodeValue || !node.nodeValue.trim()) {
                        return NodeFilter.FILTER_REJECT;
                    }

                    if (parentTag && ['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEXTAREA'].includes(parentTag)) {
                        return NodeFilter.FILTER_REJECT;
                    }

                    return NodeFilter.FILTER_ACCEPT;
                }
            });

            const textNodes = [];
            let currentNode = walker.nextNode();
            while (currentNode) {
                textNodes.push(currentNode);
                currentNode = walker.nextNode();
            }

            textNodes.forEach(node => {
                node.nodeValue = replaceKnownCopy(node.nodeValue);
            });
        }
    }

    function getFooterServicesMarkup() {
        return serviceDropdownLinks.slice(1).map(link => `<li><a href="${link.href}">${link.label}</a></li>`).join('');
    }

    // ===== Hero Slider =====
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroIndicatorsContainer = document.querySelector('.hero-indicators');
    const heroPrevBtn = document.querySelector('.hero-prev');
    const heroNextBtn = document.querySelector('.hero-next');
    let currentSlide = 0;
    let slideInterval;

    if (heroSlides.length > 0 && heroIndicatorsContainer) {
        // Create indicators
        heroSlides.forEach((slide, index) => {
            const indicator = document.createElement('div');
            indicator.classList.add('hero-indicator');
            if (index === 0) indicator.classList.add('active');
            indicator.addEventListener('click', () => goToSlide(index));
            heroIndicatorsContainer.appendChild(indicator);
        });

        const heroIndicators = document.querySelectorAll('.hero-indicator');

        function showSlide(index) {
            heroSlides.forEach(slide => slide.classList.remove('active'));
            heroIndicators.forEach(indicator => indicator.classList.remove('active'));

            heroSlides[index].classList.add('active');
            heroIndicators[index].classList.add('active');
            currentSlide = index;
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % heroSlides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + heroSlides.length) % heroSlides.length;
            showSlide(currentSlide);
        }

        function goToSlide(index) {
            showSlide(index);
            resetSlideInterval();
        }

        function resetSlideInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Auto-play slider
        slideInterval = setInterval(nextSlide, 5000);

        // Navigation buttons
        if (heroNextBtn) heroNextBtn.addEventListener('click', () => {
            nextSlide();
            resetSlideInterval();
        });

        if (heroPrevBtn) heroPrevBtn.addEventListener('click', () => {
            prevSlide();
            resetSlideInterval();
        });

        // Pause on hover
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.addEventListener('mouseenter', () => clearInterval(slideInterval));
            heroSection.addEventListener('mouseleave', () => resetSlideInterval());
        }
    }

    // ===== Counter Animation =====
    const statNumbers = document.querySelectorAll('.stat-number');
    let counterAnimated = false;

    function animateCounters() {
        if (counterAnimated) return;

        const statsSection = document.querySelector('.stats-section');
        if (!statsSection) return;

        const statsSectionTop = statsSection.offsetTop;
        const statsSectionHeight = statsSection.offsetHeight;
        const scrollPosition = window.scrollY + window.innerHeight;

        if (scrollPosition > statsSectionTop && window.scrollY < statsSectionTop + statsSectionHeight) {
            counterAnimated = true;

            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-target'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        stat.textContent = Math.floor(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        stat.textContent = target;
                    }
                };

                updateCounter();
            });
        }
    }

    window.addEventListener('scroll', animateCounters);

    // ===== Project Filter =====
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectItems = document.querySelectorAll('.project-item');

    function applyProjectFilter(filterValue) {
        filterBtns.forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-filter') === filterValue);
        });

        projectItems.forEach(item => {
            const itemCategories = item.getAttribute('data-category');

            if (filterValue === 'all') {
                item.style.display = 'block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 10);
            } else if (itemCategories && itemCategories.includes(filterValue)) {
                item.style.display = 'block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'scale(1)';
                }, 10);
            } else {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            applyProjectFilter(this.getAttribute('data-filter') || 'all');
        });
    });

    // Set initial project item styles
    projectItems.forEach(item => {
        item.style.transition = 'all 0.3s ease';
    });

    const requestedProjectCategory = getCurrentProjectFilter();
    if (requestedProjectCategory !== 'all') {
        const matchingFilter = Array.from(filterBtns).find(btn => btn.getAttribute('data-filter') === requestedProjectCategory);
        if (matchingFilter) {
            applyProjectFilter(requestedProjectCategory);
        }
    }

    // ===== Testimonials =====
    const testimonialsSlider = document.getElementById('testimonialsSlider');
    const testimonialForm = document.getElementById('testimonialForm');
    const testimonialFormMessage = document.getElementById('testimonialFormMessage');
    const testimonialModalElement = document.getElementById('testimonialModal');
    const testimonialModal = testimonialModalElement && typeof bootstrap !== 'undefined'
        ? bootstrap.Modal.getOrCreateInstance(testimonialModalElement)
        : null;
    let testimonialItems = [];
    let currentTestimonial = 0;
    let testimonialInterval;

    if (testimonialsSlider) {
        loadTestimonials();
    }

    if (testimonialForm) {
        testimonialForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(testimonialForm);
            const submitBtn = testimonialForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('testimonials.php', {
                    method: 'POST',
                    body: formData
                });
                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Unable to submit your review right now.');
                }

                testimonialFormMessage.className = 'mt-3 success';
                testimonialFormMessage.textContent = payload.message || 'Thank you for sharing your experience.';
                testimonialForm.reset();
                await loadTestimonials();

                if (testimonialModal) {
                    setTimeout(() => {
                        testimonialModal.hide();
                    }, 1200);
                }
            } catch (error) {
                testimonialFormMessage.className = 'mt-3 error';
                testimonialFormMessage.textContent = error.message || 'Unable to submit your review right now.';
            } finally {
                testimonialFormMessage.style.display = 'block';
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    }

    if (testimonialModalElement) {
        testimonialModalElement.addEventListener('hidden.bs.modal', function() {
            if (testimonialForm) {
                testimonialForm.reset();
            }

            if (testimonialFormMessage) {
                testimonialFormMessage.className = 'mt-3';
                testimonialFormMessage.textContent = '';
                testimonialFormMessage.style.display = 'none';
            }
        });
    }

    async function loadTestimonials() {
        try {
            const response = await fetch('testimonials.php', {
                headers: {
                    'Accept': 'application/json'
                }
            });
            const payload = await response.json();

            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Unable to load reviews at the moment.');
            }

            renderTestimonials(Array.isArray(payload.testimonials) ? payload.testimonials : []);
        } catch (error) {
            if (testimonialsSlider) {
                testimonialsSlider.innerHTML = `<div class="testimonial-empty-state">${escapeHtml(error.message || 'Unable to load reviews at the moment.')}</div>`;
            }
        }
    }

    function renderTestimonials(testimonials) {
        if (!testimonialsSlider) {
            return;
        }

        clearInterval(testimonialInterval);

        if (testimonials.length === 0) {
            testimonialsSlider.innerHTML = `<div class="testimonial-empty-state">Be the first to share your experience with ${COMPANY_NAME}.</div>`;
            testimonialItems = [];
            return;
        }

        testimonialsSlider.innerHTML = testimonials.map((testimonial, index) => `
            <div class="testimonial-item${index === 0 ? ' active' : ''}">
                <div class="testimonial-content">
                    <div class="testimonial-rating" aria-label="Rated ${Number(testimonial.rating) || 5} out of 5">
                        ${renderRatingStars(Number(testimonial.rating) || 5)}
                    </div>
                    <i class="fas fa-quote-left"></i>
                    <p>"${escapeHtml(testimonial.message || '')}"</p>
                    <div class="testimonial-author">
                        <img src="assets/img/profile-placeholder.svg" alt="Client portrait placeholder">
                        <div>
                            <h5>${escapeHtml(testimonial.name || 'Anonymous Client')}</h5>
                            <span>${escapeHtml(testimonial.role || 'Client')}</span>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

        testimonialItems = Array.from(testimonialsSlider.querySelectorAll('.testimonial-item'));
        currentTestimonial = 0;

        if (testimonialItems.length > 1) {
            testimonialInterval = setInterval(nextTestimonial, 6000);
        }
    }

    function showTestimonial(index) {
        if (testimonialItems.length === 0) {
            return;
        }

        testimonialItems.forEach(item => item.classList.remove('active'));
        testimonialItems[index].classList.add('active');
    }

    function nextTestimonial() {
        if (testimonialItems.length === 0) {
            return;
        }

        currentTestimonial = (currentTestimonial + 1) % testimonialItems.length;
        showTestimonial(currentTestimonial);
    }

    function renderRatingStars(rating) {
        return Array.from({ length: 5 }, (_, index) => (
            `<i class="${index < rating ? 'fas' : 'far'} fa-star"></i>`
        )).join('');
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // ===== Contact Form Handling =====
    const contactForm = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(contactForm);

            // Show loading state
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';
            submitBtn.disabled = true;
            formMessage.style.display = 'block';

            // Send form data via AJAX
            fetch('contact.php', {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                return { ok: response.ok, data };
            })
            .then(data => {
                if (data.ok && data.data.success) {
                    formMessage.className = 'mt-3 success';
                    formMessage.textContent = data.data.message || 'Thank you! Your message has been sent successfully.';
                    contactForm.reset();
                } else {
                    formMessage.className = 'mt-3 error';
                    formMessage.textContent = data.data.message || 'Sorry, there was an error sending your message. Please try again.';
                }
            })
            .catch(() => {
                formMessage.className = 'mt-3 error';
                formMessage.textContent = 'Sorry, there was an error sending your message. Please try again.';
            })
            .finally(() => {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;

                // Hide message after 5 seconds
                setTimeout(() => {
                    formMessage.style.display = 'none';
                }, 5000);
            });
        });
    }

    // ===== Scroll to Top Button =====
    const scrollTopBtn = document.getElementById('scrollTop');

    window.addEventListener('scroll', function() {
        if (!scrollTopBtn) {
            return;
        }

        if (window.scrollY > 300) {
            scrollTopBtn.classList.add('show');
        } else {
            scrollTopBtn.classList.remove('show');
        }
    });

    if (scrollTopBtn) {
        scrollTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ===== Newsletter Form =====
    const newsletterForm = document.querySelector('.newsletter-form');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;

            // Here you would typically send this to your backend
            alert('Thank you for subscribing! We will keep you updated.');
            this.reset();
        });
    }

    // ===== Preloader (Optional) =====
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
    });

    // ===== Mobile Menu Close on Outside Click =====
    document.addEventListener('click', function(e) {
        const navbarCollapse = document.querySelector('.navbar-collapse');
        const navbarToggler = document.querySelector('.navbar-toggler');

        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
            if (!navbarCollapse.contains(e.target) && !navbarToggler.contains(e.target)) {
                navbarCollapse.classList.remove('show');
            }
        }
    });

})();
