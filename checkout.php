<?php
session_start();
include("connect.php");

// For demonstration, we assume the cart is already populated in the session.
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - Grocery Store</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      margin: 0;
      padding: 0;
    }
    header {
      background: #FF5722;
      padding: 20px;
      text-align: center;
      color: #fff;
    }
    .container {
      max-width: 1000px;
      margin: 20px auto;
      padding: 20px;
      background: #fff;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    h2 {
      margin-top: 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    .total {
      text-align: right;
      font-size: 18px;
      margin-top: 20px;
    }
    .btn {
      display: inline-block;
      padding: 10px 15px;
      background: #FF5722;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
    }
    .btn:hover {
      background: #e64a19;
    }
    form {
      max-width: 500px;
      margin: 20px auto;
    }
    form input[type="text"], form input[type="email"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0;
      border: 1px solid #ccc;
      border-radius: 3px;
    }
    form input[type="submit"] {
      background: #FF5722;
      border: none;
      padding: 10px 20px;
      color: #fff;
      border-radius: 3px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <header>
    <h1>Checkout</h1>
  </header>

  <div class="container">
    <h2>Your Order Summary</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
      <table>
        <tr>
          <th>Item</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
        <?php 
          foreach ($_SESSION['cart'] as $id => $cartItem): 
            $subtotal = $cartItem['price'] * $cartItem['quantity'];
            $total += $subtotal;
        ?>
          <tr>
            <td><?php echo htmlspecialchars($cartItem['name']); ?></td>
            <td>$<?php echo number_format($cartItem['price'], 2); ?></td>
            <td><?php echo $cartItem['quantity']; ?></td>
            <td>$<?php echo number_format($subtotal, 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
      <div class="total">
        <strong>Total: $<?php echo number_format($total, 2); ?></strong>
      </div>
      
      <!-- Simple checkout form (in a real system, you would handle payment details) -->
      <h2>Enter Your Details</h2>
      <form action="process_checkout.php" method="post">
        <input type="text" name="fullName" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="address" placeholder="Shipping Address" required>
        <input type="text" name="phone" placeholder="phone" required>
        <input type="submit" value="Place Order">
      </form>
    <?php else: ?>
      <p>Your cart is empty. <a href="homepage.php">Shop now</a>.</p>
    <?php endif; ?>
  </div>
</body>
</html>
