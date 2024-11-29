<?php
    require_once("settings.php");

    // Connect to database and get session information
    $conn = new mysqli($host, $user, $pswd, $dbnm) or die("Failed to connect to server: " . mysqli_connect_error());

    // Get list of products
    $query = "SELECT p.product_id, p.product_name, p.product_price, p.image, p.subcategory_id, sc.subcategory_name
            FROM cos20031_product p
            JOIN cos20031_subcategory sc ON p.subcategory_id = sc.subcategory_id
            WHERE p.category_id = 2;";

    $queryResult = $conn->query($query);
    $products = $queryResult->fetch_all(MYSQLI_ASSOC);

    $groupedProducts = [];
    foreach ($products as $product) {
        $groupedProducts[$product['subcategory_name']][] = $product;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="Document" content="width=device-width, initial-scale=1.0">
    <title>Freshcart - Products</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="topnav">
        <a href="website.html">Home</a>
        <a class="active" href="products.html">Products</a>
 
    </div>

    <header>
        <div class="navbar">
            <div class="logo">
                <h1><a href="website.html">Freshcart</a></h1>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Search for products...">
                <button class="search-btn">🔍</button>
            </div>
            <div class="nav-icons">
                <button id="login-btn">Sign In</button>
                <button id="cart-btn">Cart (<span id="cart-count">0</span>)</button>
            </div>
        </div>
    </header>

    <main>
        <h2>Our Products</h2>
        <section class="product-page-grid">
            <?php foreach ($groupedProducts as $subcategoryName => $products): ?>
                <h2><?php echo htmlspecialchars($subcategoryName); ?></h2>
                <div class="product-container">
                    <?php foreach ($products as $product): ?>
                        <div class="product-page-card">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <p><?php echo htmlspecialchars($product['product_name']); ?></p>
                            <p class="price">$<?php echo htmlspecialchars($product['product_price']); ?></p>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                <input type="number" name="amount" value="1" min="1" max="99">
                                <button type="submit" class="add-to-cart">+</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Freshcart</p>
    </footer>
</body>

</html>