// Example dashboard.js
// Fetch and display dashboard data from backend API
fetch('/api/dashboard')
  .then(response => response.json())
  .then(data => {
    // Populate dashboard elements with data
    document.getElementById('totalExpense').textContent = 'Rs' + data.total_expense.toFixed(2);
    // Add more DOM updates as needed
  })
  .catch(() => {
    document.getElementById('dashboardMessage').textContent = 'Failed to load dashboard data.';
  });
