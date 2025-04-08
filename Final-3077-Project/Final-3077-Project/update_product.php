<?php
session_start(); //if admin continue 
if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    die("Non Admin.");
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
//product infor from edit page 
$product_id = $_POST['product_id'];
$name = $_POST['name'];
$category = $_POST['category'];
$description = $_POST['description'];
$url = $_POST['productPageURL'];

// Update product
$stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, description = ?, productPageURL = ? WHERE id = ?");
$stmt->execute([$name, $category, $description, $url, $product_id]);

// Update sizes
if (!empty($_POST['sizes'])) {
    foreach ($_POST['sizes'] as $size) {
        $stmt = $pdo->prepare("UPDATE product_sizes SET size = ?, price = ?, image = ?, imageAlt = ? WHERE id = ?");
        $stmt->execute([
            $size['size'],
            $size['price'],
            $size['image'],
            $size['imageAlt'],
            $size['id']
        ]);
    }
}

header("Location: admin_dashboard.php");
exit();
?>
