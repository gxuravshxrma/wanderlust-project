document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.contact-form');
    if (!form) {
      console.error('Form .contact-form not found');
      return;
    }
  
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
  
      const name    = form.querySelector('[name="name"]').value;
      const email   = form.querySelector('[name="email"]').value;
      const phone   = form.querySelector('[name="phone"]').value;
      const subject = form.querySelector('[name="subject"]').value;
      const message = form.querySelector('[name="message"]').value;
  
      try {
        const response = await fetch('sendmail.php', {

          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, email, phone, subject, message })
        });
  
        // ensure we got JSON back
        const result = await response.json();
  
        if (result.success) {
          alert('Message sent! We’ll contact you soon.');
          form.reset();
        } else {
          alert('Error: ' + (result.error || 'Unknown error'));
        }
      } catch (err) {
        console.error('Form submission error:', err);
        alert('Server error. Please try again later.');
      }
    });
  });
  