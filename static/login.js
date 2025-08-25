document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Example: send data to backend API (replace URL with your backend endpoint)
    fetch('/api/login', {
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
            window.location.href = '/dashboard.html';
        } else {
            document.getElementById('loginMessage').textContent = data.message || 'Login failed';
        }
    })
    .catch(() => {
        document.getElementById('loginMessage').textContent = 'Server error';
    });
});
