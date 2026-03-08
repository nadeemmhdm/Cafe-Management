<?php
$db_file = __DIR__ . '/cybercafe.sqlite';

try {
    $conn = new PDO("sqlite:" . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    if (basename($_SERVER['PHP_SELF']) !== 'setup.php') {
        die("Database Connection Error: " . $e->getMessage() . " <br><a href='setup.php'>Go to Setup Page</a>");
    }
}
?>