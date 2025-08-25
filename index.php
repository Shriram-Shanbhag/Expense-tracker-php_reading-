<?php
session_start();

// Database connection
function get_db() {
    $db = new PDO('sqlite:finance.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

// User authentication helpers
function is_logged_in() {
    return isset($_SESSION['user_id']);
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: ?page=login');
        exit();
    }
}
function current_username() {
    return $_SESSION['username'] ?? '';
}

// Routing
$page = $_GET['page'] ?? (is_logged_in() ? 'dashboard' : 'login');

switch ($page) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $db = get_db();
            $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: ?page=dashboard');
                exit();
            } else {
                $error = 'Invalid username or password';
            }
        }
        include 'templates/login.php';
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $db = get_db();
            $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = 'Username already exists';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $db->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)')->execute([$username, $hash]);
                $success = 'Registration successful! Please login.';
                header('Location: ?page=login&registered=1');
                exit();
            }
        }
        include 'templates/register.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: ?page=login');
        exit();
    case 'dashboard':
        require_login();
        $db = get_db();
        $user_id = $_SESSION['user_id'];
        $current_month = date('Y-m');
        // Total expense
        $stmt = $db->prepare('SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND date LIKE ?');
        $stmt->execute([$user_id, "$current_month%"]);
        $total_expense = $stmt->fetchColumn() ?: 0;
        // Category expenses
        $stmt = $db->prepare('SELECT category, SUM(amount) as total FROM expenses WHERE user_id = ? AND date LIKE ? GROUP BY category');
        $stmt->execute([$user_id, "$current_month%"]);
        $category_expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Recent expenses
        $stmt = $db->prepare('SELECT * FROM expenses WHERE user_id = ? ORDER BY date DESC, id DESC LIMIT 10');
        $stmt->execute([$user_id]);
        $recent_expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include 'templates/dashboard.php';
        break;
    case 'add_expense':
        require_login();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = floatval($_POST['amount'] ?? 0);
            $category = $_POST['category'] ?? '';
            $description = $_POST['description'] ?? '';
            $date = $_POST['date'] ?? date('Y-m-d');
            $db = get_db();
            $db->prepare('INSERT INTO expenses (user_id, amount, category, description, date) VALUES (?, ?, ?, ?, ?)')
                ->execute([$_SESSION['user_id'], $amount, $category, $description, $date]);
            $success = 'Expense added successfully!';
            header('Location: ?page=dashboard');
            exit();
        }
        include 'templates/add_expense.php';
        break;
    case 'history':
        require_login();
        $db = get_db();
        $user_id = $_SESSION['user_id'];
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-1 year'));
        $stmt = $db->prepare('SELECT * FROM expenses WHERE user_id = ? AND date BETWEEN ? AND ? ORDER BY date DESC');
        $stmt->execute([$user_id, $start_date, $end_date]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Group by month
        $monthly_expenses = [];
        $monthly_stats = [];
        $total_spent = 0;
        $total_transactions = 0;
        foreach ($expenses as $expense) {
            $month = substr($expense['date'], 0, 7);
            if (!isset($monthly_expenses[$month])) $monthly_expenses[$month] = [];
            $monthly_expenses[$month][] = $expense;
            $total_spent += $expense['amount'];
            $total_transactions++;
        }
        foreach ($monthly_expenses as $month => $exps) {
            $amounts = array_column($exps, 'amount');
            $categories = array_column($exps, 'category');
            $monthly_stats[$month] = [
                'total' => array_sum($amounts),
                'average_per_day' => count($amounts) ? array_sum($amounts) / 30 : 0,
                'largest' => count($amounts) ? max($amounts) : 0,
                'most_used_category' => count($categories) ? array_count_values($categories) : []
            ];
            if ($monthly_stats[$month]['most_used_category']) {
                arsort($monthly_stats[$month]['most_used_category']);
                $monthly_stats[$month]['most_used_category'] = array_key_first($monthly_stats[$month]['most_used_category']);
            } else {
                $monthly_stats[$month]['most_used_category'] = '';
            }
        }
        include 'templates/history.php';
        break;
    case 'analytics':
        require_login();
        $db = get_db();
        $user_id = $_SESSION['user_id'];
        $current_month = date('Y-m');
        // Category breakdown
        $stmt = $db->prepare('SELECT category, SUM(amount) as total, COUNT(*) as count FROM expenses WHERE user_id = ? AND date LIKE ? GROUP BY category ORDER BY total DESC');
        $stmt->execute([$user_id, "$current_month%"]);
        $category_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Daily spending
        $stmt = $db->prepare('SELECT date, SUM(amount) as total FROM expenses WHERE user_id = ? AND date LIKE ? GROUP BY date ORDER BY date');
        $stmt->execute([$user_id, "$current_month%"]);
        $daily_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Monthly comparison (last 6 months)
        $monthly_comparison = [];
        for ($i = 0; $i < 6; $i++) {
            $month_date = strtotime("-{$i} months");
            $month_str = date('Y-m', $month_date);
            $stmt = $db->prepare('SELECT SUM(amount) as total FROM expenses WHERE user_id = ? AND date LIKE ?');
            $stmt->execute([$user_id, "$month_str%"]);
            $total = $stmt->fetchColumn() ?: 0;
            $monthly_comparison[] = [
                'month' => date('F Y', $month_date),
                'total' => $total
            ];
        }
        $monthly_comparison = array_reverse($monthly_comparison);
        include 'templates/analytics.php';
        break;
    case 'export_csv':
        require_login();
        $db = get_db();
        $user_id = $_SESSION['user_id'];
        $stmt = $db->prepare('SELECT date, category, description, amount FROM expenses WHERE user_id = ? ORDER BY date DESC');
        $stmt->execute([$user_id]);
        $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="expenses.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Date', 'Category', 'Description', 'Amount']);
        foreach ($expenses as $row) {
            fputcsv($out, $row);
        }
        fclose($out);
        exit();
    default:
        header('Location: ?page=dashboard');
        exit();
} 