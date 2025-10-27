// Form validation and submission functionality
class FormValidation {
    constructor() {
        this.contactForm = document.getElementById('contactForm');
        this.reservationForm = document.getElementById('reservationForm');
        this.init();
    }

    init() {
        if (this.contactForm) {
            this.setupContactForm();
        }
        
        if (this.reservationForm) {
            this.setupReservationForm();
        }
        
        this.setupModal();
    }

    setupContactForm() {
        this.contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (this.validateContactForm()) {
                this.submitContactForm();
            }
        });

        // Real-time validation
        this.setupRealTimeValidation(this.contactForm);
    }

    setupReservationForm() {
        this.reservationForm.addEventListener('submit', (e) => {
            e.preventDefault();
            if (this.validateReservationForm()) {
                this.submitReservationForm();
            }
        });

        // Real-time validation
        this.setupRealTimeValidation(this.reservationForm);

        // Date restrictions
        this.setupDateRestrictions();
    }

    setupRealTimeValidation(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                this.clearFieldError(input);
            });
        });
    }

    setupDateRestrictions() {
        const dateInput = document.getElementById('reservation-date');
        if (dateInput) {
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;

            // Set max date to 3 months from today
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 3);
            dateInput.max = maxDate.toISOString().split('T')[0];

            // Generate time slots when date changes
            dateInput.addEventListener('change', () => {
                this.generateTimeSlots();
            });

            // Initial time slot generation
            this.generateTimeSlots();
        }
    }

    generateTimeSlots() {
        const timeSelect = document.getElementById('reservation-time');
        if (!timeSelect) return;

        // Clear existing options except the first one
        while (timeSelect.options.length > 1) {
            timeSelect.remove(1);
        }

        // Generate time slots (every 30 minutes from opening to closing)
        const openingTime = 8 * 60; // 8:00 AM in minutes
        const closingTime = 22 * 60; // 10:00 PM in minutes
        const interval = 30; // 30 minutes

        for (let time = openingTime; time <= closingTime; time += interval) {
            const hours = Math.floor(time / 60);
            const minutes = time % 60;
            const period = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            const timeString = `${displayHours}:${minutes.toString().padStart(2, '0')} ${period}`;
            
            const option = document.createElement('option');
            option.value = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
            option.textContent = timeString;
            timeSelect.appendChild(option);
        }
    }

    validateContactForm() {
        let isValid = true;
        const fields = [
            { id: 'name', type: 'text', required: true },
            { id: 'email', type: 'email', required: true },
            { id: 'phone', type: 'tel', required: false },
            { id: 'subject', type: 'select', required: true },
            { id: 'message', type: 'textarea', required: true }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (field.required && !this.validateField(element)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateReservationForm() {
        let isValid = true;
        const fields = [
            { id: 'name', name: 'name', type: 'text', required: true },
            { id: 'email', name: 'email', type: 'email', required: true },
            { id: 'phone', name: 'phone', type: 'tel', required: true },
            { id: 'guests', name: 'guests', type: 'select', required: true },
            { id: 'date', name: 'date', type: 'date', required: true },
            { id: 'time', name: 'time', type: 'select', required: true }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            if (field.required && !this.validateField(element)) {
                isValid = false;
            }
        });

        // Additional validation for date
        const dateInput = document.getElementById('date');
        if (dateInput.value) {
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                this.showFieldError(dateInput, 'Please select a future date');
                isValid = false;
            }
        }

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldId = field.id;

        // Clear previous error
        this.clearFieldError(field);

        // Check required fields
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'This field is required');
            return false;
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.showFieldError(field, 'Please enter a valid email address');
                return false;
            }
        }

        // Phone validation
        if (field.type === 'tel' && value) {
            const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
            if (!phoneRegex.test(value)) {
                this.showFieldError(field, 'Please enter a valid phone number');
                return false;
            }
        }

        return true;
    }

    showFieldError(field, message) {
        const errorElement = document.getElementById(field.id + 'Error');
        if (errorElement) {
            errorElement.textContent = message;
            field.style.borderColor = '#e74c3c';
        }
    }

    clearFieldError(field) {
        const errorElement = document.getElementById(field.id + 'Error');
        if (errorElement) {
            errorElement.textContent = '';
            field.style.borderColor = '#e0e0e0';
        }
    }

    async submitContactForm() {
        const formData = new FormData(this.contactForm);
        const notification = document.getElementById('formNotification');

        try {
            // Simulate API call - replace with actual endpoint
            await this.simulateAPICall(formData);
            
            notification.className = 'form-notification success';
            notification.textContent = 'Thank you! Your message has been sent successfully.';
            this.contactForm.reset();
            
            // Hide notification after 5 seconds
            setTimeout(() => {
                notification.style.display = 'none';
            }, 5000);
            
        } catch (error) {
            notification.className = 'form-notification error';
            notification.textContent = 'Sorry, there was an error sending your message. Please try again.';
        }
    }

    async submitReservationForm() {
        const formData = new FormData(this.reservationForm);
        const notification = document.getElementById('formNotification');
        const submitButton = this.reservationForm.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        try {
            submitButton.disabled = true;
            submitButton.innerHTML = 'Booking...';

            const response = await fetch('api/submit-reservation.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('A network error occurred. Please try again.');
            }

            const result = await response.json();

            if (result.success) {
                // Show confirmation modal with reservation details
                this.showReservationConfirmation(formData);
                this.reservationForm.reset();
                notification.style.display = 'none'; // Hide any previous error messages
            } else {
                throw new Error(result.message || 'An unknown error occurred.');
            }
            
        } catch (error) {
            notification.className = 'form-notification error';
            notification.textContent = error.message;
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    }

    showReservationConfirmation(formData) {
        const modal = document.getElementById('confirmationModal');
        const detailsContainer = document.getElementById('reservationDetails');
        
        // Format date for display
        const date = new Date(formData.get('date'));
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const formattedDate = date.toLocaleDateString('en-US', options);
        
        // Format time for display
        const time = formData.get('time');
        const [hours, minutes] = time.split(':');
        const period = hours >= 12 ? 'PM' : 'AM';
        const displayHours = hours % 12 || 12;
        const formattedTime = `${displayHours}:${minutes} ${period}`;
        
        detailsContainer.innerHTML = `
            <div class="detail-item">
                <span class="detail-label">Name:</span>
                <span class="detail-value">${formData.get('name')}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date:</span>
                <span class="detail-value">${formattedDate}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Time:</span>
                <span class="detail-value">${formattedTime}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Guests:</span>
                <span class="detail-value">${formData.get('guests')} people</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Occasion:</span>
                <span class="detail-value">${formData.get('occasion') || 'Not specified'}</span>
            </div>
        `;
        
        modal.classList.add('active');
    }

    setupModal() {
        const modal = document.getElementById('confirmationModal');
        const closeBtn = document.getElementById('modalClose');
        const okBtn = document.getElementById('modalOk');

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                modal.classList.remove('active');
            });
        }

        if (okBtn) {
            okBtn.addEventListener('click', () => {
                modal.classList.remove('active');
            });
        }

        // Close modal when clicking outside
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
        }
    }
}

// Initialize form validation when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new FormValidation();
});