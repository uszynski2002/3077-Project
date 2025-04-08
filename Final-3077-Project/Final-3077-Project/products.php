<?php //json products to database, load if you delete a product and want it back in the db 
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

// Read JSON file
$jsonFile = "products.json";  
$jsonData = file_get_contents($jsonFile);

// PHP error log
error_log(print_r($jsonData, true));

// if error
if (!$jsonData) {
    die("No valid JSON");
}

// Decode JSON data
$data = json_decode($jsonData, true);
if (!$data) {
    die("Invalid JSON data");
}

// SQL insert statement
$productStmt = $pdo->prepare("INSERT INTO products (name, category, description, productPageURL) VALUES (:name, :category, :description, :productPageURL)");
$sizeStmt = $pdo->prepare("INSERT INTO product_sizes (product_id, size, price, image, imageAlt) VALUES (:product_id, :size, :price, :image, :imageAlt)");

// Loop each product
foreach ($data['products'] as $entry) {
    // If keys exist 
    if (isset($entry['name']) && isset($entry['category']) && isset($entry['description']) && isset($entry['productPageURL'])) {
        
        // Check if product already in db by productPageURL
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE productPageURL = :productPageURL");
        $checkStmt->bindValue(':productPageURL', $entry['productPageURL']);
        $checkStmt->execute();
        $productExists = $checkStmt->fetchColumn();

        // Only insert if new
        if ($productExists == 0) {
            // Insert into the `products` table
            $productStmt->bindValue(':name', $entry['name']);
            $productStmt->bindValue(':category', $entry['category']);
            $productStmt->bindValue(':description', $entry['description']);
            $productStmt->bindValue(':productPageURL', $entry['productPageURL']);
            
            try { //size and image table 
                if ($productStmt->execute()) {
                    // Get product ID
                    $product_id = $pdo->lastInsertId();

                    // loop sizes insert into `product_sizes`
                foreach ($entry['sizes'] as $index => $size) {
                // Check for size and price 
                    if (isset($size['size']) && isset($size['price'])) {

                        // image and imageAlt for certain sizes
                        $image = null;
                        $imageAlt = null;

                        // First size option = image1 and image1Alt
                        if (isset($entry['image1']) && isset($entry['image1Alt']) && $index === 0) {
                            $image = $entry['image1'];
                            $imageAlt = $entry['image1Alt'];
                        }
                        // Other size option = image2 and image2Alt 
                        elseif (isset($entry['image2']) && isset($entry['image2Alt']) && $index === 1) {
                            $image = $entry['image2'];
                            $imageAlt = $entry['image2Alt'];
                        }

                        // Skip if no image and imageAlt
                        if (!$image || !$imageAlt) {
                            continue;
                        }

                        // Insert size data into product_sizes db  
                        $sizeStmt->bindValue(':product_id', $product_id);
                        $sizeStmt->bindValue(':size', $size['size']);
                        $sizeStmt->bindValue(':price', $size['price']);
                        $sizeStmt->bindValue(':image', $image);  
                        $sizeStmt->bindValue(':imageAlt', $imageAlt);  

                        try {
                            if (!$sizeStmt->execute()) {
                            error_log("Error size data: " . implode(", ", $sizeStmt->errorInfo()));
                            }
                            } catch (PDOException $e) {
                                error_log("PDO Exception: " . $e->getMessage());
                            }
                        }
                    }
                    
                } else {
                    error_log("Error on product insert: " . implode(", ", $productStmt->errorInfo()));
                }
            } catch (PDOException $e) {
                error_log("PDO Exception: " . $e->getMessage());
            }
        } else {
            error_log("Product already exists: " . $entry['productPageURL']);
        }
    }
}

echo "Data successfully stored.";
?>
