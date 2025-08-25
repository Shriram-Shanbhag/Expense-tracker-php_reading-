// Example analytics.js
// Fetch and display analytics data from backend API
fetch('/api/analytics')
  .then(response => response.json())
  .then(data => {
    // Populate analytics elements with data
    document.getElementById('categoryCount').textContent = data.category_count;
    // Add more DOM updates as needed
  })
  .catch(() => {
    document.getElementById('analyticsMessage').textContent = 'Failed to load analytics data.';
  });
