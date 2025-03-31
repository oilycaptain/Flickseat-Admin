document.addEventListener('DOMContentLoaded', function() {
    // Signup Form Validation
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('usernameError');
        const passwordError = document.getElementById('passwordError');
        
        // Password requirement elements
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqNumber = document.getElementById('req-number');

        usernameInput.addEventListener('input', validateUsername);
        passwordInput.addEventListener('input', validatePassword);

        signupForm.addEventListener('submit', function(e) {
            if (!validateUsername() || !validatePassword()) {
                e.preventDefault();
            }
        });

        function validateUsername() {
            const username = usernameInput.value.trim();
            if (username.length < 4) {
                usernameInput.classList.add('invalid');
                usernameError.style.display = 'block';
                return false;
            } else {
                usernameInput.classList.remove('invalid');
                usernameError.style.display = 'none';
                return true;
            }
        }

        function validatePassword() {
            const password = passwordInput.value;
            let isValid = true;
            
            // Check length
            if (password.length < 8) {
                reqLength.classList.remove('valid');
                isValid = false;
            } else {
                reqLength.classList.add('valid');
            }
            
            // Check uppercase
            if (!/[A-Z]/.test(password)) {
                reqUppercase.classList.remove('valid');
                isValid = false;
            } else {
                reqUppercase.classList.add('valid');
            }
            
            // Check number
            if (!/[0-9]/.test(password)) {
                reqNumber.classList.remove('valid');
                isValid = false;
            } else {
                reqNumber.classList.add('valid');
            }
            
            if (!isValid) {
                passwordInput.classList.add('invalid');
                passwordError.style.display = 'block';
                return false;
            } else {
                passwordInput.classList.remove('invalid');
                passwordError.style.display = 'none';
                return true;
            }
        }
    }

    // Login Form Validation
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        const usernameInput = document.getElementById('loginUsername');
        const passwordInput = document.getElementById('loginPassword');
        const usernameError = document.getElementById('loginUserError');
        const passwordError = document.getElementById('loginPassError');

        loginForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate username
            if (usernameInput.value.trim() === '') {
                usernameInput.classList.add('invalid');
                usernameError.textContent = 'Username is required';
                usernameError.style.display = 'block';
                isValid = false;
            } else {
                usernameInput.classList.remove('invalid');
                usernameError.style.display = 'none';
            }
            
            // Validate password
            if (passwordInput.value === '') {
                passwordInput.classList.add('invalid');
                passwordError.textContent = 'Password is required';
                passwordError.style.display = 'block';
                isValid = false;
            } else {
                passwordInput.classList.remove('invalid');
                passwordError.style.display = 'none';
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});

// Toggle show/hide password for any field with .toggle-password
document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function () {
        const input = document.getElementById(this.dataset.target);
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        this.textContent = isHidden ? '🙈' : '👁️';
    });
});