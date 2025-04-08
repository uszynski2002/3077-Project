<?php
session_start();
// Get product ID which is dynamically added to delete_product.php url 
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    $host = "localhost";
    $username = "uszynskm_2user";
    $password = "Admin3077";
    $dbname = "uszynskm_2";
    try {
     $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }   catch (PDOException $e) {
        die("connection failed: " . $e->getMessage());
    }

    // Prepare to delet product that has same id 
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the product was successfully deleted
    if ($stmt->rowCount() > 0) {
        //  back to admin page 
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: Product could not be deleted.";
    }
} else {
    // If no ID is provided, display an error
    echo "Error: No product ID provided.";
}
?>
