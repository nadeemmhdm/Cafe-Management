<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :u AND password = :p");
    $stmt->execute(['u' => $username, 'p' => $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Username or Password";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['forgot'])) {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT * FROM admin WHERE email = :e");
    $stmt->execute(['e' => $email]);
    if ($stmt->fetch()) {
        $temp_pass = rand(100000, 999999);
        $hashed = md5($temp_pass);
        $conn->prepare("UPDATE admin SET password = :p WHERE email = :e")->execute(['p' => $hashed, 'e' => $email]);
        // In real world, send email. Here we show it.
        $success = "Password Reset! New Password is: " . $temp_pass;
    } else {
        $error = "Email not found.";
    }
}

$mode = isset($_GET['action']) && $_GET['action'] == 'forgot' ? 'forgot' : 'login';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Cyber Café Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-wrapper animate-fade-in">
    <div class="auth-card glass-panel">
        <div class="text-center mb-4">
            <h2 style="color:var(--primary); font-size:2rem;">Cyber Café Sys.</h2>
            <p style="color:var(--text-muted);">
                <?= $mode == 'login' ? 'Admin Authentication' : 'Password Recovery' ?>
            </p>
        </div>

        <?php if ($error)
            echo "<div class='badge inactive mb-4 w-100 text-center' style='width:100%'>$error</div>"; ?>
        <?php if ($success)
            echo "<div class='badge active mb-4 w-100 text-center' style='width:100%'>$success</div>"; ?>

        <?php if ($mode == 'login'): ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="admin">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" name="login" class="btn-neon" style="width:100%;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                    </svg>
                    Secure Login
                </button>
            </form>
            <div class="text-center mt-4">
                <a href="?action=forgot" style="font-size:0.9rem;">Forgot Password?</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label>Registered Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="admin@cafe.com">
                </div>
                <button type="submit" name="forgot" class="btn-neon" style="width:100%;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
                    </svg>
                    Recover Account
                </button>
            </form>
            <div class="text-center mt-4">
                <a href="index.php" style="font-size:0.9rem;">Back to Login</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>