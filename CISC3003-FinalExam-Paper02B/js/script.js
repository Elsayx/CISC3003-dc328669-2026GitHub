const contactForm = document.querySelector('#contactForm');
const contactHint = document.querySelector('#contactHint');

if (contactForm) {
    contactForm.addEventListener('submit', (event) => {
        const message = contactForm.querySelector('#message').value.trim();
        if (!contactForm.checkValidity() || message.length < 10) {
            event.preventDefault();
            contactHint.textContent = 'Please enter a valid name, email, subject and message.';
            contactForm.reportValidity();
            return;
        }

        contactHint.textContent = 'Submitting contact form to PHP and PHPMailer...';
    });
}
