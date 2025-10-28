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
                // Here you would typically submit the form via AJAX
                // For now, we'll just show the confirmation modal as a demo
                console.log('Form is valid. Submitting...');
                showConfirmationModal();
            }
        });
    }

    /**
     * Shows a confirmation modal (demo).
     */
    function showConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const closeButton = document.getElementById('modalClose');
        const okButton = document.getElementById('modalOk');

        if (modal) {
            modal.classList.add('active');
            closeButton.onclick = () => modal.classList.remove('active');
            okButton.onclick = () => modal.classList.remove('active');
        }
    }
});