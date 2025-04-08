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
    $db_status = 'Online';
} catch (Exception $e) {
    $db_status = 'Offline';
}

//show status of db tables 
$table_statuses = [];

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);

    foreach ($tables as $table) {
        $tableName = $table[0];
        try {
            $check = $pdo->query("SELECT 1 FROM `$tableName` LIMIT 1");
            $table_statuses[$tableName] = 'Online';
        } catch (Exception $e) {
            $table_statuses[$tableName] = 'Offline';
        }
    }
} catch (Exception $e) {
    $table_statuses["Database Error"] = 'Offline';
}
$product_page_statuses = [];


try { //check product pages 
    $stmt = $pdo->query("SELECT name FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $name = urlencode($product['name']);
        $url = "https://uszynskm.myweb.cs.uwindsor.ca/Final-3077-Project/product.php?name=$name";

        // file_get_contents to check product page
        $response = @file_get_contents($url);
        
        if ($response !== false) {
            $product_page_statuses[$product['name']] = 'Online';
        } else {
            $product_page_statuses[$product['name']] = 'Offline';
        }
    }
} catch (Exception $e) {
    $product_page_statuses["Error loading product pages"] = 'Offline';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Madison Uszynski">
    <meta name="description" content="status page">
    <meta name="keywords" content="web, status, page">
    <link rel="stylesheet" href="product.css">
    <title>Status Monitor</title>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
</head>
<body>
    <div class="background"> 
      <div id="navbar-container"></div> <!-- Nav html and java -->
      <div class="content-container">
        <div class="title-container"> 
            <h1>Website & Services Status</h1>
        </div>
        <table class="status-table">
            <tr><th>Service</th><th>Status</th></tr><!-- titles -->
            <tr><td>Database</td><td class="<?= strtolower($db_status) ?>"><?= $db_status ?></td></tr><!-- database status  -->
            <tr><td>Main Website</td><td id="mainStatus">Checking...</td></tr><!-- main webpage status  -->
            <?php foreach ($table_statuses as $table => $status): ?><!-- for each table in db  -->
            <tr>
                <td><?= htmlspecialchars($table) ?></td><!-- name table  -->
                <td class="<?= strtolower($status) ?>"><?= $status ?></td><!-- status -->
            </tr>
            <?php endforeach; ?> 
            <tr><th colspan="2">Individual Product Pages</th></tr><!-- for each product page -->
            <?php foreach ($product_page_statuses as $product => $status): ?>
            <tr>
                <td><?= htmlspecialchars($product) ?></td><!--product name   -->
                <td class="<?= strtolower($status) ?>"><?= $status ?></td><!-- status -->
            </tr>
            <?php endforeach; ?>
            </table>
            <script>
            fetch("https://uszynskm.myweb.cs.uwindsor.ca/Final-3077-Project/Homepage.html") //if responisive, online
            .then(res => {
                if (res.ok) {
                    document.getElementById("mainStatus").textContent = "Online";
                    document.getElementById("mainStatus").className = "online";
                } else {
                    throw new Error("Offline");
                }
            })
            .catch(() => {
                document.getElementById("mainStatus").textContent = "Offline";
                document.getElementById("mainStatus").className = "offline";
            });
        </script>
    </div>
      <div id="footer"></div>
      <script src="nav.js"></script> 
    </div>
</body>
</html>
