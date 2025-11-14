document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');

    if (hamburger && navMenu) {
        console.log('Hamburger and navMenu elements found. Initializing event listeners.');
        // Toggle main navigation menu
        hamburger.addEventListener('click', () => {
            const expanded = hamburger.getAttribute('aria-expanded') === 'true' || false;
            hamburger.setAttribute('aria-expanded', !expanded);
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Close menu on nav link click
        navMenu.addEventListener('click', (e) => {
            if (e.target.classList.contains('nav-link')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                hamburger.setAttribute('aria-expanded', 'false');
            }
        });
    }
    else {
        console.log('Hamburger or navMenu elements NOT found. Check HTML structure and class names.');
    }

    // --- Hero Slideshow ---
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length > 0) {
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach(slide => {
                slide.classList.remove('active');
            });
            slides[index].classList.add('active');
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        // Show the first slide initially
        showSlide(currentSlide);

        // Change slide every 5 seconds
        setInterval(nextSlide, 5000);
    }
    else {
        console.log('No hero-slide elements found for slideshow.');
    }
});