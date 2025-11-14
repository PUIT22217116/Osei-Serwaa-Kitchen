<?php include 'includes/header.php'; ?>

<main>
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Contact Us</h1> 
                <p>We'd love to hear from you. Get in touch with Osei Serwaa Kitchen.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-main">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Information -->
                <div class="contact-info">
                    <h2>Get In Touch</h2>
                    <p>Have questions about our menu, want to make a special reservation, or just want to say hello? We're here to help!</p>
                    
                    <div class="contact-methods">
                        <div class="contact-method reveal">
                            <div class="contact-icon">📍</div> 
                            <div class="contact-details">
                                <h3>Visit Us</h3>
                                <p>Offankor Barrier<br>Accra, Ghana</p>
                            </div>
                        </div>
                        
                        <div class="contact-method reveal">
                            <div class="contact-icon">📞</div>
                            <div class="contact-details">
                                <h3>Call Us</h3>
                                <p>053 495 575<br>020 363 538</p>
                            </div>
                        </div>
                        
                        <div class="contact-method reveal">
                            <div class="contact-icon">✉️</div>
                            <div class="contact-details">
                                <h3>Email Us</h3>
                                <p>info@oseiserwakitchen.com<br>reservations@oseiserwakitchen.com</p>
                            </div>
                        </div>
                        
                        <div class="contact-method reveal">
                            <div class="contact-icon">🕒</div>
                            <div class="contact-details">
                                <h3>Opening Hours</h3>
                                <p>Monday - Friday: 8am - 10pm<br>Saturday - Sunday: 9am - 11pm</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-links reveal">
                        <h3>Follow Us</h3>
                        <div class="social-icons">
                            <a href="https://www.tiktok.com/@osei.serwaa.kitch/video/7568565703553420555?_r=1&_t=ZM-91685Rgecwe" class="social-icon" title="TikTok" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="contact-form-container">
                    <form class="contact-form reveal" id="contactForm">
                        <div class="form-group ">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required>
                            <span class="error-message" id="nameError"></span>
                        </div>
                        
                        <div class="form-group ">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required>
                            <span class="error-message" id="emailError"></span>
                        </div>
                        
                        <div class="form-group ">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                            <span class="error-message" id="phoneError"></span>
                        </div>
                        
                        <div class="form-group ">
                            <label for="subject">Subject *</label>
                            <select id="subject" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="reservation">Reservation Inquiry</option>
                                <option value="catering">Catering Services</option>
                                <option value="feedback">Feedback</option>
                                <option value="complaint">Complaint</option>
                                <option value="other">Other</option>
                            </select>
                            <span class="error-message" id="subjectError"></span>
                        </div>
                        
                        <div class="form-group ">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                            <span class="error-message" id="messageError"></span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-full">Send Message</button>
                        
                        <div class="form-notification" id="formNotification"></div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <h2>Find Us</h2>
            <div class="map-container">
                <div class="map-placeholder">
                    <div class="map-content"> 
                        <h3>📍 Our Location</h3>
                        <p>Offankor Barrier, Accra, Ghana</p>
                        <div class="map-directions">
                            <a href="https://www.google.com/maps/search/?api=1&query=Offankor+Barrier+Accra" target="_blank" class="btn btn-outline">Get Directions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Review Modal -->
<div class="modal" id="reviewModal">
    <div class="modal-content">
        <span class="modal-close" id="reviewModalClose">&times;</span>
        <h2>Review Your Message</h2>
        <p>Please check your details below before sending.</p>
        <div class="review-details">
            <div class="review-item">
                <strong>Name:</strong>
                <p id="reviewName"></p>
            </div>
            <div class="review-item">
                <strong>Email:</strong>
                <p id="reviewEmail"></p>
            </div>
            <div class="review-item">
                <strong>Subject:</strong>
                <p id="reviewSubject"></p>
            </div>
            <div class="review-item">
                <strong>Message:</strong>
                <pre id="reviewMessage"></pre>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-outline" id="editBtn">Go Back & Edit</button>
            <button class="btn btn-primary" id="confirmSendBtn">Confirm & Send</button>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div class="modal" id="successModal">
    <div class="modal-content" style="text-align: center;">
        <div class="success-icon">✅</div>
        <h2>Message Sent!</h2>
        <p id="successMessageText">
            Thank you for reaching out. Your message has been sent successfully. We will get back to you shortly.
        </p>
        <div class="modal-actions" style="justify-content: center;">
            <button class="btn btn-primary" id="successModalOk">OK</button>
        </div>
    </div>
</div>


<!-- Include contact form script -->
<script>
    // Make the admin number available to the contact form script
    const waAdminNumber = '<?php echo defined("WA_ADMIN_NUMBER") ? WA_ADMIN_NUMBER : ""; ?>';
</script>
<script src="js/contact-form.js"></script>

<?php include 'includes/footer.php'; ?>