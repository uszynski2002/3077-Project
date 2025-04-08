<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    die("Access denied."); //if admin 
}

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
//admin user form 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['users'])) {
    $action = $_POST['action'];
    $userIds = implode(',', array_map('intval', $_POST['users'])); 
//if user is now disabled
    if ($action == "disable") {
        $pdo->query("UPDATE users SET status = 'disabled' WHERE id IN ($userIds)");
    } elseif ($action == "activate") { //if activated 
        $pdo->query("UPDATE users SET status = 'active' WHERE id IN ($userIds)");
    }
}
header("Location: admin_dashboard.php");
exit();
?>