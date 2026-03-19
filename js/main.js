/**
 * NephSpace Elite Construction - Main JavaScript
 * Building Excellence, Defining Spaces
 */

(function() {
    'use strict';

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

    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        // Update active nav link based on scroll position
        updateActiveNavLink();
    });

    // ===== Smooth Scrolling for Navigation Links =====
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');

            if (href.startsWith('#')) {
                e.preventDefault();
                const targetId = href.substring(1);
                const targetSection = document.getElementById(targetId);

                if (targetSection) {
                    const offsetTop = targetSection.offsetTop - 80;
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse.classList.contains('show')) {
                        navbarCollapse.classList.remove('show');
                    }
                }
            }
        });
    });

    // ===== Update Active Navigation Link =====
    function updateActiveNavLink() {
        const sections = document.querySelectorAll('section[id]');
        const scrollPosition = window.scrollY + 150;

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute('id');

            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${sectionId}`) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }

    // ===== Hero Slider =====
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroIndicatorsContainer = document.querySelector('.hero-indicators');
    const heroPrevBtn = document.querySelector('.hero-prev');
    const heroNextBtn = document.querySelector('.hero-next');
    let currentSlide = 0;
    let slideInterval;

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

    // ===== Testimonials Slider =====
    const testimonialItems = document.querySelectorAll('.testimonial-item');
    let currentTestimonial = 0;

    function showTestimonial(index) {
        testimonialItems.forEach(item => item.classList.remove('active'));
        testimonialItems[index].classList.add('active');
    }

    function nextTestimonial() {
        currentTestimonial = (currentTestimonial + 1) % testimonialItems.length;
        showTestimonial(currentTestimonial);
    }

    // Auto-rotate testimonials
    if (testimonialItems.length > 0) {
        setInterval(nextTestimonial, 6000);
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

        if (navbarCollapse && navbarCollapse.classList.contains('show')) {
            if (!navbarCollapse.contains(e.target) && !navbarToggler.contains(e.target)) {
                navbarCollapse.classList.remove('show');
            }
        }
    });

})();
