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
                <p>Book your table at Osei Serwa Kitchen for an unforgettable dining experience</p>
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
                                <p>Call us at <strong>+233 123 456 789</strong> for immediate assistance with your reservation.</p>
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

<?php include 'includes/footer.php'; ?>