document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');

    // --- Time Slot Generation ---

    /**
     * Generates time slots based on the selected date.
     */
    function generateTimeSlots() {
        // Clear existing options
        timeSelect.innerHTML = '<option value="">Select time</option>';

        const selectedDate = new Date(dateInput.value + 'T00:00:00');
        if (isNaN(selectedDate.getTime())) {
            return; // Invalid date
        }

        const dayOfWeek = selectedDate.getDay(); // 0=Sunday, 1=Monday, ..., 6=Saturday
        const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);

        // Define opening hours
        const openingHour = isWeekend ? 9 : 8; // 9 AM on weekends, 8 AM on weekdays
        const closingHour = isWeekend ? 23 : 22; // 11 PM on weekends, 10 PM on weekdays

        const now = new Date();
        const isToday = selectedDate.toDateString() === now.toDateString();

        // Start generating slots from the opening hour
        for (let hour = openingHour; hour < closingHour; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                // Check if the time slot is in the past for today
                if (isToday) {
                    const currentHour = now.getHours();
                    const currentMinute = now.getMinutes();
                    if (hour < currentHour || (hour === currentHour && minute <= currentMinute)) {
                        continue; // Skip past time slots
                    }
                }

                // Format the time for display and value
                const period = hour < 12 ? 'AM' : 'PM';
                const displayHour = hour % 12 || 12; // Convert 0 to 12 for 12 AM, and 13-23 to 1-11 PM
                const displayMinute = minute.toString().padStart(2, '0');
                const valueTime = `${hour.toString().padStart(2, '0')}:${displayMinute}`;
                const displayText = `${displayHour}:${displayMinute} ${period}`;

                const option = document.createElement('option');
                option.value = valueTime;
                option.textContent = displayText;
                timeSelect.appendChild(option);
            }
        }
    }

    // --- Event Listeners ---

    // Generate time slots when the date changes
    if (dateInput) {
        dateInput.addEventListener('change', generateTimeSlots);
    }

    // Initial generation if a date is already selected (e.g., by browser autofill)
    if (dateInput && dateInput.value) {
        generateTimeSlots();
    }

    // --- Form Validation (Basic Example) ---
    const reservationForm = document.getElementById('reservationForm');
    if (reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent actual submission for this example

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            let isValid = true;
            const requiredFields = ['name', 'email', 'phone', 'guests', 'date', 'time'];

            requiredFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                const errorSpan = document.getElementById(`${fieldId}Error`);
                if (!input.value) {
                    isValid = false;
                    if (errorSpan) {
                        errorSpan.textContent = 'This field is required.';
                    }
                }
            });

            if (isValid) {
                // Get form data
                const formData = new FormData(reservationForm);
                
                // Disable submit button and show loading state
                const submitBtn = reservationForm.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = 'Sending...';

                // Submit via AJAX
                fetch('js/submit-reservation.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Build WhatsApp message
                        const message = `*New Table Reservation* 📅\n\n` +
                            `*Name:* ${formData.get('name')}\n` +
                            `*Phone:* ${formData.get('phone')}\n` +
                            `*Email:* ${formData.get('email')}\n` +
                            `*Date:* ${formData.get('date')}\n` +
                            `*Time:* ${formData.get('time')}\n` +
                            `*Guests:* ${formData.get('guests')}\n` +
                            (formData.get('occasion') ? `*Occasion:* ${formData.get('occasion')}\n` : '') +
                            (formData.get('notes') ? `\n*Special Requests:*\n${formData.get('notes')}` : '');

                        // Open WhatsApp with the message
                        const waNumber = '233246103680'; // 0246103680 in international format
                        const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`;
                        window.open(waUrl, '_blank');

                        // Show success modal with booking details
                        showConfirmationModal(formData);
                        
                        // Reset form
                        reservationForm.reset();
                    } else {
                        // Show error message
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const errorSpan = document.getElementById(`${field}Error`);
                                if (errorSpan) {
                                    errorSpan.textContent = data.errors[field];
                                }
                            });
                        }
                        alert(data.message || 'Failed to create reservation. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('A network error occurred. Please try again.');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                });
            }
        });
    }

    /**
     * Shows the confirmation modal with booking details
     */
    function showConfirmationModal(formData) {
        const modal = document.getElementById('confirmationModal');
        const closeButton = document.getElementById('modalClose');
        const okButton = document.getElementById('modalOk');
        const details = document.getElementById('reservationDetails');

        if (modal && details) {
            // Format the reservation details
            const time = formData.get('time');
            const [hours, minutes] = time.split(':');
            const timeStr = new Date(2000, 0, 1, hours, minutes).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            details.innerHTML = `
                <div class="detail-item">
                    <strong>Name:</strong> ${formData.get('name')}
                </div>
                <div class="detail-item">
                    <strong>Date:</strong> ${formData.get('date')}
                </div>
                <div class="detail-item">
                    <strong>Time:</strong> ${timeStr}
                </div>
                <div class="detail-item">
                    <strong>Guests:</strong> ${formData.get('guests')}
                </div>
            `;

            modal.style.display = 'block';
            closeButton.onclick = () => modal.style.display = 'none';
            okButton.onclick = () => modal.style.display = 'none';

            // Close on clicking outside
            window.onclick = (event) => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };
        }
    }
});