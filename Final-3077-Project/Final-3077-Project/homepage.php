<?php
header('Content-Type: application/json');
session_start();

$host = "localhost";
$username = "uszynskm_2user";
$password = "Admin3077";
$dbname = "uszynskm_2";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

try {
    // Combined query with JOIN
    $stmt = $pdo->query("
        SELECT 
            p.id AS product_id,
            p.name,
            p.description,
            p.productPageURL,
            s.size,
            s.price,
            s.image,
            s.imageAlt
        FROM products p
        LEFT JOIN product_sizes s ON p.id = s.product_id
        ORDER BY p.id
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group sizes under each product
    $productData = [];
    foreach ($rows as $row) {
        $id = $row['product_id'];
        if (!isset($productData[$id])) {
            $productData[$id] = [
                'id' => $id,
                'name' => $row['name'],
                'productPageURL' => $row['productPageURL'],
                'sizes' => []
            ];
        }
        if ($row['size'] !== null) {
            $productData[$id]['sizes'][] = [
                'size' => $row['size'],
                'image' => $row['image'],
                'imageAlt' => $row['imageAlt']
            ];
        }
    }

    // removes gaps in array 
    $productData = array_values($productData);

    echo json_encode([
        'products' => $productData
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
