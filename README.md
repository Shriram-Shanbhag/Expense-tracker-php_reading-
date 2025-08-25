
# Personal Finance Tracker (PHP Version)

A beautiful and comprehensive web application for tracking personal expenses with analytics and insights, built using PHP, CSS, and JavaScript.

## Features


- 🔐 **User Authentication**: Secure login and registration system (PHP sessions)
- 📊 **Dashboard**: Overview of current month's spending with key metrics
- ➕ **Add Expenses**: Easy expense entry with category selection
- 📈 **Analytics**: Interactive charts and spending insights (JS/Chart.js)
- 📅 **History**: Complete expense history for the last 12 months
- 📱 **Responsive Design**: Works perfectly on desktop and mobile devices (CSS)
- 💾 **SQLite Database**: No complex setup required
- 📤 **Data Export**: Export your expense data to CSV



### Installation

1. **Clone or download the project files**

2. **Set up your web server** (e.g., XAMPP, WAMP, or LAMP) and place the project folder in your web root (e.g., `htdocs` for XAMPP).

3. **Ensure PHP and SQLite are enabled** in your server configuration.

4. **Open your browser** and go to:
   ```
   http://localhost/3money%20management/index.php
   ```


### First Time Setup

1. Click "Register" to create your account
2. Login with your credentials
3. Start adding your expenses!

## Usage Guide

### Dashboard
- View current month's total spending
- See category breakdown with percentages
- Check recent transactions
- Quick access to all features

### Adding Expenses
- Click "Add Expense" or use the quick add buttons
- Select from predefined categories:
  - Food & Dining
  - Transportation
  - Shopping
  - Entertainment
  - Healthcare
  - Utilities
  - Housing
  - Education
  - Travel
  - Insurance
  - Investments
  - Gifts
  - Other

### Analytics
- **Category Breakdown**: Pie chart showing spending by category
- **Daily Trend**: Line chart of daily spending patterns
- **Monthly Comparison**: Bar chart comparing last 6 months
- **Spending Insights**: AI-powered recommendations

### History
- View all expenses from the last 12 months
- Grouped by month for easy navigation
- Monthly summaries with statistics
- Yearly overview


## Database

The application uses SQLite, which means:
- No database server required
- Data is stored locally in `finance.db`
- Automatic database creation on first run (handled by PHP)
- Easy backup (just copy the .db file)

personal-finance-tracker/

## File Structure

```
personal-finance-tracker/
├── index.php              # Main PHP entry point
├── finance.db             # SQLite database (created automatically)
├── static/                # CSS and JavaScript files
│   ├── styles.css         # Main stylesheet
│   ├── dashboard.js       # Dashboard interactivity
│   ├── analytics.js       # Analytics charts
│   └── ...                # Other JS/CSS files
└── templates/             # HTML/PHP templates
    ├── base.php           # Base template with navigation
    ├── login.php          # Login page
    ├── register.php       # Registration page
    ├── dashboard.php      # Main dashboard
    ├── add_expense.php    # Add expense form
    ├── history.php        # Expense history
    └── analytics.php      # Analytics and charts
```


## Security Features

- Password hashing using PHP's password_hash
- Session management with PHP sessions
- SQL injection protection (using prepared statements)

## Customization

### Adding New Categories
Edit the `add_expense.html` template and add new options to the category select dropdown.

### Changing Colors
Modify the CSS in `static/styles.css` to change the color scheme.

### Database Schema
The database has two main tables:
- `users`: User accounts and authentication
- `expenses`: Expense records with user relationships

## Troubleshooting

### Common Issues


1. **Web server not running**:
   - Make sure Apache or your local web server is started (e.g., XAMPP control panel)

2. **Database errors**:
   - Delete `finance.db` and reload the application

3. **PHP errors**:
   - Check your PHP error log for details

### Getting Help

If you encounter any issues:

1. Check that PHP 7.4+ is installed
2. Ensure your web server is running
3. Check the browser and PHP error logs for messages

## Features in Detail

### Smart Analytics
- Automatic spending pattern recognition
- Budget recommendations based on spending habits
- Visual charts for easy understanding
- Export functionality for external analysis

### User Experience
- Modern, responsive design
- Intuitive navigation
- Quick add buttons for common expenses
- Real-time data updates

### Data Management
- Secure user authentication
- Individual user data isolation
- Easy data export
- Automatic backup through SQLite

## Future Enhancements

Potential features for future versions:
- Budget setting and tracking
- Recurring expense management
- Income tracking
- Multiple currency support
- Mobile app version
- Cloud backup integration


## License

This project is open source and available under the MIT License.

---

**Happy Budgeting! 💰** 