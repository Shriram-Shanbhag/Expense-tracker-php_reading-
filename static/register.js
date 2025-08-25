// Example register.js
// Handle register form submission
const form = document.getElementById('registerForm');
if (form) {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        username: document.getElementById('username').value,
        password: document.getElementById('password').value
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.href = '/login.html';
      } else {
        document.getElementById('registerMessage').textContent = data.message || 'Registration failed.';
      }
    })
    .catch(() => {
      document.getElementById('registerMessage').textContent = 'Server error';
    });
  });
}
