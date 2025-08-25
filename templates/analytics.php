<?php $title = 'Analytics - Personal Finance Tracker'; $content_view = __FILE__; include __DIR__.'/base.php'; if (basename(__FILE__) !== basename($_SERVER['SCRIPT_FILENAME'])) return; ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-white fw-bold">
            <i class="fas fa-chart-bar me-2"></i>Analytics
        </h1>
        <p class="text-white-50">Detailed insights into your spending patterns</p>
    </div>
</div>
<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card expense-card">
            <div class="card-body text-center">
                <i class="fas fa-chart-pie fa-2x text-primary mb-2"></i>
                <h4 class="fw-bold"><?= count($category_data) ?></h4>
                <p class="text-muted mb-0">Categories Used</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card expense-card">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                <h4 class="fw-bold">Rs<?= number_format(array_sum(array_column($category_data, 'total')), 2) ?></h4>
                <p class="text-muted mb-0">Total This Month</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card expense-card">
            <div class="card-body text-center">
                <i class="fas fa-calendar-day fa-2x text-warning mb-2"></i>
                <h4 class="fw-bold"><?= count($daily_data) ?></h4>
                <p class="text-muted mb-0">Days with Expenses</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card expense-card">
            <div class="card-body text-center">
                <i class="fas fa-trending-up fa-2x text-info mb-2"></i>
                <h4 class="fw-bold">Rs<?= number_format((array_sum(array_column($category_data, 'total')) > 0 ? array_sum(array_column($category_data, 'total')) / 30 : 0), 2) ?></h4>
                <p class="text-muted mb-0">Daily Average</p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Category Breakdown Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Category Breakdown
                </h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <!-- Daily Spending Trend -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Daily Spending Trend
                </h5>
            </div>
            <div class="card-body">
                <canvas id="dailyChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Monthly Comparison -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Monthly Comparison (Last 6 Months)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Detailed Statistics -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Category Details
                </h5>
            </div>
            <div class="card-body">
                <?php if ($category_data): ?>
                    <?php foreach ($category_data as $category): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                        <div>
                            <div class="fw-bold"><?= htmlspecialchars($category['category']) ?></div>
                            <small class="text-muted"><?= $category['count'] ?> transactions</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">Rs<?= number_format($category['total'], 2) ?></div>
                            <small class="text-muted">
                                <?= number_format(array_sum(array_column($category_data, 'total')) > 0 ? ($category['total'] / array_sum(array_column($category_data, 'total'))) * 100 : 0, 1) ?>%
                            </small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Spending Insights
                </h5>
            </div>
            <div class="card-body">
                <?php if ($category_data): ?>
                    <?php
                    $total_spent = array_sum(array_column($category_data, 'total'));
                    $top_category = $category_data[0] ?? null;
                    $avg_per_day = $total_spent / 30;
                    $max_day = null;
                    if ($daily_data) {
                        $max_day = $daily_data[0];
                        foreach ($daily_data as $day) {
                            if ($day['total'] > $max_day['total']) $max_day = $day;
                        }
                    }
                    ?>
                    <div class="mb-3">
                        <h6 class="text-muted">Top Spending Category</h6>
                        <p class="fw-bold"><?= $top_category ? htmlspecialchars($top_category['category']) . ' - Rs' . number_format($top_category['total'], 2) : 'N/A' ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Average Daily Spending</h6>
                        <p class="fw-bold">Rs<?= number_format($avg_per_day, 2) ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Most Active Day</h6>
                        <p class="fw-bold"><?= $max_day ? htmlspecialchars($max_day['date']) . ' - Rs' . number_format($max_day['total'], 2) : 'No data available' ?></p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Budget Status</h6>
                        <?php if ($avg_per_day > 50): ?>
                            <p class="fw-bold text-warning">High daily average - Consider reducing expenses</p>
                        <?php elseif ($avg_per_day > 30): ?>
                            <p class="fw-bold text-info">Moderate spending - Monitor closely</p>
                        <?php else: ?>
                            <p class="fw-bold text-success">Good spending control - Keep it up!</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center">No data available for insights</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
// Category Breakdown Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($category_data, 'category')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($category_data, 'total')) ?>,
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
// Daily Spending Chart
const dailyCtx = document.getElementById('dailyChart').getContext('2d');
const dailyChart = new Chart(dailyCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($daily_data, 'date')) ?>,
        datasets: [{
            label: 'Daily Spending (Rs)',
            data: <?= json_encode(array_column($daily_data, 'total')) ?>,
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rs' + value; } } } }
    }
});
// Monthly Comparison Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($monthly_comparison, 'month')) ?>,
        datasets: [{
            label: 'Monthly Spending (Rs)',
            data: <?= json_encode(array_column($monthly_comparison, 'total')) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: '#36A2EB',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rs' + value; } } } }
    }
});
</script> 