// ═══════════════════════════════════════════════════════════════════════
// SWAYAM - MAIN JAVASCRIPT
// Pure vanilla JavaScript for PWA and interactions
// ═══════════════════════════════════════════════════════════════════════

// Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('✅ Service Worker registered:', registration.scope);
            })
            .catch(error => {
                console.log('❌ Service Worker registration failed:', error);
            });
    });
}

// ═══════════════════════════════════════════════════════════════════════
// NAVIGATION
// ═══════════════════════════════════════════════════════════════════════

const nav = document.getElementById('navigation');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

// Scroll effect
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        nav.classList.add('nav-scrolled');
        nav.classList.remove('nav-transparent');
    } else {
        nav.classList.add('nav-transparent');
        nav.classList.remove('nav-scrolled');
    }
});

// Mobile menu toggle
if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenuBtn.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking a link
    const mobileLinks = mobileMenu.querySelectorAll('.mobile-link');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenuBtn.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    });
}

// ═══════════════════════════════════════════════════════════════════════
// LANGUAGE SWITCHER
// ═══════════════════════════════════════════════════════════════════════

let currentLang = 'EN';
const languages = {
    'EN': 'English',
    'HI': 'हिंदी',
    'MR': 'मराठी'
};

const langBtn = document.getElementById('langBtn');
const langBtnMobile = document.getElementById('langBtnMobile');
const langOptions = document.getElementById('langOptions');
const langOptionsMobile = document.getElementById('langOptionsMobile');

function updateLanguageDisplay() {
    if (langBtn) langBtn.textContent = currentLang + ' ▼';
    if (langBtnMobile) langBtnMobile.textContent = `Language: ${currentLang} ▼`;
    
    // Update active class
    document.querySelectorAll('.lang-options li').forEach(li => {
        li.classList.toggle('active', li.dataset.lang === currentLang);
    });
    
    // Store preference
    localStorage.setItem('swayam-language', currentLang);
    
    console.log('Language switched to:', currentLang);
}

function toggleDropdown(options) {
    options.classList.toggle('active');
}

function selectLanguage(lang) {
    currentLang = lang;
    updateLanguageDisplay();
    // Close dropdowns
    if (langOptions) langOptions.classList.remove('active');
    if (langOptionsMobile) langOptionsMobile.classList.remove('active');
}

if (langBtn) {
    langBtn.addEventListener('click', (e) => {
        e.preventDefault();
        toggleDropdown(langOptions);
    });
}

if (langBtnMobile) {
    langBtnMobile.addEventListener('click', (e) => {
        e.preventDefault();
        toggleDropdown(langOptionsMobile);
    });
}

if (langOptions) {
    langOptions.addEventListener('click', (e) => {
        if (e.target.tagName === 'LI') {
            selectLanguage(e.target.dataset.lang);
        }
    });
}

if (langOptionsMobile) {
    langOptionsMobile.addEventListener('click', (e) => {
        if (e.target.tagName === 'LI') {
            selectLanguage(e.target.dataset.lang);
        }
    });
}

// Load saved language preference
const savedLang = localStorage.getItem('swayam-language');
if (savedLang && languages[savedLang]) {
    currentLang = savedLang;
}
updateLanguageDisplay();

// ═══════════════════════════════════════════════════════════════════════
// HERO SLIDER
// ═══════════════════════════════════════════════════════════════════════

const slider = document.getElementById('heroSlider');
if (slider) {
    const slides = slider.querySelectorAll('.slide');
    const prevBtn = document.getElementById('sliderPrev');
    const nextBtn = document.getElementById('sliderNext');
    const indicators = document.querySelectorAll('.indicator');
    
    let currentSlide = 0;
    let isPlaying = true;
    let slideInterval;

    function showSlide(index) {
        // Remove active class from all slides and indicators
        slides.forEach(slide => {
            slide.classList.remove('active');
            // Reset image transform for smooth transition
            const img = slide.querySelector('.slide-img');
            if (img) img.style.transform = 'scale(1.1)';
        });
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Add active class to current slide and indicator with delay
        setTimeout(() => {
            slides[index].classList.add('active');
            indicators[index].classList.add('active');
            
            // Start gentle zoom animation
            const activeImg = slides[index].querySelector('.slide-img');
            if (activeImg) {
                setTimeout(() => {
                    activeImg.style.transform = 'scale(1)';
                }, 100);
            }
        }, 50);
        
        currentSlide = index;
    }

    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }

    function startAutoPlay() {
        slideInterval = setInterval(nextSlide, 6000);
        isPlaying = true;
    }

    function stopAutoPlay() {
        clearInterval(slideInterval);
        isPlaying = false;
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoPlay();
            setTimeout(startAutoPlay, 10000); // Resume after 10 seconds
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoPlay();
            setTimeout(startAutoPlay, 10000);
        });
    }

    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            showSlide(index);
            stopAutoPlay();
            setTimeout(startAutoPlay, 10000);
        });
    });

    // Pause on hover
    slider.addEventListener('mouseenter', stopAutoPlay);
    slider.addEventListener('mouseleave', () => {
        if (!isPlaying) startAutoPlay();
    });

    // Start autoplay
    startAutoPlay();
}

