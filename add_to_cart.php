<?php
session_start();
require_once("settings.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to add items to the shopping list.");
}

$user_id = $_SESSION['user_id'];

// Check if data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $amount = $_POST['amount'] ?? 1; // Default amount to 1 if not provided
    $day_id = date("w");

    // Validate input
    if (empty($product_id) || !is_numeric($amount) || $amount <= 0) {
        die("Error: Invalid product details or amount.");
    }

    // Connect to the database
    $conn = new mysqli($host, $user, $pswd, $dbnm);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 1: Check if the shopping list exists for the user and day
    $listSql = "SELECT list_id FROM cos20031_shopping_list WHERE user_id = ? AND day_id = ?";
    $listStmt = $conn->prepare($listSql);
    $listStmt->bind_param("ii", $user_id, $day_id);
    $listStmt->execute();
    $listResult = $listStmt->get_result();

    if ($listResult->num_rows > 0) {
        // Get the existing list_id
        $list = $listResult->fetch_assoc();
        $list_id = $list['list_id'];
    } else {
        // Create a new shopping list
        $insertListSql = "INSERT INTO cos20031_shopping_list (user_id, day_id) VALUES (?, ?)";
        $insertListStmt = $conn->prepare($insertListSql);
        $insertListStmt->bind_param("ii", $user_id, $day_id);

        if ($insertListStmt->execute()) {
            $list_id = $conn->insert_id; // Get the newly created list_id
        } else {
            die("Error: Unable to create a shopping list. " . $conn->error);
        }
        $insertListStmt->close();
    }

    $listStmt->close();

    // Step 2: Insert the product into the shopping_item table
    $insertItemSql = "INSERT INTO cos20031_shopping_item (list_id, product_id, amount) VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE amount = amount + VALUES(amount)";
    $insertItemStmt = $conn->prepare($insertItemSql);
    $insertItemStmt->bind_param("iii", $list_id, $product_id, $amount);

    if ($insertItemStmt->execute()) {
        echo "Item successfully added to your shopping list!";
        header("Location: shopping_list.php"); // Redirect back to the products page
    } else {
        die("Error: Unable to add item to shopping list. " . $conn->error);
    }

    $insertItemStmt->close();
    $conn->close();
} else {
    die("Error: Invalid request.");
}
?>
