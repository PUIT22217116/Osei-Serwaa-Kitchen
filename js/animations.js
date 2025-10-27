// Advanced animations for the website
class Animations {
    constructor() {
        this.observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        this.init();
    }

    init() {
        this.setupScrollAnimations();
        this.setupHoverAnimations();
        this.setupLoadingAnimations();
    }

    setupScrollAnimations() {
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, this.observerOptions);

        // Observe elements for scroll animations
        document.querySelectorAll('.story-point, .team-member, .value-card, .menu-item, .special-card').forEach(el => {
            observer.observe(el);
        });
    }

    setupHoverAnimations() {
        // Add hover effects to cards
        document.querySelectorAll('.team-member, .value-card, .menu-item, .special-card').forEach(card => {
            card.addEventListener('mouseenter', this.handleCardHover);
            card.addEventListener('mouseleave', this.handleCardLeave);
        });
    }

    handleCardHover(e) {
        const card = e.currentTarget;
        card.style.transform = 'translateY(-10px)';
        card.style.boxShadow = '0 15px 40px rgba(0, 0, 0, 0.15)';
    }

    handleCardLeave(e) {
        const card = e.currentTarget;
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = 'var(--shadow)';
    }

    setupLoadingAnimations() {
        // Add loading animation to images
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('load', this.handleImageLoad);
            img.addEventListener('error', this.handleImageError);
        });
    }

    handleImageLoad(e) {
        const img = e.target;
        img.style.opacity = '0';
        img.style.transition = 'opacity 0.5s ease';
        
        setTimeout(() => {
            img.style.opacity = '1';
        }, 100);
    }

    handleImageError(e) {
        const img = e.target;
        img.src = 'images/placeholder.jpg';
        console.warn('Image failed to load:', img.alt);
    }

    // Parallax effect for hero sections
    setupParallax() {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.hero, .about-hero, .menu-hero');
            
            parallaxElements.forEach(element => {
                const rate = scrolled * -0.5;
                element.style.transform = `translateY(${rate}px)`;
            });
        });
    }

    // Counter animation for statistics
    animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start);
            }
        }, 16);
    }
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const animations = new Animations();
    animations.setupParallax();
});

// Utility function for smooth element reveal
function revealOnScroll() {
    const reveals = document.querySelectorAll('.reveal');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {
        threshold: 0.1
    });

    reveals.forEach(reveal => {
        observer.observe(reveal);
    });
}

// Add CSS for scroll animations
const style = document.createElement('style');
style.textContent = `
    .animate-in {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease-out;
    }
    
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

// Initialize reveal on scroll
document.addEventListener('DOMContentLoaded', revealOnScroll);