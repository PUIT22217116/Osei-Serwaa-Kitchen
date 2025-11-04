// Menu filtering functionality
class MenuFilter {
    constructor() {
        this.menuItems = [];
        this.filterButtons = document.querySelectorAll('.filter-btn');
        this.menuGrid = document.getElementById('menuGrid');
        this.init();
    }

    init() {
        this.loadMenuItems();
        this.setupEventListeners();
    }

    loadMenuItems() {
        // Use data provided by PHP if available, otherwise use an empty array.
        this.menuItems = typeof menuData !== 'undefined' ? menuData : [];
        this.renderMenuItems(this.menuItems);
    }

    setupEventListeners() {
        this.filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleFilterClick(e.target);
            });
        });
    }

    handleFilterClick(button) {
        // Remove active class from all buttons
        this.filterButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active class to clicked button
        button.classList.add('active');
        
        // Get filter category
        const filter = button.getAttribute('data-filter');
        
        // Filter menu items
        this.filterMenuItems(filter);
    }

    filterMenuItems(category) {
        let filteredItems;
        
        if (category === 'all') {
            filteredItems = this.menuItems;
        } else {
            filteredItems = this.menuItems.filter(item => item.category === category);
        }
        
        this.renderMenuItems(filteredItems);
    }

    renderMenuItems(items) {
        this.menuGrid.innerHTML = '';
        
        if (items.length === 0) {
            // Display a message when no items are found
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'menu-empty-message';
            emptyMessage.innerHTML = `
                <h3>No Dishes Found</h3>
                <p>There are currently no items in this category. Please check back later or explore our other delicious options!</p>
            `;
            this.menuGrid.appendChild(emptyMessage);
        } else {
            // Render items if they exist
            items.forEach((item) => {
                const menuItemElement = this.createMenuItemElement(item);
                this.menuGrid.appendChild(menuItemElement);
            });
    
            // Stagger the animation for the newly rendered items
            const visibleItems = this.menuGrid.querySelectorAll('.menu-item');
            visibleItems.forEach((item, index) => {
                setTimeout(() => { item.classList.add('visible'); }, index * 100);
            });
        }
    }

    createMenuItemElement(item) {
        const div = document.createElement('div');
        div.className = 'menu-item';
        
        div.innerHTML = `
            <div class="menu-item-image">
                <a href="${item.image || 'images/menu/placeholder.jpg'}" target="_blank" title="View full image">
                    <img src="${item.image}" alt="${item.name}" onerror="this.src='images/menu/placeholder.jpg'">
                </a>
            </div>
            <div class="menu-item-content">
                <div class="menu-item-header">
                    <h3 class="menu-item-title">${item.name}</h3>
                    <span class="menu-item-price">₵${parseFloat(item.price).toFixed(2)}</span>
                </div>
                <p class="menu-item-description">${item.description}</p>
                <div class="menu-item-tags">
                    ${(item.tags || '').split(',').filter(tag => tag.trim() !== '').map(tag => `<span class="menu-tag">${tag.trim()}</span>`).join('')}
                </div>
            </div>
        `;
        
        return div;
    }
}

// Initialize menu filter when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new MenuFilter();
});