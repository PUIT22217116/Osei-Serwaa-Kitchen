<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
include 'includes/header.php';
?>

<main>
    <!-- Hero Section -->
    <section class="reservation-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Make a Reservation</h1>
                <p>Book your table at Osei Serwaa Kitchen for an unforgettable dining experience</p>
            </div>
        </div>
    </section>

    <!-- Reservation Form -->
    <section class="reservation-main">
        <div class="container">
            <div class="reservation-grid">
                <!-- Reservation Form -->
                <div class="reservation-form-container">
                    <form class="reservation-form" id="reservationForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" id="name" name="name" required>
                                <span class="error-message" id="nameError"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" id="email" name="email" required>
                                <span class="error-message" id="emailError"></span>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required>
                                <span class="error-message" id="phoneError"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="guests">Number of Guests *</label>
                                <select id="guests" name="guests" required>
                                    <option value="">Select guests</option>
                                    <option value="1">1 Person</option>
                                    <option value="2">2 People</option>
                                    <option value="3">3 People</option>
                                    <option value="4">4 People</option>
                                    <option value="5">5 People</option>
                                    <option value="6">6 People</option>
                                    <option value="7">7 People</option>
                                    <option value="8">8 People</option>
                                    <option value="9">9+ People (Special Arrangement)</option>
                                </select>
                                <span class="error-message" id="guestsError"></span>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="date">Date *</label>
                                <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                                <span class="error-message" id="dateError"></span>
                            </div>
                            
                            <div class="form-group">
                                <label for="time">Time *</label>
                                <select id="time" name="time" required>
                                    <option value="">Select time</option>
                                    <!-- Time slots will be populated by JavaScript -->
                                </select>
                                <span class="error-message" id="timeError"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="occasion">Special Occasion (Optional)</label>
                            <select id="occasion" name="occasion">
                                <option value="">No special occasion</option>
                                <option value="birthday">Birthday</option>
                                <option value="anniversary">Anniversary</option>
                                <option value="date">Date Night</option>
                                <option value="business">Business Dinner</option>
                                <option value="celebration">Celebration</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Special Requests (Optional)</label>
                            <textarea id="notes" name="notes" rows="4" placeholder="Any dietary requirements, accessibility needs, or special requests..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-full">Book Table</button>
                        
                        <div class="form-notification" id="formNotification"></div>
                    </form>
                </div>
                
                <!-- Reservation Info -->
                <div class="reservation-info">
                    <h2>Reservation Details</h2>
                    
                    <div class="reservation-features">
                        <div class="feature">
                            <div class="feature-icon">⏰</div>
                            <div class="feature-content">
                                <h3>Opening Hours</h3>
                                <p>Monday - Friday: 8am - 10pm<br>Saturday - Sunday: 9am - 11pm</p>
                            </div>
                        </div>
                        
                        <div class="feature">
                            <div class="feature-icon">👥</div>
                            <div class="feature-content">
                                <h3>Group Bookings</h3>
                                <p>For groups larger than 8 people, please call us directly to make arrangements.</p>
                            </div>
                        </div>
                        
                        <div class="feature">
                            <div class="feature-icon">🎉</div>
                            <div class="feature-content">
                                <h3>Special Occasions</h3>
                                <p>Celebrating something special? Let us know and we'll make it memorable!</p>
                            </div>
                        </div>
                        
                        <div class="feature">
                            <div class="feature-icon">📞</div>
                            <div class="feature-content">
                                <h3>Need Help?</h3>
                                <p>Call us at <strong>053 495 575 / 020 363 538</strong> for immediate assistance with your reservation.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="reservation-policy">
                        <h3>Reservation Policy</h3>
                        <ul>
                            <li>Reservations are held for 15 minutes past the booking time</li>
                            <li>For cancellations, please notify us at least 2 hours in advance</li>
                            <li>Large groups may require a deposit</li>
                            <li>Special dietary requirements can be accommodated with advance notice</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="modal-content">
            <span class="modal-close" id="modalClose">&times;</span>
            <div class="modal-icon">✅</div>
            <h2>Reservation Confirmed!</h2>
            <div class="reservation-details" id="reservationDetails">
                <!-- Reservation details will be populated here -->
            </div>
            <p class="modal-message">We've sent a confirmation email to your address. We look forward to serving you!</p>
            <button class="btn btn-primary" id="modalOk">OK</button>
        </div>
    </div>
</main>

<!-- Include form validation script -->
<script src="js/form-validation.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('reservationForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // The form-validation.js script handles the submission and shows a modal.
        // We will listen for the success event to trigger the WhatsApp redirect.
        
        // This is a custom event dispatched from form-validation.js upon success
        document.addEventListener('reservationSuccess', function(event) {
            const formData = event.detail.formData;
            const adminNumber = '<?php echo defined("WA_ADMIN_NUMBER") ? WA_ADMIN_NUMBER : ""; ?>';

            if (!adminNumber) {
                console.error('WhatsApp admin number is not configured in config.php');
                return;
            }

            // Build the message
            let message = `*New Reservation Request* 📅\n\n`;
            message += `*Name:* ${formData.get('name')}\n`;
            message += `*Email:* ${formData.get('email')}\n`;
            message += `*Phone:* ${formData.get('phone')}\n`;
            message += `*Guests:* ${formData.get('guests')}\n`;
            message += `*Date:* ${formData.get('date')}\n`;
            message += `*Time:* ${formData.get('time')}\n`;

            const occasion = formData.get('occasion');
            if (occasion) {
                message += `*Occasion:* ${occasion}\n`;
            }

            const notes = formData.get('notes');
            if (notes) {
                message += `\n*Notes:* ${notes}`;
            }

            // Create the WhatsApp click-to-chat URL
            const whatsappUrl = `https://wa.me/${adminNumber}?text=${encodeURIComponent(message)}`;

            // Update the confirmation modal to inform the user
            const modal = document.getElementById('confirmationModal');
            const modalMessage = modal.querySelector('.modal-message');
            const modalOkButton = modal.querySelector('#modalOk');

            modalMessage.innerHTML = 'Your request has been saved. <strong>Click OK to send us the details on WhatsApp.</strong>';
            
            // When the user clicks "OK", redirect them to WhatsApp
            modalOkButton.onclick = function() {
                window.open(whatsappUrl, '_blank');
                modal.style.display = 'none';
            };

        }, { once: true }); // Ensure the listener only runs once per submission

    });
});
</script>

<?php include 'includes/footer.php'; ?>