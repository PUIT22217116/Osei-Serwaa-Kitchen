document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');
    const notification = document.getElementById('formNotification');

    // Modal elements
    const reviewModal = document.getElementById('reviewModal');
    const reviewModalClose = document.getElementById('reviewModalClose');
    const editBtn = document.getElementById('editBtn');
    const confirmSendBtn = document.getElementById('confirmSendBtn');

    const successModal = document.getElementById('successModal');
    const successModalOk = document.getElementById('successModalOk');
    const successMessageText = document.getElementById('successMessageText');

    let currentFormData = null;

    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear previous errors and notifications
        clearErrors();
        notification.style.display = 'none';
        notification.className = 'form-notification';

        if (!validateForm()) {
            showNotification('Please fill out all required fields correctly.', 'error');
            return;
        }

        // Populate and show review modal instead of sending
        currentFormData = new FormData(form);
        populateReviewModal(currentFormData);
        reviewModal.style.display = 'block';
    });

    // --- Modal Event Listeners ---
    reviewModalClose.onclick = () => reviewModal.style.display = 'none';
    editBtn.onclick = () => reviewModal.style.display = 'none';
    window.onclick = (event) => {
        if (event.target == reviewModal) reviewModal.style.display = 'none';
    };
    successModalOk.onclick = () => successModal.style.display = 'none';
    confirmSendBtn.addEventListener('click', handleConfirmSend);

    function validateForm() {
        let isValid = true;
        const requiredFields = ['name', 'email', 'subject', 'message'];
        
        requiredFields.forEach(id => {
            const input = document.getElementById(id);
            const errorSpan = document.getElementById(`${id}Error`);
            if (!input.value.trim()) {
                errorSpan.textContent = 'This field is required.';
                isValid = false;
            }
        });

        const emailInput = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailInput.value.trim() && !emailPattern.test(emailInput.value)) {
            emailError.textContent = 'Please enter a valid email address.';
            isValid = false;
        }

        return isValid;
    }

    function populateReviewModal(formData) {
        document.getElementById('reviewName').textContent = formData.get('name');
        document.getElementById('reviewEmail').textContent = formData.get('email');
        document.getElementById('reviewSubject').textContent = formData.get('subject');
        document.getElementById('reviewMessage').textContent = formData.get('message');
    }

    function handleConfirmSend() {
        confirmSendBtn.disabled = true;
        confirmSendBtn.textContent = 'Sending...';

        // Send the data to the server to be processed
        fetch('js/submit-contact.php', {
            method: 'POST',
            body: currentFormData
        })
        .then(response => response.json())
        .then(data => {
            reviewModal.style.display = 'none';

            if (data.success) {
                // Build the WhatsApp message
                let message = `*Contact Form Submission* 📩\n\n`;
                message += `*Name:* ${currentFormData.get('name')}\n`;
                message += `*Email:* ${currentFormData.get('email')}\n`;
                if (currentFormData.get('phone')) {
                    message += `*Phone:* ${currentFormData.get('phone')}\n`;
                }
                message += `*Subject:* ${currentFormData.get('subject')}\n\n`;
                message += `*Message:*\n${currentFormData.get('message')}`;

                // Use direct number if waAdminNumber is not available
                const adminNumber = '233246103680';
                const whatsappUrl = `https://wa.me/${adminNumber}?text=${encodeURIComponent(message)}`;

                // Try to open WhatsApp
                const waWindow = window.open(whatsappUrl, '_blank');
                
                if (waWindow) {
                    successMessageText.textContent = 'Thank you! Your message has been sent successfully.';
                    successModal.style.display = 'block';
                    form.reset();
                } else {
                    // If popup was blocked, show instructions
                    successMessageText.innerHTML = 'Your message was saved. <a href="' + whatsappUrl + '" target="_blank">Click here to open WhatsApp</a> and send your message.';
                    successModal.style.display = 'block';
                }
                
                successModalOk.onclick = () => {
                    successModal.style.display = 'none';
                    if (!waWindow) {
                        window.open(whatsappUrl, '_blank');
                    }
                };
            } else {
                showNotification(data.message || 'An unknown error occurred.', 'error');
                if (data.errors) {
                    displayErrors(data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            reviewModal.style.display = 'none';
            showNotification('A network error occurred. Please try again.', 'error');
        })
        .finally(() => {
            confirmSendBtn.disabled = false;
            confirmSendBtn.textContent = 'Confirm & Send';
        });
    }

    function showNotification(message, type) {
        notification.textContent = message;
        notification.className = 'form-notification'; // Reset classes
        notification.classList.add(type);
        notification.style.display = 'block';
    }

    function displayErrors(errors) {
        for (const field in errors) {
            const errorSpan = document.getElementById(`${field}Error`);
            if (errorSpan) {
                errorSpan.textContent = errors[field];
            }
        }
    }

    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(span => {
            span.textContent = '';
        });
    }
});