// ═══════════════════════════════════════════════════════════════════════
// SPIRITUAL SCROLL REVEAL ANIMATIONS
// ═══════════════════════════════════════════════════════════════════════

const observerOptions = {
    root: null,
    rootMargin: '-50px 0px -50px 0px',
    threshold: [0.1, 0.3, 0.5]
};

const spiritualObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            // Add staggered delay for multiple elements
            setTimeout(() => {
                entry.target.classList.add('revealed');
            }, index * 150);
        }
    });
}, observerOptions);

// Observe all reveal elements with enhanced detection
document.querySelectorAll('.reveal, .reveal-fade, .reveal-left, .reveal-right, .reveal-scale').forEach(element => {
    spiritualObserver.observe(element);
});

// ═══════════════════════════════════════════════════════════════════════
// PARALLAX EFFECTS
// ═══════════════════════════════════════════════════════════════════════

let ticking = false;

function updateParallax() {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.slide-img, .image-placeholder');
    
    parallaxElements.forEach(element => {
        const speed = 0.5;
        const yPos = -(scrolled * speed);
        element.style.transform = `translate3d(0, ${yPos}px, 0) scale(1.1)`;
    });
    
    ticking = false;
}

function requestParallaxUpdate() {
    if (!ticking) {
        requestAnimationFrame(updateParallax);
        ticking = true;
    }
}

// Only add parallax on non-mobile devices
if (window.innerWidth > 768 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    window.addEventListener('scroll', requestParallaxUpdate, { passive: true });
}

// ═══════════════════════════════════════════════════════════════════════
// GENTLE HOVER ANIMATIONS
// ═══════════════════════════════════════════════════════════════════════

// Add gentle hover effects to cards
document.querySelectorAll('.glass-card, .testimonial-card, .pricing-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-12px) scale(1.02)';
        card.style.boxShadow = '0 20px 60px rgba(146, 115, 151, 0.25)';
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0) scale(1)';
        card.style.boxShadow = '';
    });
});

// Gentle float animation for icons
document.querySelectorAll('.icon-circle').forEach((icon, index) => {
    icon.style.animationDelay = `${index * 0.5}s`;
});

// ═══════════════════════════════════════════════════════════════════════
// PWA INSTALL PROMPT
// ═══════════════════════════════════════════════════════════════════════

let deferredPrompt;
const installPrompt = document.getElementById('installPrompt');
const btnInstall = installPrompt?.querySelector('.btn-install');

window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing
    e.preventDefault();
    
    // Store the event for later use
    deferredPrompt = e;
    
    // Show install prompt
    if (installPrompt) {
        installPrompt.classList.remove('hidden');
    }
});

if (btnInstall) {
    btnInstall.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        // Show the install prompt
        deferredPrompt.prompt();

        // Wait for the user's response
        const { outcome } = await deferredPrompt.userChoice;
        
        console.log(`User response: ${outcome}`);
        
        if (outcome === 'accepted') {
            console.log('✅ PWA installed');
        }

        // Clear the deferredPrompt
        deferredPrompt = null;
        
        // Hide the install button
        installPrompt.classList.add('hidden');
    });
}

// Hide install prompt if already installed
window.addEventListener('appinstalled', () => {
    console.log('✅ PWA was installed');
    if (installPrompt) {
        installPrompt.classList.add('hidden');
    }
});

// Check if already installed
if (window.matchMedia('(display-mode: standalone)').matches) {
    console.log('✅ Running in standalone mode');
    if (installPrompt) {
        installPrompt.classList.add('hidden');
    }
}

// ═══════════════════════════════════════════════════════════════════════
// OFFLINE INDICATOR
// ═══════════════════════════════════════════════════════════════════════

const offlineIndicator = document.getElementById('offlineIndicator');

function updateOnlineStatus() {
    if (navigator.onLine) {
        console.log('✅ Online');
        if (offlineIndicator) {
            offlineIndicator.classList.add('hidden');
        }
    } else {
        console.log('⚠️ Offline');
        if (offlineIndicator) {
            offlineIndicator.classList.remove('hidden');
        }
    }
}

window.addEventListener('online', updateOnlineStatus);
window.addEventListener('offline', updateOnlineStatus);

// Initial check
updateOnlineStatus();

// ═══════════════════════════════════════════════════════════════════════
// FAQ FUNCTIONALITY
// ═══════════════════════════════════════════════════════════════════════

const faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');
    
    question.addEventListener('click', () => {
        const isActive = item.classList.contains('active');
        
        // Close all other FAQ items
        faqItems.forEach(otherItem => {
            if (otherItem !== item) {
                otherItem.classList.remove('active');
            }
        });
        
        // Toggle current item
        item.classList.toggle('active', !isActive);
    });
});

