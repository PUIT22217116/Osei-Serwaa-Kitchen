// Admin main JavaScript functionality
class AdminMain {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupDataTables();
        this.setupModals();
        this.setupForms();
    }

    setupEventListeners() {
        // Toggle sidebar on mobile
        this.setupSidebarToggle();
        
        // Confirm delete actions
        this.setupDeleteConfirmations();
        
        // Toggle switches
        this.setupToggleSwitches();
        
        // Search functionality
        this.setupSearch();
    }

    setupSidebarToggle() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                document.querySelector('.admin-sidebar').classList.toggle('collapsed');
                document.querySelector('.admin-main').classList.toggle('expanded');
            });
        }
    }

    setupDeleteConfirmations() {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });
    }

    setupToggleSwitches() {
        document.querySelectorAll('.toggle-switch').forEach(switchElement => {
            switchElement.addEventListener('change', (e) => {
                const target = e.target;
                const itemId = target.dataset.id;
                const field = target.dataset.field;
                const value = target.checked ? 1 : 0;

                this.updateItemStatus(itemId, field, value);
            });
        });
    }

    setupSearch() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                this.filterTable(searchTerm);
            });
        }
    }

    filterTable(searchTerm) {
        const table = document.querySelector('.data-table');
        if (!table) return;

        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    setupDataTables() {
        // Simple table sorting
        document.querySelectorAll('.data-table th[data-sort]').forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                this.sortTable(header);
            });
        });
    }

    sortTable(header) {
        const table = header.closest('table');
        const columnIndex = Array.from(header.parentNode.children).indexOf(header);
        const isNumeric = header.dataset.type === 'number';
        const isAscending = header.dataset.sort === 'asc';
        
        const rows = Array.from(table.querySelectorAll('tbody tr'));
        
        rows.sort((a, b) => {
            const aValue = a.children[columnIndex].textContent.trim();
            const bValue = b.children[columnIndex].textContent.trim();
            
            let comparison = 0;
            if (isNumeric) {
                comparison = parseFloat(aValue) - parseFloat(bValue);
            } else {
                comparison = aValue.localeCompare(bValue);
            }
            
            return isAscending ? comparison : -comparison;
        });
        
        // Update table
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
        
        // Update sort indicator
        header.dataset.sort = isAscending ? 'desc' : 'asc';
        
        // Remove sort indicators from other headers
        table.querySelectorAll('th[data-sort]').forEach(h => {
            if (h !== header) {
                delete h.dataset.sort;
            }
        });
    }

    setupModals() {
        // Close modals
        document.querySelectorAll('.modal-close, .modal-cancel').forEach(button => {
            button.addEventListener('click', () => {
                this.closeModal(button.closest('.modal'));
            });
        });

        // Open modals
        document.querySelectorAll('[data-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.dataset.modal;
                this.openModal(document.getElementById(modalId));
            });
        });

        // Close modal on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.closeModal(modal);
                }
            });
        });
    }

    openModal(modal) {
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    closeModal(modal) {
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    }

    setupForms() {
        // AJAX form submissions
        document.querySelectorAll('.ajax-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitForm(form);
            });
        });

        // Image preview for file inputs
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', (e) => {
                this.previewImage(e.target);
            });
        });
    }

    previewImage(input) {
        const preview = document.getElementById(input.dataset.preview);
        if (!preview) return;

        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    async updateItemStatus(itemId, field, value) {
        try {
            const response = await fetch('../includes/ajax-handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'update_status',
                    item_id: itemId,
                    field: field,
                    value: value
                })
            });

            const result = await response.json();
            
            if (!result.success) {
                throw new Error(result.message);
            }

            this.showNotification('Status updated successfully', 'success');
        } catch (error) {
            console.error('Error updating status:', error);
            this.showNotification('Error updating status', 'error');
            
            // Revert toggle switch
            const switchElement = document.querySelector(`[data-id="${itemId}"][data-field="${field}"]`);
            if (switchElement) {
                switchElement.checked = !switchElement.checked;
            }
        }
    }

    async submitForm(form) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;

        try {
            // Show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';

            const response = await fetch(form.action || '../includes/ajax-handler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message || 'Operation completed successfully', 'success');
                
                // Reset form if needed
                if (form.dataset.reset === 'true') {
                    form.reset();
                }
                
                // Close modal if needed
                if (form.dataset.modal) {
                    this.closeModal(document.getElementById(form.dataset.modal));
                }
                
                // Reload page or update table
                if (result.reload) {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                throw new Error(result.message || 'An error occurred');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            this.showNotification(error.message, 'error');
        } finally {
            // Restore button state
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.admin-notification');
        existingNotifications.forEach(notification => notification.remove());

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `admin-notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${this.getNotificationColor(type)};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 3000;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);

        // Close on click
        notification.querySelector('.notification-close').addEventListener('click', () => {
            notification.remove();
        });
    }

    getNotificationColor(type) {
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            warning: '#f39c12',
            info: '#3498db'
        };
        return colors[type] || colors.info;
    }

    // Utility method for API calls
    async apiCall(endpoint, data = {}) {
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            return await response.json();
        } catch (error) {
            console.error('API call error:', error);
            return { success: false, message: 'Network error occurred' };
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.adminApp = new AdminMain();
});

// CSS for notifications
const notificationStyles = `
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.admin-notification .notification-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);