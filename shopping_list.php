<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("location: signin.html");
    exit();
}

$user_id = $_SESSION['user_id'];

require_once("settings.php");
$conn = new mysqli($host, $user, $pswd, $dbnm);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch shopping lists for the user
$listSql = "SELECT *, (SELECT day FROM cos20031_day WHERE day_id = sl.day_id) AS day_name FROM cos20031_shopping_list sl WHERE user_id = ? ORDER BY day_id";
$listStmt = $conn->prepare($listSql);
$listStmt->bind_param("i", $user_id);
$listStmt->execute();
$listResult = $listStmt->get_result();
$shoppingLists = $listResult->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="topnav">
        <a class="active" href="website.html">Home</a>
        <a href="products.html">Products</a>
    </div>

    <header>
        <div class="navbar">
            <div class="logo">
                <h1><a href="website.html">Freshcart</a></h1>
            </div>
        </div>
    </header>

    <div class="cart-container">
        <h1>Your Shopping List</h1>
        <?php if (empty($shoppingLists)): ?>
            <p>Your shopping list is empty.</p>
        <?php else: ?>
            <?php foreach ($shoppingLists as $list): ?>
                <div class="shopping-list">
                    <h2>Shopping List for <?php echo htmlspecialchars($list['day_name']); ?></h2>
                    <?php
                    // Fetch items for the current list
                    $itemSql = "SELECT *, (SELECT product_name FROM cos20031_product WHERE product_id = si.product_id) AS product_name FROM cos20031_shopping_item si WHERE list_id = ?";
                    $itemStmt = $conn->prepare($itemSql);
                    $itemStmt->bind_param("i", $list['list_id']);
                    $itemStmt->execute();
                    $itemResult = $itemStmt->get_result();
                    $items = $itemResult->fetch_all(MYSQLI_ASSOC);
                    ?>

                    <?php if (empty($items)): ?>
                        <p>No items found in this list.</p>
                    <?php else: ?>
                        <ul class="shopping-items">
                            <?php foreach ($items as $item): ?>
                                <li>
                                    <p><strong>Item:</strong> <?php echo htmlspecialchars($item['product_name']); ?></p>
                                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['amount']); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Freshcart</p>
    </footer>
</body>
</html>
<?php
// Close database connection
$conn->close();
?>
