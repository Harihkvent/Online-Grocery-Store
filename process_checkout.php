<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get customer details from the checkout form
    $fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Ensure the cart is not empty
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo "Your cart is empty.";
        exit;
    }
    
    // Calculate total from cart items
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    // Insert the order into the orders table
    $orderSql = "INSERT INTO orders (fullName, email, address, total, created_at) VALUES ('$fullName', '$email', '$address', '$total', NOW())";
    if (mysqli_query($conn, $orderSql)) {
        $order_id = mysqli_insert_id($conn);
        
        // Insert each cart item into the order_items table
        foreach ($_SESSION['cart'] as $item_id => $item) {
            $itemName  = mysqli_real_escape_string($conn, $item['name']);
            $itemPrice = $item['price'];
            $quantity  = $item['quantity'];
            $orderItemSql = "INSERT INTO order_items (order_id, item_id, itemName, itemPrice, quantity)
                             VALUES ($order_id, $item_id, '$itemName', '$itemPrice', $quantity)";
            mysqli_query($conn, $orderItemSql);
        }
        
        // Clear the cart after order is processed
        unset($_SESSION['cart']);
        
        $message = "Order placed successfully! Your Order ID is " . $order_id;
    } else {
        $message = "Error placing order: " . mysqli_error($conn);
    }
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
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    .container {
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
      background: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    h1 {
      color: #4CAF50;
    }
    .message {
      font-size: 18px;
      margin: 20px 0;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 15px;
      background: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
    }
    a:hover {
      background: #45a049;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Order Confirmation</h1>
    <p class="message"><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></p>
    <a href="homepage.php">Return to Homepage</a>
  </div>
</body>
</html>
