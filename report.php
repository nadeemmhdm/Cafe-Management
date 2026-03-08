<?php
require_once 'db.php';
require_once 'header.php';

$report_data = [];
$total_income = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate'])) {
    $from_date = $_POST['from_date'] . ' 00:00:00';
    $to_date = $_POST['to_date'] . ' 23:59:59';

    $stmt = $conn->prepare("SELECT u.*, c.comp_name FROM users u LEFT JOIN computers c ON u.computer_id = c.id WHERE u.in_time BETWEEN ? AND ? ORDER BY u.in_time DESC");
    $stmt->execute([$from_date, $to_date]);
    $report_data = $stmt->fetchAll();
}
?>

<div class="flex justify-between align-center mb-4">
    <h2>Usage Report</h2>
</div>

<div class="glass-panel" style="margin-bottom:2rem;">
    <h3 class="mb-4">Generate Report by Date</h3>
    <form method="POST" class="flex align-center" style="gap:1rem; flex-wrap:wrap;">
        <div class="form-group" style="flex:1; margin-bottom:0;">
            <label>From Date</label>
            <input type="date" name="from_date" class="form-control" required
                value="<?= $_POST['from_date'] ?? date('Y-m-01') ?>">
        </div>
        <div class="form-group" style="flex:1; margin-bottom:0;">
            <label>To Date</label>
            <input type="date" name="to_date" class="form-control" required
                value="<?= $_POST['to_date'] ?? date('Y-m-t') ?>">
        </div>
        <button type="submit" name="generate" class="btn-neon" style="margin-top:1.5rem;"><i data-feather="filter"></i>
            Generate</button>
    </form>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <div class="glass-panel">
        <h3 class="mb-4">Report Details</h3>
        <?php if (empty($report_data)): ?>
            <p style="color:var(--danger); text-align:center;">No data found for this period.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Entry ID</th>
                            <th>Name</th>
                            <th>Computer</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Price Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_data as $row):
                            $total_income += $row['price'];
                            ?>
                            <tr>
                                <td><strong>
                                        <?= htmlspecialchars($row['entry_id']) ?>
                                    </strong></td>
                                <td>
                                    <?= htmlspecialchars($row['name']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['comp_name'] ?? 'N/A') ?>
                                </td>
                                <td>
                                    <?= $row['in_time'] ?>
                                </td>
                                <td>
                                    <?= $row['out_time'] ?? '-' ?>
                                </td>
                                <td>₹
                                    <?= number_format($row['price'] ?? 0, 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" style="text-align:right;">Total Revenue Built:</th>
                            <th style="font-size:1.2rem; color:var(--accent);">₹
                                <?= number_format($total_income, 2) ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>