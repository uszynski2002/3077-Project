<?php
    session_start();         
    $host = "localhost";
    $username = "uszynskm_2user";
    $password = "Admin3077";
    $dbname = "uszynskm_2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// if registration form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Grab inputs
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $isAdmin = isset($_POST['is_admin']) ? 1 : 0; 

    // If empty 
    if (empty($username) || empty($password)) {
        die("Both fields are required.");
    }
        
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); //hash for security 

    // Insert into user db
    $stmt = $pdo->prepare("INSERT INTO users (username, password, is_admin) VALUES (:username, :password, :is_admin)");

    // Bind values and execute query
    try {
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':is_admin', $isAdmin);
        $stmt->execute();

        // go to login page 
        header("Location: userlogin.html");
        exit(); 
    } catch (PDOException $e) {
        error_log("Error on user data: " . $e->getMessage());
        die("An error occurred. Try again.");
    }
}
?>

