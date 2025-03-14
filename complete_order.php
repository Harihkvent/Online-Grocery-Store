<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = intval($_POST["order_id"]);
    $query = "UPDATE orders SET status='Completed' WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);
    if ($stmt->execute()) {
        header("Location: admin_orders.php?success=Order marked as completed.");
    } else {
        header("Location: admin_orders.php?error=Failed to update order.");
    }
    exit();
}
?>
