<?php
require_once 'db.php';
require_once 'header.php';

// Get counts
$stmt = $conn->query("SELECT COUNT(*) FROM computers");
$total_computers = $stmt->fetchColumn();

$stmt = $conn->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();

// Get users currently in cafe (out_time is null)
$stmt = $conn->query("SELECT COUNT(*) FROM users WHERE out_time IS NULL");
$active_users = $stmt->fetchColumn();

// Total earnings
$stmt = $conn->query("SELECT SUM(price) FROM users");
$total_earnings = $stmt->fetchColumn() ?? 0;

?>

<h2 class="mb-4">System Overview</h2>

<div class="stats-grid">
    <div class="stat-card glass-panel" style="border-left: 4px solid var(--primary);">
        <div class="stat-icon" style="color:var(--primary);"><i data-feather="monitor"></i></div>
        <div>
            <h3 style="font-size:2rem;">
                <?= $total_computers ?>
            </h3>
            <p style="color:var(--text-muted);">Total Computers</p>
        </div>
    </div>
    <div class="stat-card glass-panel" style="border-left: 4px solid var(--secondary);">
        <div class="stat-icon" style="color:var(--secondary);"><i data-feather="users"></i></div>
        <div>
            <h3 style="font-size:2rem;">
                <?= $total_users ?>
            </h3>
            <p style="color:var(--text-muted);">Total Users</p>
        </div>
    </div>
    <div class="stat-card glass-panel" style="border-left: 4px solid var(--accent);">
        <div class="stat-icon" style="color:var(--accent);"><i data-feather="activity"></i></div>
        <div>
            <h3 style="font-size:2rem;">
                <?= $active_users ?>
            </h3>
            <p style="color:var(--text-muted);">Active Users</p>
        </div>
    </div>
    <div class="stat-card glass-panel" style="border-left: 4px solid #f59e0b;">
        <div class="stat-icon" style="color:#f59e0b;"><i data-feather="dollar-sign"></i></div>
        <div>
            <h3 style="font-size:2rem;">₹
                <?= number_format($total_earnings, 2) ?>
            </h3>
            <p style="color:var(--text-muted);">Total Earnings</p>
        </div>
    </div>
</div>

<div class="glass-panel" style="margin-top:2rem;">
    <div class="flex justify-between align-center mb-4">
        <h3>Recent Users</h3>
        <a href="users.php" class="btn-neon" style="font-size:0.8rem; padding:0.4rem 1rem;">View All</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Entry ID</th>
                    <th>Name</th>
                    <th>Computer</th>
                    <th>In Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->query("SELECT u.entry_id, u.name, u.in_time, u.out_time, c.comp_name FROM users u LEFT JOIN computers c ON u.computer_id = c.id ORDER BY u.id DESC LIMIT 5");
                while ($row = $stmt->fetch()) {
                    $status = $row['out_time'] ? "<span class='badge inactive'>Logged Out</span>" : "<span class='badge active'>Active</span>";
                    echo "<tr>
                        <td>{$row['entry_id']}</td>
                        <td>{$row['name']}</td>
                        <td>" . ($row['comp_name'] ?? 'N/A') . "</td>
                        <td>{$row['in_time']}</td>
                        <td>{$status}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'footer.php'; ?>