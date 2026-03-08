<?php
require_once 'db.php';
require_once 'header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $admin_id = $_SESSION['admin_id'];

    $stmt = $conn->prepare("UPDATE admin SET name = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$name, $email, $admin_id])) {
        $_SESSION['admin_name'] = $name;
        $msg = "<div class='badge active mb-4'>Profile details updated successfully!</div>";
    }
}

$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin_data = $stmt->fetch();
?>

<div class="flex justify-between align-center mb-4">
    <h2>Update Profile</h2>
</div>

<?= $msg ?>

<div class="glass-panel" style="max-width:600px; margin:0 auto;">
    <div style="text-align:center; margin-bottom:2rem;">
        <div
            style="width:100px; height:100px; border-radius:50%; background:var(--primary); display:flex; align-items:center; justify-content:center; font-size:3rem; font-weight:bold; margin:0 auto 1rem;">
            <?= substr($admin_data['name'], 0, 1) ?>
        </div>
        <h3 style="color:var(--text-main);">
            <?= htmlspecialchars($admin_data['username']) ?>
        </h3>
        <p style="color:var(--text-muted);">Administrator Account</p>
    </div>

    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required
                value="<?= htmlspecialchars($admin_data['name']) ?>">
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required
                value="<?= htmlspecialchars($admin_data['email']) ?>">
        </div>
        <div class="form-group">
            <label>Username (Non-editable)</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($admin_data['username']) ?>" readonly
                style="background:rgba(255,255,255,0.05); color:var(--text-muted);">
        </div>
        <button type="submit" name="update_profile" class="btn-neon w-100" style="width:100%;">
            <i data-feather="save"></i> Save Changes
        </button>
    </form>
</div>

<?php require_once 'footer.php'; ?>