<?php
session_start();
// Set content type to JSON
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "uszynskm_2user";
$password = "Admin3077";
$dbname = "uszynskm_2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Check if product name is in the URL
if (!isset($_GET['name'])) {
    echo json_encode(["error" => "No product name specified"]);
    exit;
}

$productName = $_GET['name'];

// product details from the database
$productStmt = $pdo->prepare("SELECT * FROM products WHERE name = :name");
$productStmt->bindValue(':name', $productName);
$productStmt->execute();
$product = $productStmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(["error" => "Product not found"]);
    exit;
}

// Fetch product sizes
$sizeStmt = $pdo->prepare("SELECT size, price, image, imageAlt FROM product_sizes WHERE product_id = :product_id");
$sizeStmt->bindValue(':product_id', $product['id']);
$sizeStmt->execute();
$sizes = $sizeStmt->fetchAll(PDO::FETCH_ASSOC);

// Add the sizes to the product data
$product['sizes'] = $sizes;
$product['defaultImage'] = $sizes[0]['image'];
$product['defaultImageAlt'] = $sizes[0]['imageAlt'];

// Return as JSON 
echo json_encode($product);
exit; 
?>
