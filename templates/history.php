<?php $title = 'Expense History - Personal Finance Tracker'; $content_view = __FILE__; include __DIR__.'/base.php'; if (basename(__FILE__) !== basename($_SERVER['SCRIPT_FILENAME'])) return; ?>
<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-white fw-bold">
            <i class="fas fa-history me-2"></i>Expense History
        </h1>
        <p class="text-white-50">View your expenses for the last 12 months</p>
    </div>
</div>
<?php if ($monthly_expenses): ?>
    <?php foreach ($monthly_expenses as $month => $expenses): ?>
    <div class="card mb-4">
        <div class="card-header bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <?= htmlspecialchars($month) ?>
                </h5>
                <div class="text-end">
                    <span class="badge bg-primary fs-6">
                        Rs<?= number_format($monthly_stats[$month]['total'], 2) ?>
                    </span>
                    <span class="badge bg-secondary ms-2"><?= count($expenses) ?> expenses</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td>
                                <i class="fas fa-calendar-day me-2 text-muted"></i>
                                <?= htmlspecialchars($expense['date']) ?>
                            </td>
                            <td>
                                <span class="category-badge bg-primary text-white">
                                    <?= htmlspecialchars($expense['category']) ?>
                                </span>
                            </td>
                            <td>
                                <?= $expense['description'] ? htmlspecialchars($expense['description']) : '<span class="text-muted">No description</span>' ?>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-danger">
                                    Rs<?= number_format($expense['amount'], 2) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Monthly Summary -->
            <div class="row mt-3 pt-3 border-top">
                <div class="col-md-6">
                    <h6 class="text-muted">Category Breakdown:</h6>
                    <?php
                    $category_totals = [];
                    foreach ($expenses as $expense) {
                        if (!isset($category_totals[$expense['category']])) {
                            $category_totals[$expense['category']] = 0;
                        }
                        $category_totals[$expense['category']] += $expense['amount'];
                    }
                    ?>
                    <?php foreach ($category_totals as $category => $total): ?>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted"><?= htmlspecialchars($category) ?></small>
                        <small class="fw-bold">Rs<?= number_format($total, 2) ?></small>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Monthly Statistics:</h6>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Average per day:</small>
                        <small class="fw-bold">Rs<?= number_format($monthly_stats[$month]['average_per_day'], 2) ?></small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Largest expense:</small>
                        <small class="fw-bold">Rs<?= number_format($monthly_stats[$month]['largest'], 2) ?></small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Most used category:</small>
                        <small class="fw-bold"><?= htmlspecialchars($monthly_stats[$month]['most_used_category']) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No Expense History</h4>
            <p class="text-muted">You haven't recorded any expenses yet.</p>
            <a href="?page=add_expense" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Your First Expense
            </a>
        </div>
    </div>
<?php endif; ?>
<!-- Summary Card -->
<?php if ($monthly_expenses): ?>
<div class="card mt-4">
    <div class="card-header bg-transparent border-0">
        <h5 class="mb-0">
            <i class="fas fa-chart-line me-2"></i>Yearly Summary
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="text-center">
                    <h4 class="fw-bold text-primary">
                        Rs<?= number_format($total_spent, 2) ?>
                    </h4>
                    <small class="text-muted">Total Spent</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="text-center">
                    <h4 class="fw-bold text-success">
                        <?= count($monthly_expenses) ?>
                    </h4>
                    <small class="text-muted">Months with Expenses</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="text-center">
                    <h4 class="fw-bold text-warning">
                        <?= $total_transactions ?>
                    </h4>
                    <small class="text-muted">Total Transactions</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="text-center">
                    <h4 class="fw-bold text-info">
                        Rs<?= number_format($total_spent / 365, 2) ?>
                    </h4>
                    <small class="text-muted">Daily Average</small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?> 