<?php
session_start(); //send selected theme to db 

if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    die("Access denied.");//if admin ccontinue 
}

// DB connection
$host = "localhost";
$username = "uszynskm_2user";
$password = "Admin3077";
$dbname = "uszynskm_2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


// Update theme if an option 
if (isset($_POST['theme']) && in_array($_POST['theme'], ['default', 'valentines', 'winter'])) {
    $theme = $_POST['theme'];

    $stmt = $pdo->prepare("UPDATE site_alts SET setting_value = :theme WHERE setting_key = 'theme'"); 
    $stmt->bindValue(':theme', $theme);
    $stmt->execute();

    header("Location: admin_dashboard.php"); // Send back to dashboard
    exit();
} else {
    echo "Invalid theme selected.";
}
?>
