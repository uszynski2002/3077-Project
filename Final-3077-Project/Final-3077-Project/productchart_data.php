<?php
session_start();
$host = "localhost";
$username = "uszynskm_2user";
$password = "Admin3077";
$dbname = "uszynskm_2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get products
    $productStmt = $pdo->query("SELECT id, name FROM products ORDER BY name ASC");

    $data = [];

    while ($product = $productStmt->fetch(PDO::FETCH_ASSOC)) {
        // Get sizes for each product
        $sizeStmt = $pdo->prepare("SELECT price FROM product_sizes WHERE product_id = :product_id");
        $sizeStmt->bindValue(':product_id', $product['id'], PDO::PARAM_INT);
        $sizeStmt->execute();
        $sizes = $sizeStmt->fetchAll(PDO::FETCH_ASSOC);

        // Loop through each size and add to chart data
        foreach ($sizes as $index => $size) {
            $label = $product['name'] . ' (Size ' . ($index + 1) . ')';
            $data[] = [$label, floatval($size['price'])];
        }
    }

    echo json_encode($data);

} catch (PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
}
?>
