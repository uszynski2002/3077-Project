<?php
session_start();

// admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    die("Access denied.");
}

$host = "localhost";
$username = "uszynskm_2user";
$password = "Admin3077";
$dbname = "uszynskm_2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("connection failed: " . $e->getMessage());
}

// submitted form for user activity 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['action'], $_POST['users']) || !is_array($_POST['users']) || count($_POST['users']) === 0) {
        die("No users selected ");
    }

    // status based on action
    $action = $_POST['action'];
    if ($action == 'disable') {
        $status = 'disabled';  
    } elseif ($action == 'activate') {
        $status = 'active';
    } else {
        die("invalid action");
    }

    // user IDs if more than 1 changed 
    $userIds = array_map('intval', $_POST['users']);  
    $placeholders = implode(',', array_fill(0, count($userIds), '?')); 

    // execute query
    $sql = "UPDATE users SET status = ? WHERE id IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge([$status], $userIds));

    // check if any rows were updated
    if ($stmt->rowCount() > 0) {
        header("Location: admin_dashboard.php");
    } else {
        echo "No changes made.";
    }
} else {
    die("Invalid request");
}
?>
