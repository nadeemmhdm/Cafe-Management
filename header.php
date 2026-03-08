<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Cyber Café System</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>

<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <div class="sidebar glass-nav" id="sidebar">
            <div style="display:flex; justify-content:space-between; align-items:center;" class="mb-4">
                <h3 style="color:var(--primary);">Cyber Café</h3>
                <button class="mobile-toggle" onclick="toggleSidebar()"><i data-feather="x"></i></button>
            </div>

            <nav style="display:flex; flex-direction:column; gap:0.5rem;">
                <a href="dashboard.php" class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
                    <i data-feather="grid"></i> Dashboard
                </a>
                <a href="computers.php" class="nav-link <?= $current_page == 'computers.php' ? 'active' : '' ?>">
                    <i data-feather="monitor"></i> Manage Computers
                </a>
                <a href="users.php" class="nav-link <?= $current_page == 'users.php' ? 'active' : '' ?>">
                    <i data-feather="users"></i> Manage Users
                </a>
                <a href="search.php" class="nav-link <?= $current_page == 'search.php' ? 'active' : '' ?>">
                    <i data-feather="search"></i> Search Users
                </a>
                <a href="report.php" class="nav-link <?= $current_page == 'report.php' ? 'active' : '' ?>">
                    <i data-feather="bar-chart-2"></i> User Reports
                </a>

                <h4 class="mt-4 mb-2" style="font-size:0.9rem; color:var(--text-muted); padding-left:1rem;">ADMIN
                    SETTINGS</h4>
                <a href="profile.php" class="nav-link <?= $current_page == 'profile.php' ? 'active' : '' ?>">
                    <i data-feather="user"></i> Profile Update
                </a>
                <a href="change_password.php"
                    class="nav-link <?= $current_page == 'change_password.php' ? 'active' : '' ?>">
                    <i data-feather="lock"></i> Change Password
                </a>
                <a href="logout.php" class="nav-link">
                    <i data-feather="log-out"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="main-content">
            <div class="glass-panel"
                style="margin-bottom:1.5rem; display:flex; justify-content:space-between; align-items:center; padding:1rem 1.5rem;">
                <button class="mobile-toggle" onclick="toggleSidebar()"><i data-feather="menu"></i></button>
                <div style="font-size:1.2rem; font-weight:600; color:var(--text-main);">Welcome,
                    <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?>
                </div>
                <div style="display:flex; align-items:center; gap:1rem;">
                    <div
                        style="width:40px; height:40px; border-radius:50%; background:var(--primary); display:flex; align-items:center; justify-content:center; font-weight:bold;">
                        <?= substr($_SESSION['admin_name'] ?? 'A', 0, 1) ?>
                    </div>
                </div>
            </div>
            <div class="animate-fade-in content-area">