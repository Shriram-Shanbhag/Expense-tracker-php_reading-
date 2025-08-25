// Example logout.js
// Handle logout button click
const logoutBtn = document.getElementById('logoutBtn');
if (logoutBtn) {
  logoutBtn.addEventListener('click', function() {
    fetch('/api/logout', { method: 'POST' })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          window.location.href = '/login.html';
        } else {
          alert('Logout failed.');
        }
      })
      .catch(() => {
        alert('Server error');
      });
  });
}
