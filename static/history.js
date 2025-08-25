// Example history.js
// Fetch and display history data from backend API
fetch('/api/history')
  .then(response => response.json())
  .then(data => {
    // Populate history elements with data
    document.getElementById('totalSpent').textContent = 'Rs' + data.total_spent.toFixed(2);
    // Add more DOM updates as needed
  })
  .catch(() => {
    document.getElementById('historyMessage').textContent = 'Failed to load history data.';
  });
