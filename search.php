<?php
require_once 'db.php';
require_once 'header.php';

$search_results = [];
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['q'])) {
    $q = "%" . $_GET['q'] . "%";
    $stmt = $conn->prepare("SELECT u.*, c.comp_name FROM users u LEFT JOIN computers c ON u.computer_id = c.id WHERE u.entry_id LIKE ? OR u.name LIKE ? OR u.mobile LIKE ? ORDER BY u.id DESC");
    $stmt->execute([$q, $q, $q]);
    $search_results = $stmt->fetchAll();
}
?>

<div class="flex justify-between align-center mb-4">
    <h2>Search Users</h2>
</div>

<div class="glass-panel" style="margin-bottom:2rem;">
    <form method="GET" class="flex" style="gap:1rem;">
        <input type="text" name="q" class="form-control" required
            placeholder="Search by Entry ID, Name or Mobile Number" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button type="submit" class="btn-neon"><i data-feather="search"></i> Search</button>
    </form>
</div>

<?php if (isset($_GET['q'])): ?>
    <div class="glass-panel">
        <h3 class="mb-4">Search Results for "
            <?= htmlspecialchars($_GET['q']) ?>"
        </h3>
        <?php if (empty($search_results)): ?>
            <p style="color:var(--danger); text-align:center;">No records found!</p>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Entry ID</th>
                            <th>Name/Mobile</th>
                            <th>Computer</th>
                            <th>In Time</th>
                            <th>Out Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($search_results as $row):
                            $status = $row['out_time'] ? "<span class='badge inactive'>Logged Out</span>" : "<span class='badge active'>Active</span>";
                            ?>
                            <tr>
                                <td><strong>
                                        <?= htmlspecialchars($row['entry_id']) ?>
                                    </strong></td>
                                <td>
                                    <?= htmlspecialchars($row['name']) ?><br><small style="color:var(--text-muted);">
                                        <?= htmlspecialchars($row['mobile']) ?>
                                    </small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['comp_name']) ?>
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
                                <td>
                                    <?= $status ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['remarks']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require_once 'footer.php'; ?>