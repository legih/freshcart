<?php
session_start();
if (!isset($_SESSION['admin']) && !$_SESSION['admin']) {
    header("location: signin.php");
    exit();
}

require_once("settings.php");

// Connect to the database
$conn = new mysqli($host, $user, $pswd, $dbnm);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories for the filter and dropdown
$categories = $conn->query("SELECT category_id, category_name FROM cos20031_category")->fetch_all(MYSQLI_ASSOC);
$subcategories = $conn->query("SELECT subcategory_id, subcategory_name FROM cos20031_subcategory")->fetch_all(MYSQLI_ASSOC);
// Fetch stock statuses
$stockStatuses = $conn->query("SELECT stock_id, stock_type FROM cos20031_stock")->fetch_all(MYSQLI_ASSOC);

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Add product
        if ($action === 'add') {
            $name = $_POST['product_name'];
            $price = $_POST['product_price'];
            $stock = $_POST['stock_status'];
            $category_id = $_POST['category_id'];
            $subcategory_id = $_POST['subcategory_id'];
            $image = $_POST['image'];

            $query = "INSERT INTO cos20031_product (product_name, product_price, stock_status, category_id, subcategory_id, image) 
                      VALUES ('$name', '$price', '$stock', '$category_id', '$subcategory_id', '$image')";
            $conn->query($query);
        }

        // Update product
        elseif ($action === 'update') {
            $id = $_POST['product_id'];
            $name = $_POST['product_name'];
            $price = $_POST['product_price'];
            $stock = $_POST['stock_status'];
            $category_id = $_POST['category_id'];
            $subcategory_id = $_POST['subcategory_id'];
            $image = $_POST['image'];

            $query = "UPDATE cos20031_product 
                      SET product_name='$name', product_price='$price', stock_status='$stock', 
                          category_id='$category_id', subcategory_id='$subcategory_id', image='$image' 
                      WHERE product_id='$id'";
            $conn->query($query);
        }

        // Delete product
        elseif ($action === 'delete') {
            $id = $_POST['product_id'];
            $query = "DELETE FROM cos20031_product WHERE product_id='$id'";
            $conn->query($query);
        }
    }
}
// Fetch search parameters
$searchQuery = isset($_GET['search']) ? $_GET['search'] : "";
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : "";
$searchType = isset($_GET['search_type']) ? $_GET['search_type'] : "name"; // Default to search by name

// Base query with JOIN
$query = "SELECT p.*, 
            s.stock_type AS stock_name, 
            c.category_name, 
            sc.subcategory_name
          FROM cos20031_product p
          JOIN cos20031_stock s ON p.stock_status = s.stock_id
          JOIN cos20031_category c ON p.category_id = c.category_id
          LEFT JOIN cos20031_subcategory sc ON p.subcategory_id = sc.subcategory_id
          WHERE 1";

// Add search filter based on the selected type
if ($searchType === 'name' && !empty($searchQuery)) {
    $query .= " AND p.product_name LIKE '%$searchQuery%'";
} elseif ($searchType === 'category' && !empty($categoryFilter)) {
    $query .= " AND p.category_id = '$categoryFilter'";
}

// Execute the query
$products = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <a href="website.php">Back to Home</a>
    <a href="adminedit.php">Edit Mode</a>
    <h1>Admin Panel</h1>

    <!-- Search and Filter Form -->
    <form method="GET" action="admin.php">
        <select name="search_type">
            <option value="name" <?php echo isset($_GET['search_type']) && $_GET['search_type'] === 'name' ? 'selected' : ''; ?>>Search by Name</option>
            <option value="category" <?php echo isset($_GET['search_type']) && $_GET['search_type'] === 'category' ? 'selected' : ''; ?>>Search by Category</option>
        </select>
        
        <!-- For name search -->
        <input type="text" name="search" placeholder="Enter product name..." 
            value="<?php echo isset($_GET['search_type']) && $_GET['search_type'] === 'name' ? htmlspecialchars($searchQuery) : ''; ?>"
            style="<?php echo isset($_GET['search_type']) && $_GET['search_type'] === 'name' ? '' : 'display:none;'; ?>">

        <!-- For category search -->
        <select name="category" 
                style="<?php echo isset($_GET['search_type']) && $_GET['search_type'] === 'category' ? '' : 'display:none;'; ?>">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category['category_id']); ?>" 
                        <?php echo isset($_GET['category']) && $_GET['category'] == $category['category_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Search</button>
    </form>

    <!-- Products Table -->
    <h2>All Products</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['product_price']); ?></td>
                    <td><?php echo htmlspecialchars($product['stock_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['subcategory_name']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" width="50"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
