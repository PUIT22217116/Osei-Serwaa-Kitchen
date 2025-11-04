document.addEventListener('DOMContentLoaded', () => {
    class Gallery {
        constructor(galleryData) {
            this.galleryItems = galleryData || [];
            this.filteredItems = this.galleryItems;
            this.currentIndex = 0;

            this.grid = document.getElementById('galleryGrid');
            this.filterButtons = document.querySelectorAll('.filter-btn');
            
            // Lightbox elements
            this.lightbox = document.getElementById('lightbox');
            this.lightboxImg = document.getElementById('lightbox-img');
            this.lightboxTitle = document.getElementById('lightbox-title');
            this.lightboxDesc = document.getElementById('lightbox-desc');
            this.closeBtn = document.querySelector('.lightbox-close');
            this.prevBtn = document.getElementById('lightbox-prev');
            this.nextBtn = document.getElementById('lightbox-next');

            this.init();
        }

        init() {
            this.renderItems(this.galleryItems);
            this.addEventListeners();
        }

        renderItems(items) {
            this.grid.innerHTML = '';
            if (items.length === 0) {
                this.grid.innerHTML = '<p class="gallery-empty-message">No images found in this category.</p>';
                return;
            }

            items.forEach((item, index) => {
                const galleryItem = document.createElement('div');
                galleryItem.className = 'gallery-item';
                galleryItem.dataset.category = item.category;
                galleryItem.dataset.index = index;

                galleryItem.innerHTML = `
                    <div class="gallery-image">
                        <img src="${item.image}" alt="${item.title}">
                    </div>
                    <div class="gallery-overlay">
                        <div class="gallery-info">
                            <h3 class="gallery-title">${item.title}</h3>
                            <span class="gallery-category">${item.category}</span>
                        </div>
                    </div>
                `;

                galleryItem.addEventListener('click', () => {
                    this.openLightbox(item);
                });

                this.grid.appendChild(galleryItem);
            });
        }

        addEventListeners() {
            // Filter buttons
            this.filterButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    this.handleFilter(e.target);
                });
            });

            // Lightbox controls
            this.closeBtn.addEventListener('click', () => this.closeLightbox());
            this.lightbox.addEventListener('click', (e) => {
                if (e.target === this.lightbox) {
                    this.closeLightbox();
                }
            });
            this.prevBtn.addEventListener('click', () => this.showPrevImage());
            this.nextBtn.addEventListener('click', () => this.showNextImage());
            
            // Keyboard navigation for lightbox
            document.addEventListener('keydown', (e) => {
                if (this.lightbox.classList.contains('active')) {
                    if (e.key === 'ArrowLeft') this.showPrevImage();
                    if (e.key === 'ArrowRight') this.showNextImage();
                    if (e.key === 'Escape') this.closeLightbox();
                }
            });
        }

        handleFilter(button) {
            this.filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const filter = button.dataset.filter;

            if (filter === 'all') {
                this.filteredItems = this.galleryItems;
            } else {
                this.filteredItems = this.galleryItems.filter(item => item.category === filter);
            }

            this.renderItems(this.filteredItems);
        }

        openLightbox(item) {
            this.currentIndex = this.filteredItems.findIndex(i => i.id === item.id); // Find index in the *currently filtered* list
            this.updateLightboxContent();
            this.lightbox.classList.add('active');
        }

        closeLightbox() {
            this.lightbox.classList.remove('active');
        }

        updateLightboxContent() {
            const item = this.filteredItems[this.currentIndex];
            this.lightboxImg.src = item.image;
            this.lightboxImg.alt = item.title;
            this.lightboxTitle.textContent = item.title;
            this.lightboxDesc.textContent = item.description;
        }

        showPrevImage() {
            this.currentIndex = (this.currentIndex - 1 + this.filteredItems.length) % this.filteredItems.length;
            this.updateLightboxContent();
        }

        showNextImage() {
            this.currentIndex = (this.currentIndex + 1) % this.filteredItems.length;
            this.updateLightboxContent();
        }
    }

    // The 'galleryData' variable is provided by the PHP script in gallery.php
    if (typeof galleryData !== 'undefined') {
        new Gallery(galleryData);
    } else {
        console.error('Gallery data not found.');
    }
});