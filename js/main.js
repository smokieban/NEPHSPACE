/**
 * NephSpace Elite Construction - Main JavaScript
 * Building Excellence, Defining Spaces
 */

(function() {
    'use strict';

    const navbarSocialLinks = [
        { label: 'Instagram', iconClass: 'fab fa-instagram', href: '#' },
        { label: 'LinkedIn', iconClass: 'fab fa-linkedin-in', href: '#' },
        { label: 'Twitter', iconClass: 'fab fa-twitter', href: '#' },
        { label: 'Facebook', iconClass: 'fab fa-facebook-f', href: '#' }
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
                brand.setAttribute('aria-label', 'NephSpace Elite Construction');
                brand.setAttribute('title', 'NephSpace Elite Construction');
            }

            if (!container || !collapse || !navList) {
                return;
            }

            container.classList.add('navbar-layout');
            collapse.classList.add('navbar-menu-shell');
            navList.classList.remove('ms-auto');
            navList.classList.add('navbar-menu-list');

            let socialWrapper = navbarElement.querySelector('.navbar-social');
            if (!socialWrapper) {
                socialWrapper = document.createElement('div');
                socialWrapper.className = 'navbar-social d-none d-lg-flex';
                socialWrapper.setAttribute('aria-label', 'Social media links');
                socialWrapper.innerHTML = getNavbarSocialMarkup();
                container.appendChild(socialWrapper);
            }
        });
    }

    function getNavbarSocialMarkup() {
        return navbarSocialLinks.map(link => {
            const isPlaceholder = link.href === '#';
            return `
                <a href="${link.href}" class="navbar-social-link" aria-label="Visit our ${link.label} page" title="Visit our ${link.label} page"${isPlaceholder ? ' data-placeholder-link="true"' : ' target="_blank" rel="noopener noreferrer"'}>
                    <i class="${link.iconClass}"></i>
                </a>
            `;
        }).join('');
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
                            <h4 class="footer-title">NephSpace Elite Construction</h4>
                            <p class="footer-tagline">Building Excellence, Defining Spaces</p>
                            <p>We deliver architecture, design-build, quantity surveying, materials supply, and sourcing support for residential, commercial, and interior projects across Kenya.</p>
                            <div class="footer-social">
                                <a href="https://wa.me/254700903141?text=Hello%20NephSpace%20Elite%20Construction%2C%20I%20would%20like%20to%20discuss%20a%20project." target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp" title="Chat with us on WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                <a href="mailto:nephspaceconstruction1@gmail.com" aria-label="Send us an email" title="Send us an email"><i class="fas fa-envelope"></i></a>
                                <a href="tel:+254700903141" aria-label="Call us" title="Call us"><i class="fas fa-phone"></i></a>
                                <a href="https://www.google.com/maps?q=PRM6%2B22W%2C%20Mogotio%20Rd%2C%20Nairobi" target="_blank" rel="noopener noreferrer" aria-label="Open our office in Google Maps" title="Open our office in Google Maps"><i class="fas fa-map-marker-alt"></i></a>
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
                                <li><a href="index.html#contact">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-widget-title">Our Services</h5>
                            <ul class="footer-links">
                                <li><a href="architecture.html">Architecture</a></li>
                                <li><a href="quantity-surveying.html">Quantity Surveying</a></li>
                                <li><a href="design-build.html">Design and Build</a></li>
                                <li><a href="materials.html">Construction Materials</a></li>
                                <li><a href="sourcing.html">International Sourcing</a></li>
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
                        <p class="copyright">&copy; ${currentYear} NephSpace Elite Construction and Interiors Hub Ltd. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <ul class="footer-bottom-links">
                            <li><a href="about.html">About</a></li>
                            <li><a href="projects.html">Projects</a></li>
                            <li><a href="index.html#contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </div>`;
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

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            projectItems.forEach(item => {
                const itemCategories = item.getAttribute('data-category');

                if (filterValue === 'all') {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    // Check if the item's categories include the filter value
                    if (itemCategories && itemCategories.includes(filterValue)) {
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
                }
            });
        });
    });

    // Set initial project item styles
    projectItems.forEach(item => {
        item.style.transition = 'all 0.3s ease';
    });

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
            testimonialsSlider.innerHTML = '<div class="testimonial-empty-state">Be the first to share your experience with NephSpace Elite Construction.</div>';
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

    // ===== Lightbox Configuration =====
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Project %1 of %2'
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

        if (e.target.closest('.navbar-social-link[data-placeholder-link="true"]')) {
            e.preventDefault();
        }

        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
            if (!navbarCollapse.contains(e.target) && !navbarToggler.contains(e.target)) {
                navbarCollapse.classList.remove('show');
            }
        }
    });

})();
