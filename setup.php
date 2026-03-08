<?php
require_once 'db.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['setup'])) {
    try {
        // Admin table
        $conn->exec("CREATE TABLE IF NOT EXISTS admin (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            username TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        )");

        // Computers table
        $conn->exec("CREATE TABLE IF NOT EXISTS computers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            comp_name TEXT NOT NULL UNIQUE,
            ip_address TEXT,
            status TEXT DEFAULT 'Active'
        )");

        // Users table
        $conn->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            entry_id TEXT NOT NULL UNIQUE,
            name TEXT NOT NULL,
            email TEXT,
            mobile TEXT,
            address TEXT,
            computer_id INTEGER,
            in_time DATETIME DEFAULT CURRENT_TIMESTAMP,
            out_time DATETIME,
            price REAL DEFAULT 0,
            remarks TEXT,
            FOREIGN KEY(computer_id) REFERENCES computers(id)
        )");

        // Insert Default Admin
        $stmt = $conn->prepare("SELECT COUNT(*) FROM admin");
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $password = md5('admin123');
            $conn->exec("INSERT INTO admin (name, username, email, password) VALUES ('System Admin', 'admin', 'admin@cafe.com', '$password')");
            $msg = "<div class='badge active mb-4'>Database created successfully! Default User: admin / admin123</div>";
        } else {
            $msg = "<div class='badge active mb-4'>Database already setup!</div>";
        }

    } catch (PDOException $e) {
        $msg = "<div class='badge inactive mb-4'>Error: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database | Cyber Café System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-wrapper glass-panel animate-fade-in"
    style="margin:2rem auto; width:90%; border:none; box-shadow:none;">
    <div class="auth-card glass-panel text-center">
        <h2 class="mb-4" style="color:var(--primary);">System Setup</h2>
        <p class="mb-4" style="color:var(--text-muted);">Initialize Cyber Café Management System database.</p>
        <?= $msg ?>
        <form method="POST">
            <button type="submit" name="setup" class="btn-neon w-100" style="width:100%;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
                Install Database
            </button>
        </form>
        <div class="mt-4">
            <a href="index.php">Go to Login</a>
        </div>
    </div>
</body>

</html>