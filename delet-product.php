<?php
include_once('../connect.php');

// Check if product ID is provided in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Prepare SQL statement to delete the product
    $sql = "DELETE FROM stock WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully!');window.location.href='./stock.php';</script>";
    } else {
        echo "<script>alert('Failed to delete product!');window.location.href='./stock.php';</script>";
    }
} else {
    // Redirect back to the stock page if product ID is not provided
    echo "<script>alert('Invalid request!');window.location.href='./stock.php';</script>";
}
