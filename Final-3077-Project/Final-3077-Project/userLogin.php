<?php
session_start();

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

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $inputUsername = trim($_POST['login_username']);
    $inputPassword = trim($_POST['login_password']);

    if (empty($inputUsername) || empty($inputPassword)) { // If fields are empty
        $errorMessage = "Both username and password are required.";
    }

    // Fetch user from DB
    $stmt = $pdo->prepare("SELECT id, username, password, is_admin, status FROM users WHERE username = :username");
    $stmt->bindValue(':username', $inputUsername);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If match found and password is correct
    if ($user && password_verify($inputPassword, $user['password'])) {
        if ($user['status'] === 'disabled') { // If user account is disabled
            $errorMessage = "Your account is disabled. Please contact support.";
        } else {
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];


            // Redirect to page based on role
            if ($user['is_admin'] == 1) {
                header("Location: admin_dashboard.php"); // Admin users
            } else {
                header("Location: Homepage.html"); // Regular users
            }
            exit();
        }
    } else {
        //  user doesn't exist
        $errorMessage = "Incorrect username or password. Please try again.";
    }
}
?>