// ═══════════════════════════════════════════════════════════════════════
// NEWSLETTER FORM
// ═══════════════════════════════════════════════════════════════════════

const newsletterForm = document.getElementById('newsletterForm');

if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const email = newsletterForm.querySelector('input[type="email"]').value;
        
        // Here you would send to your backend
        console.log('Newsletter subscription:', email);
        
        // Show success message
        alert('Thank you for subscribing! 🙏');
        newsletterForm.reset();
    });
}

// ═══════════════════════════════════════════════════════════════════════
// FOOTER CURRENT YEAR
// ═══════════════════════════════════════════════════════════════════════

const currentYearSpan = document.getElementById('currentYear');
if (currentYearSpan) {
    currentYearSpan.textContent = new Date().getFullYear();
}

// ═══════════════════════════════════════════════════════════════════════
// SMOOTH SCROLL FOR ANCHOR LINKS
// ═══════════════════════════════════════════════════════════════════════

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href === '#') return;
        
        e.preventDefault();
        const target = document.querySelector(href);
        
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ═══════════════════════════════════════════════════════════════════════
// PERFORMANCE MONITORING
// ═══════════════════════════════════════════════════════════════════════

window.addEventListener('load', () => {
    // Check if PerformanceObserver is supported
    if ('PerformanceObserver' in window) {
        // Monitor largest contentful paint
        const lcpObserver = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
        });
        
        lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
    }
    
    // Log page load time
    const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
    console.log('Page load time:', loadTime, 'ms');
});

// ═══════════════════════════════════════════════════════════════════════
// ACCESSIBILITY: KEYBOARD NAVIGATION
// ═══════════════════════════════════════════════════════════════════════

// Trap focus in mobile menu when open
if (mobileMenu) {
    const focusableElements = mobileMenu.querySelectorAll(
        'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    
    if (focusableElements.length > 0) {
        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];
        
        mobileMenu.addEventListener('keydown', (e) => {
            if (e.key !== 'Tab' || !mobileMenu.classList.contains('active')) return;
            
            if (e.shiftKey) {
                if (document.activeElement === firstFocusable) {
                    e.preventDefault();
                    lastFocusable.focus();
                }
            } else {
                if (document.activeElement === lastFocusable) {
                    e.preventDefault();
                    firstFocusable.focus();
                }
            }
        });
    }
}

// ESC key closes mobile menu
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && mobileMenu?.classList.contains('active')) {
        mobileMenuBtn?.classList.remove('active');
        mobileMenu.classList.remove('active');
    }
});

// ═══════════════════════════════════════════════════════════════════════
// CONSOLE WELCOME MESSAGE
// ═══════════════════════════════════════════════════════════════════════

console.log(
    '%c🙏 Swayam - Journey Within 🙏',
    'font-size: 24px; font-weight: bold; background: linear-gradient(135deg, #927397, #D6809C); color: white; padding: 20px; border-radius: 10px;'
);

console.log(
    '%cWelcome to Swayam! This website works offline thanks to Progressive Web App technology.',
    'font-size: 14px; color: #927397; padding: 10px;'
);

// ═══════════════════════════════════════════════════════════════════════
// DEBUG MODE (Remove in production)
// ═══════════════════════════════════════════════════════════════════════

if (location.hostname === 'localhost' || location.hostname === '127.0.0.1') {
    console.log('🔧 Debug mode enabled');
    
    // Log all service worker events
    if (navigator.serviceWorker) {
        navigator.serviceWorker.addEventListener('message', (event) => {
            console.log('📨 SW Message:', event.data);
        });
    }
}

// ═══════════════════════════════════════════════════════════════════════
// INITIALIZATION COMPLETE
// ═══════════════════════════════════════════════════════════════════════

console.log('✅ Swayam initialized successfully');
document.addEventListener('DOMContentLoaded', function() {
    // Find all slider containers on the page
    const sliders = document.querySelectorAll('.gallery-slider');

    // Loop through each slider found
    sliders.forEach((slider, index) => {
        const images = slider.querySelectorAll('.slider-img');
        
        // If a slider has less than 2 images, don't run the script for it
        if (images.length < 2) return;

        let currentIndex = 0;

        // Set distinct timings so they don't all change at exactly the same millisecond (optional aesthetic choice)
        const intervalTime = 3500 + (index * 200); // e.g., 3500ms, 3700ms, 3900ms...

        function cycleImages() {
            // 1. Remove active class from current image
            images[currentIndex].classList.remove('active');

            // 2. Calculate next index (loop back to 0 at the end)
            currentIndex = (currentIndex + 1) % images.length;

            // 3. Add active class to the new image
            images[currentIndex].classList.add('active');
        }

        // Start the automated cycling
        setInterval(cycleImages, intervalTime);
    });
});