<?php
    require_once("settings.php");

    // Connect to database and get session information
    $conn = new mysqli($host, $user, $pswd, $dbnm) or die("Failed to connect to server: " . mysqli_connect_error());

    $searchQuery = isset($_GET['search']) ? $_GET['search'] : "";
    $query = "SELECT p.*, 
            c.category_name, 
            sc.subcategory_name
          FROM cos20031_product p
          JOIN cos20031_category c ON p.category_id = c.category_id
          LEFT JOIN cos20031_subcategory sc ON p.subcategory_id = sc.subcategory_id
          WHERE 1";

    if (!empty($searchQuery)) {
        $query .= " AND p.product_name LIKE '%$searchQuery%'";
    }

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($products as $product) {
            $subcategory = $product['subcategory_name'] ?? "Uncategorized";
            $groupedProducts[$subcategory][] = $product;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="Document" content="width=device-width, initial-scale=1.0">
    <title>Freshcart - Products</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>

<body>
    <?php include "nav.inc"; ?>

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