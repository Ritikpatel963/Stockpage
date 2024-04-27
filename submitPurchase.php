<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include_once('../connect.php');

    // Get form data
    $productId = $_POST['product_id'];
    $weight = $_POST['weight'];
    $cost = $_POST['cost'];

    // Prepare and execute the SQL query
    $stmt = $db->prepare("INSERT INTO purchase_history (product_id, weight, cost) VALUES (?, ?, ?)");
    $stmt->bind_param("idd", $productId, $weight, $cost);
    $stmt->execute();

    // Close statement and database connection
    $stmt->close();

    // Get form data
    $productId = $_POST['product_id'];
    $purchasedWeight = $_POST['weight'];

    // Retrieve the existing weight from the database
    $sql = "SELECT weight FROM stock WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($existingWeight);
    $stmt->fetch();
    $stmt->close();

    // Calculate the new total weight
    $newTotalWeight = $existingWeight + $purchasedWeight;

    // Update the database with the new total weight
    $updateSql = "UPDATE stock SET weight = ? WHERE id = ?";
    $updateStmt = $db->prepare($updateSql);
    $updateStmt->bind_param("di", $newTotalWeight, $productId);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirect back to the page where the modal was opened
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // If the form is not submitted, redirect to the homepage or display an error message
    header("Location: ./stock.php");
    exit();
}
