<?php
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $order_status, $order_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Order status updated successfully!'); window.location.href='admin_orders.php';</script>";
    } else {
        echo "<script>alert('Error updating order status.'); window.location.href='admin_orders.php';</script>";
    }
    
    mysqli_stmt_close($stmt);
}
?>
