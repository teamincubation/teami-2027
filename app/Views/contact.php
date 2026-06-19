<style>
    .contact-section {
        max-width: 1000px;
        margin: 6rem auto;
        padding: 0 2rem;
    }

    @media (max-width: 768px) {
        .contact-section {
            margin: 3rem auto;
        }
    }

    .contact-hero {
        text-align: center;
        margin-bottom: 5rem;
    }

    .contact-hero h2 {
        font-size: 2.75rem;
        color: var(--text-main);
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #0f172a 40%, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .contact-hero p {
        color: var(--text-muted);
        font-size: 1.15rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 4rem;
        align-items: start;
    }

    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
        }
    }

    .contact-info h3 {
        font-size: 1.75rem;
        color: var(--text-main);
        margin-bottom: 1.5rem;
    }

    .info-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glow);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .info-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-glow);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--primary);
        font-size: 1.25rem;
        border: 1px solid var(--border-active);
    }

    .info-details h4 {
        color: var(--text-main);
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .info-details p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .contact-form-box {
        background: var(--bg-card);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--border-glow);
        border-radius: 16px;
        padding: 2.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
    }

    .contact-form-box h3 {
        color: var(--text-main);
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        font-family: 'Outfit', sans-serif;
    }

    .form-group-contact {
        margin-bottom: 1.5rem;
    }

    .form-group-contact label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-main);
    }

    .input-contact, .textarea-contact {
        width: 100%;
        background: #ffffff;
        border: 1px solid var(--border-glow);
        border-radius: 8px;
        padding: 0.8rem 1rem;
        font-size: 0.95rem;
        color: var(--text-main);
        font-family: 'Inter', sans-serif;
        transition: var(--transition);
    }

    .input-contact:focus, .textarea-contact:focus {
        outline: none;
        border-color: var(--primary);
        background: #ffffff;
        box-shadow: 0 0 10px rgba(5, 150, 105, 0.1);
    }

    .textarea-contact {
        resize: vertical;
        min-height: 120px;
    }
</style>

<div class="contact-section">
    <div class="contact-hero">
        <h2>Get in Touch</h2>
        <p>Have questions about internships, volunteer campaigns, or credential verification? Contact us below.</p>
    </div>
    
    <div class="contact-grid">
        <div class="contact-info">
            <h3>Contact Information</h3>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="info-details">
                    <h4>Email Address</h4>
                    <p>info@teamincubation.in</p>
                </div>
            </div>
            
            <div class="info-card">
                <div class="info-icon">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="info-details">
                    <h4>Office Address</h4>
                    <p>Calicut, Kerala, India - 673001</p>
                </div>
            </div>
        </div>
        
        <div class="contact-form-box">
            <h3>Send a Message</h3>
            <form onsubmit="event.preventDefault(); alert('Message sent successfully (Demo mode)!');">
                <div class="form-group-contact">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" class="input-contact" required placeholder="e.g. John Doe">
                </div>
                
                <div class="form-group-contact">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" class="input-contact" required placeholder="e.g. john@example.com">
                </div>
                
                <div class="form-group-contact">
                    <label for="message">Message</label>
                    <textarea id="message" class="textarea-contact" required placeholder="Write your message here..."></textarea>
                </div>
                
                <button type="submit" class="btn-verify" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </div>
</div>
