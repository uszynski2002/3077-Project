<?php
session_start();

$admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null; // null if no username is set
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Madison Uszynski">
        <meta name="description" content="nav for The CoffeeSetUp">
        <meta name="keywords" content="nav, coffeesetup">
        <link rel="stylesheet" href="product.css">
        <title>Nav</title>
    </head>
    <body>
        <!-- Greeting for logged-in user or guest -->
        <h2>
        <?php
            if ($username) {
                echo "Hello, " . htmlspecialchars($username) . "!";
            } else {
                echo "Hello, Guest!";
            }
        ?>
        </h2>
        <nav>
            <a href="Homepage.html">
                <img class="nav" src="images/Logo.png" alt="Logo">
            </a>
            <a class="nav" href="aboutpage.html">About</a>
            <a class="nav" href="userlogin.html">Create Account</a>
            <?php if ($admin): ?>
            <!-- Admin users have Status page instead of the tea and coffee page -->
            <a class="nav" href="status.php">Status</a>
            <?php else: ?>
            <!-- Regular users  -->
            <a class="nav" href="Product2.html">Tea</a>
            <a class="nav" href="Product1.html">Coffee</a>
            <?php endif; ?>
            <?php if ($admin): ?>
                <a class="nav" href="admin_dashboard.php" id="admin-link">Admin</a><!--admin page-->
            <?php endif; ?>
            <?php if ($username): ?>
                <a class="nav" href="logout.php">Log Out</a><!--logout page-->
            <?php endif; ?>
            <a href="ShoppingCart.html">
                <img class="nav" src="images/checkout.png" alt="Checkout">
            </a>
        </nav>
    </body>
</html>
