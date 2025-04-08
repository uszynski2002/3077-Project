<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['is_admin'] != 1) {
    die("Access denied.");
}// if admin show page 

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

// fetch user info
$stmt = $pdo->query("
    SELECT id,username, status 
    FROM users
    WHERE is_admin = 0
    ORDER BY username
");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// fetch products and sizes
$stmt = $pdo->query("
    SELECT id, name, description, productPageURL
    FROM products
    ORDER BY id
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt_sizes = $pdo->query("
    SELECT product_id, size, price, image, imageAlt
    FROM product_sizes
");
$sizes = $stmt_sizes->fetchAll(PDO::FETCH_ASSOC);

// group sizes, prices, and images by product_id
$product_sizes = [];
$product_prices = [];
$product_images = [];
$product_images_alt = [];
foreach ($sizes as $size) {
    $product_sizes[$size['product_id']][] = $size['size'];
    $product_prices[$size['product_id']][] = $size['price'];
    $product_images[$size['product_id']][] = $size['image'];
    $product_images_alt[$size['product_id']][] = $size['imageAlt'];
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Madison Uszynski">
        <meta name="description" content="Admin Dashboard">
        <meta name="keywords" content="Admin,Dashboard,Page">
        <link rel="stylesheet" href="product.css">
        <title>Admin Dashboard</title>
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /> <!--icon-->
    </head>
    <body>
        <div class="background">
            <div id="navbar-container"></div>
            <div class="content-container">
                <div class="title-container">
                    <h1>Admin Dashboard</h1>
                </div>
                <h2 class="admin">Change User Status</h2> <!--change user -->
                <form action="update_users.php" method="POST"><!--users updated here -->
                    <?php foreach ($users as $user): ?><!--display all non admin users  -->
                        <label>
                            <input type="checkbox" class="check" name="users[]" value="<?php echo $user['id']; ?>"><!--select and sends user id-->
                            <?php echo htmlspecialchars($user['username']) . " - " . htmlspecialchars($user['status']); ?><!-- display username and status-->
                        </label>
                    <?php endforeach; ?>
                    <div class="select">
                        <select name="action"><!--select action for status, send to update_user.php -->
                            <option value="disable">Disable Selected</option>
                            <option value="activate">Activate Selected</option>
                        </select>
                    </div>
                    <button type="submit">Apply</button><!--submit -->
                </form>
                <div class="container_admin">
                    <h2 class="admin">Change Website Theme</h2><!-- change theme-->
                    <div class="button-group">
                        <form method="POST" action="admin_theme.php" class="button-group">
                            <button type="submit" name="theme" value="valentines" class="admin">Valentine Theme</button>
                            <button type="submit" name="theme" value="winter" class="admin">Winter Theme</button>
                            <button type="submit" name="theme" value="default" class="admin">Default Theme</button>
                        </form>
                    </div>
                </div>
                <div class="container_admin">
                    <h2 class="admin">Edit Products</h2><!-- edit products-->
                    <table>
                        <thead>
                            <tr>
                                <th colspan="4">Select a Product to Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?><!-- for each product loop -->
                            <tr>
                                <td class="admin_product" colspan="4"><?php echo htmlspecialchars($product['name']); ?></td><!-- display product name huge-->
                            </tr>
                            <tr>
                                <td colspan="1">
                                <strong>Product ID</strong><!-- id column -->
                                </td>
                                <td colspan="1">
                                <strong>Description</strong><!-- description column -->
                                </td>
                                <td colspan="1">
                                <strong>URL</strong><!-- url column -->
                                </td>
                                <td colspan="1">
                                <strong>Actions</strong><!-- buttons column-->
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo htmlspecialchars($product['id']); ?></td><!-- id number-->
                                <td><?php echo htmlspecialchars($product['description']); ?></td><!-- description-->
                                <td><?php echo htmlspecialchars($product['productPageURL']); ?></td><!--url -->
                                <td>
                                    <button class="admin" onclick="window.location.href='edit_product.php?id=<?php echo $product['id']; ?>'">Edit</button><!--edit php -->
                                    <button class="admin" onclick="window.location.href='delete_product.php?id=<?php echo $product['id']; ?>'">Delete</button><!--delete product php -->
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1">
                                <strong>Sizes</strong><!-- sizes column-->
                                </td>
                                <td colspan="1">
                                <strong>Price</strong><!-- prices column-->
                                </td>
                                <td colspan="1">
                                <strong>Image</strong><!-- image column-->
                                </td>
                                <td colspan="1">
                                <strong>Alt Text</strong><!-- alt text column-->
                                </td>
                            </tr>
                            <?php if (!empty($product_sizes[$product['id']])): ?><!-- if values -->
                                <?php foreach ($product_sizes[$product['id']] as $index => $size): ?><!-- for each value -->
                            <tr>
                                <td colspan="1"><?php echo htmlspecialchars($size); ?></td><!-- display size-->
                                <td colspan="1">$
                                <?php echo htmlspecialchars($product_prices[$product['id']][$index]); ?><!--price -->
                                </td>
                                <td colspan="1">
                                <img class="admin_i" src="<?php echo htmlspecialchars($product_images[$product['id']][$index]); ?>"><!-- image-->
                                </td>
                                <td colspan="1">
                                    <?php echo htmlspecialchars($product_images_alt[$product['id']][$index]); ?><!-- alt text-->
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4">No sizes available</td>
                            </tr>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <div id="footer"></div>
        </div>
        <script src="nav.js"></script> 
        <script>
            // help wiki button for form
            const requestFormHelp = document.createElement('div');
            requestFormHelp.classList.add('wiki'); // class
            requestFormHelp.textContent = 'Admin Wiki'; 

            // to help page
            requestFormHelp.onclick = function() {
                window.location.href = 'admin_wiki.html'; 
            };
            const contentContainer = document.querySelector('.content-container'); 
            contentContainer.appendChild(requestFormHelp);
        </script>
    </body>
</html>