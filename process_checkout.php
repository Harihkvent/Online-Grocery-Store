<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the connection was successful
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Collect form data
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $total = 0;

    // Check if cart is set and not empty
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        exit;
    }

    // Calculate total price
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Insert order details into 'orders' table
    $orderSql = "INSERT INTO orders (fullName, email, address, total, created_at) 
                 VALUES ('$fullName', '$email', '$address', '$total', NOW())";

    if (mysqli_query($conn, $orderSql)) {
        $order_id = mysqli_insert_id($conn);

        // Insert each item into order_items table
        foreach ($_SESSION['cart'] as $item_id => $item) {
            $itemName = mysqli_real_escape_string($conn, $item['name']);
            $itemPrice = $item['price'];
            $quantity = $item['quantity'];

            $orderItemSql = "INSERT INTO order_items (order_id, item_id, itemName, itemPrice, quantity)
                             VALUES ($order_id, $item_id, '$itemName', '$itemPrice', $quantity)";
            if (!mysqli_query($conn, $orderItemSql)) {
                echo "Error adding order item: " . mysqli_error($conn);
                exit;
            }

            // Update stock in items table
            $updateStock = "UPDATE items SET stock = stock - $quantity WHERE id = $item_id";
            if (!mysqli_query($conn, $updateStock)) {
                echo "Error updating stock for item $item_id: " . mysqli_error($conn);
                exit;
            }
        }

        // Clear the cart session
        unset($_SESSION['cart']);

        $message = "Order placed successfully! Your Order ID is " . $order_id;
    } else {
        $message = "Error placing order: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order Confirmation - Grocery Store</title>
  <style>
    body {
      text-align: center;
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
      background: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .message {
      font-size: 18px;
      margin: 20px 0;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 15px;
      background: #28a745;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    a:hover {
      background: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Order Confirmation</h2>
    <p class="message"><?php echo isset($message) ? $message : 'Something went wrong. Please try again.'; ?></p>
    <a href="homepage.php">Return to Homepage</a>
  </div>
</body>
</html>
