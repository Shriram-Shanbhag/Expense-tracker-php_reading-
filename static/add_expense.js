// Example add_expense.js
// Handle add expense form submission
const form = document.getElementById('addExpenseForm');
if (form) {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('/api/add_expense', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        amount: document.getElementById('amount').value,
        category: document.getElementById('category').value,
        description: document.getElementById('description').value,
        date: document.getElementById('date').value
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.href = '/dashboard.html';
      } else {
        document.getElementById('addExpenseMessage').textContent = data.message || 'Failed to add expense.';
      }
    })
    .catch(() => {
      document.getElementById('addExpenseMessage').textContent = 'Server error';
    });
  });
}
