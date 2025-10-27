// Gallery functionality with filtering and lightbox
class GallerySlider {
    constructor() {
        this.galleryItems = [];
        this.filterButtons = document.querySelectorAll('.filter-btn');
        this.galleryGrid = document.getElementById('galleryGrid');
        this.lightbox = document.getElementById('lightbox');
        this.currentIndex = 0;
        this.filteredItems = [];
        this.init();
    }

    init() {
        this.loadGalleryItems();
        this.setupEventListeners();
        this.generateTimeSlots();
    }

    loadGalleryItems() {
        // Use data provided by PHP if available, otherwise use an empty array.
        this.galleryItems = typeof galleryData !== 'undefined' ? galleryData : [];

        this.filteredItems = this.galleryItems;
        this.renderGalleryItems();
    }

    setupEventListeners() {
        // Filter buttons
        this.filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleFilterClick(e.target);
            });
        });

        // Lightbox
        document.querySelector('.lightbox-close').addEventListener('click', () => {
            this.closeLightbox();
        });

        document.getElementById('lightbox-prev').addEventListener('click', () => {
            this.navigateLightbox(-1);
        });

        document.getElementById('lightbox-next').addEventListener('click', () => {
            this.navigateLightbox(1);
        });

        // Close lightbox on outside click
        this.lightbox.addEventListener('click', (e) => {
            if (e.target === this.lightbox) {
                this.closeLightbox();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (this.lightbox.classList.contains('active')) {
                if (e.key === 'Escape') this.closeLightbox();
                if (e.key === 'ArrowLeft') this.navigateLightbox(-1);
                if (e.key === 'ArrowRight') this.navigateLightbox(1);
            }
        });
    }

    handleFilterClick(button) {
        // Remove active class from all buttons
        this.filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        button.classList.add('active');
        
        // Get filter category
        const filter = button.getAttribute('data-filter');
        
        // Filter gallery items
        this.filterGalleryItems(filter);
    }

    filterGalleryItems(category) {
        if (category === 'all') {
            this.filteredItems = this.galleryItems;
        } else {
            this.filteredItems = this.galleryItems.filter(item => item.category === category);
        }
        
        this.renderGalleryItems();
    }

    renderGalleryItems() {
        this.galleryGrid.innerHTML = '';
        
        this.filteredItems.forEach((item, index) => {
            const galleryItemElement = this.createGalleryItemElement(item, index);
            this.galleryGrid.appendChild(galleryItemElement);
        });
    }

    createGalleryItemElement(item, index) {
        const div = document.createElement('div');
        div.className = 'gallery-item';
        div.style.animationDelay = `${index * 0.1}s`;
        div.setAttribute('data-index', index);
        
        div.innerHTML = `
            <div class="gallery-image">
                <img src="${item.image}" alt="${item.title}" onerror="this.src='images/gallery/placeholder.jpg'">
            </div>
            <div class="gallery-overlay">
                <div class="gallery-info">
                    <h3 class="gallery-title">${item.title}</h3>
                    <p class="gallery-category">${item.category}</p>
                </div>
            </div>
        `;
        
        // Add click event to open lightbox
        div.addEventListener('click', () => {
            this.openLightbox(index);
        });
        
        return div;
    }

    openLightbox(index) {
        this.currentIndex = index;
        this.updateLightboxContent();
        this.lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeLightbox() {
        this.lightbox.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    navigateLightbox(direction) {
        this.currentIndex += direction;
        
        if (this.currentIndex < 0) {
            this.currentIndex = this.filteredItems.length - 1;
        } else if (this.currentIndex >= this.filteredItems.length) {
            this.currentIndex = 0;
        }
        
        this.updateLightboxContent();
    }

    updateLightboxContent() {
        const item = this.filteredItems[this.currentIndex];
        
        document.getElementById('lightbox-img').src = item.image;
        document.getElementById('lightbox-img').alt = item.title;
        document.getElementById('lightbox-title').textContent = item.title;
        document.getElementById('lightbox-desc').textContent = item.description;
        
        // Update navigation buttons state
        document.getElementById('lightbox-prev').style.display = this.filteredItems.length > 1 ? 'block' : 'none';
        document.getElementById('lightbox-next').style.display = this.filteredItems.length > 1 ? 'block' : 'none';
    }
}

// Initialize gallery when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new GallerySlider();
});