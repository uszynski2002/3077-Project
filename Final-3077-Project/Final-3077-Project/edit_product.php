<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    die("Access denied."); //if admin open page 
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

$product_id = $_GET['id'] ?? null; //get id from url 
if (!$product_id) {
    die("No product ID.");
}

// Fetch product with same id
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

// Fetch sizes
$stmt_sizes = $pdo->prepare("SELECT * FROM product_sizes WHERE product_id = ?");
$stmt_sizes->execute([$product_id]);
$sizes = $stmt_sizes->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="author" content="Madison Uszynski">
    <meta name="description" content="edit product page">
    <meta name="keywords" content="edit, admin,page">
    <link rel="stylesheet" href="product.css">
    <title>Edit Product</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
  </head>
    <div class="background"> 
      <div id="navbar-container"></div> <!-- Nav html and java -->
      <div class="content-container">
        <div class="title-container"> 
          <h1>Edit Product: <?php echo htmlspecialchars($product['name']); ?></h1><!-- dynamic title with json info -->
        </div>
        <form action="update_product.php" method="POST"> <!-- form processed on update_product.php -->
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>"><!-- id  -->
            <label>Name: <input type="text" id="nameInput" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"></label><!-- new name -->
            <label>Category: <input type="text" id="categoryInput" name="category" value="<?php echo htmlspecialchars($product['category']); ?>"readonly></label><!--will reflect chnages made in name -->
            <label>Description: <input type="text" name="description" value="<?php echo htmlspecialchars($product['description']); ?>"></label><!-- description-->
            <label>URL: <input type="text" name="productPageURL" value="<?php echo htmlspecialchars($product['productPageURL']); ?>"readonly></label><!--url stays the same -->

            <h2>Sizes & Prices</h2><!--sizes -->
            <?php foreach ($sizes as $index => $size): ?>
                <div class="size-block">
                    <input type="hidden" name="sizes[<?php echo $index; ?>][id]" value="<?php echo $size['id']; ?>"><!--id -->
                    <label>Size: <input type="text" name="sizes[<?php echo $index; ?>][size]" value="<?php echo $size['size']; ?>"></label><!--sizes -->
                    <label>Price: <input type="text" name="sizes[<?php echo $index; ?>][price]" value="<?php echo $size['price']; ?>"></label><!--prices -->
                    <label>Image URL: <input type="text" name="sizes[<?php echo $index; ?>][image]" value="<?php echo $size['image']; ?>"></label><!--images (ONLY URL image will need to be added in images folder) -->
                    <label>Alt Text: <input type="text" name="sizes[<?php echo $index; ?>][imageAlt]" value="<?php echo $size['imageAlt']; ?>"></label><!-- alt text-->
                </div>
            <?php endforeach; ?>
            <button type="submit">Save Changes</button>
        </form>
      </div>
      <div id="footer"></div>
      <script src="product.js"></script> 
      <script src="nav.js"></script> 
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () { //category will be excatly the same as name 
     const nameInput = document.getElementById("nameInput");
        const categoryInput = document.getElementById("categoryInput");

        nameInput.addEventListener("input", function () {
        categoryInput.value = nameInput.value;
    });
  });
</script>
  </body>
</html>
