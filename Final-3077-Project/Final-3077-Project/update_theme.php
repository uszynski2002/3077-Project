<?php
session_start();
//update theme by checking db chnage 
$host = "localhost";
$username = "uszynskm_2user";
$password = "Admin3077";
$dbname = "uszynskm_2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //select value of theme 

    $stmt = $pdo->query("SELECT setting_value FROM site_alts WHERE setting_key = 'theme'");
    $theme = $stmt->fetchColumn();
    //json encode for js file in nav.js 

    echo json_encode(["theme" => $theme]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Could not connect to database"]);
}
?>