<?php //if user is logged in, wiki buttons will appear on screen
session_start();
header('Content-Type: application/json');
echo json_encode(['userLoggedIn' => isset($_SESSION['username'])]);
?>