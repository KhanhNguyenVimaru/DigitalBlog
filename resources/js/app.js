import './bootstrap';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

// Helper to get CSRF token from meta or input
function getCsrfToken() {
    let token = document.querySelector('meta[name="csrf-token"]');
    if (token) return token.getAttribute('content');
    let input = document.querySelector('input[name="_token"]');
    if (input) return input.value;
    return '';
}

// AJAX Signup
const signupForm = document.getElementById('signupForm');
const signupSpinner = document.getElementById('signupSpinner');
if (signupForm) {
    signupForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(signupForm);
        if (signupSpinner) signupSpinner.style.display = 'flex';
        try {
            const response = await fetch('/handle_signup', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                },
                body: formData  
            });
            const data = await response.json();
            if (signupSpinner) signupSpinner.style.display = 'none';
            if (response.ok) {
                Swal.fire({icon: 'success', title: 'Registration successful!', text: 'A verification email has been sent. Please check your inbox to verify your account.'}).then(() => {
                    window.location.href = '/signup-success';
                });
            } else {
                let msg = data.message || data.error || 'Registration failed';
                Swal.fire({icon: 'error', title: 'Error', text: msg});
            }
        } catch (err) {
            if (signupSpinner) signupSpinner.style.display = 'none';
            Swal.fire({icon: 'error', title: 'Error', text: 'An error occurred, please try again.'});
        }
    });
}

// AJAX Login
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(loginForm);
        try {
            const response = await fetch('/handle_login', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                },
                body: formData
            });
            const data = await response.json();
            if (response.ok) {
                localStorage.setItem('token', data.access_token);
                localStorage.setItem('user', data.user);
                Swal.fire({icon: 'success', title: 'Login successful!', text: 'Welcome back ' + data.user}).then(() => {
                    window.location.href = '/';
                });
            } else {
                let msg = data.message || data.error || 'Login failed';
                Swal.fire({icon: 'error', title: 'Error', text: msg});
            }
        } catch (err) {
            Swal.fire({icon: 'error', title: 'Error', text: 'An error occurred, please try again.'});
        }
    });
}
