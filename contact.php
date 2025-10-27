<?php include 'includes/header.php'; ?>

<main>
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <h1>Contact Us</h1>
                <p>We'd love to hear from you. Get in touch with Osei Serwa Kitchen.</p>
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
                                <p>123 Food Street<br>Accra, Ghana</p>
                            </div>
                        </div>
                        
                        <div class="contact-method reveal">
                            <div class="contact-icon">📞</div>
                            <div class="contact-details">
                                <h3>Call Us</h3>
                                <p>+233 123 456 789<br>+233 987 654 321</p>
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
                            <a href="#" class="social-icon">📘</a>
                            <a href="#" class="social-icon">📷</a>
                            <a href="#" class="social-icon">🐦</a>
                            <a href="#" class="social-icon">💼</a>
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
                        <p>123 Food Street, Accra, Ghana</p>
                        <div class="map-directions">
                            <a href="https://maps.google.com" target="_blank" class="btn btn-outline">Get Directions</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>