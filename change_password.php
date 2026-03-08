<?php
require_once 'db.php';
require_once 'header.php';

$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_pass = md5($_POST['current_password']);
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $admin_id = $_SESSION['admin_id'];

    if ($new_pass !== $confirm_pass) {
        $msg = "<div class='badge inactive mb-4'>New passwords do not match!</div>";
    } else {
        $stmt = $conn->prepare("SELECT password FROM admin WHERE id = ?");
        $stmt->execute([$admin_id]);
        $row = $stmt->fetch();

        if ($current_pass !== $row['password']) {
            $msg = "<div class='badge inactive mb-4'>Current password is incorrect!</div>";
        } else {
            $new_hash = md5($new_pass);
            $conn->prepare("UPDATE admin SET password = ? WHERE id = ?")->execute([$new_hash, $admin_id]);
            $msg = "<div class='badge active mb-4'>Password changed successfully!</div>";
        }
    }
}
?>

<div class="flex justify-between align-center mb-4">
    <h2>Change Password</h2>
</div>

<?= $msg ?>

<div class="glass-panel" style="max-width:600px; margin:0 auto;">
    <p style="color:var(--text-muted);" class="mb-4">It is recommended to use at least 8 characters consisting of
        alphabets, numbers, and symbols.</p>
    <form method="POST">
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control" required placeholder="••••••••">
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" class="form-control" required minlength="6"
                placeholder="••••••••">
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required minlength="6"
                placeholder="••••••••">
        </div>
        <button type="submit" name="change_password" class="btn-neon w-100" style="width:100%;">
            <i data-feather="key"></i> Update Password
        </button>
    </form>
</div>

<?php require_once 'footer.php'; ?>