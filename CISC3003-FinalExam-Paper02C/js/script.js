const tabButtons = document.querySelectorAll('.tab-button');
const panels = document.querySelectorAll('.panel');
const signupForm = document.querySelector('#signupForm');
const signupHint = document.querySelector('#signupHint');
const signupEmail = document.querySelector('#signupEmail');
const emailStatus = document.querySelector('#emailStatus');

tabButtons.forEach((button) => {
    button.addEventListener('click', () => {
        tabButtons.forEach((item) => item.classList.remove('active'));
        panels.forEach((panel) => panel.classList.remove('active'));
        button.classList.add('active');
        document.querySelector(`#${button.dataset.target}`)?.classList.add('active');
    });
});

if (signupEmail) {
    signupEmail.addEventListener('blur', async () => {
        const email = signupEmail.value.trim();
        if (!email) {
            emailStatus.textContent = '';
            return;
        }

        try {
            const response = await fetch(`check_email.php?email=${encodeURIComponent(email)}`);
            const data = await response.json();
            emailStatus.textContent = data.message;
            emailStatus.className = data.available ? 'hint success' : 'hint error';
        } catch (error) {
            emailStatus.textContent = 'Ajax email check failed. Please verify the server is running.';
            emailStatus.className = 'hint error';
        }
    });
}

if (signupForm) {
    signupForm.addEventListener('submit', (event) => {
        const password = document.querySelector('#signupPassword').value;
        const confirmPassword = document.querySelector('#confirmPassword').value;

        if (!signupForm.checkValidity()) {
            event.preventDefault();
            signupHint.textContent = 'Please complete the signup form correctly.';
            signupForm.reportValidity();
            return;
        }

        if (password !== confirmPassword) {
            event.preventDefault();
            signupHint.textContent = 'Passwords do not match.';
            return;
        }

        signupHint.textContent = 'Submitting signup data to PHP...';
    });
}
