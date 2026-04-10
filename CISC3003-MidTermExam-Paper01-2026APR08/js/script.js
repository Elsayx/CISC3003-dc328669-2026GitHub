const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

// Toggle between Sign In and Sign Up panels
signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

// Validation Requirements
// Requirement 1: Password length >= 8
// Requirement 2: Valid email format

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

// Handle Sign Up Form Submission
document.getElementById('signup-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('signup-email').value;
    const password = document.getElementById('signup-password').value;
    
    if (!validateEmail(email)) {
        alert("Please enter a valid email address!");
        return;
    }
    
    if (password.length < 8) {
        alert("Password must be at least 8 characters long!");
        return;
    }
    
    alert("Sign Up form submitted successfully!");
});

// Handle Sign In Form Submission
document.getElementById('signin-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('signin-email').value;
    const password = document.getElementById('signin-password').value;
    
    if (!validateEmail(email)) {
        alert("Please enter a valid email address!");
        return;
    }
    
    if (password.length < 8) {
        alert("Password must be at least 8 characters long!");
        return;
    }
    
    alert("Sign In form submitted successfully!");
});