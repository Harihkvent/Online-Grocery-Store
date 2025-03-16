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
    /* Global Styles */
* {
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f0f2f5;
  margin: 0;
  padding: 0;
  color: #333;
  line-height: 1.6;
}

/* Navigation */
nav {
  background: #fff;
  border-bottom: 1px solid #e0e0e0;
  padding: 10px 20px;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 15px;
}

nav a {
  color: #FF5722;
  text-decoration: none;
  font-weight: bold;
  padding: 5px 10px;
  transition: color 0.3s ease;
}

nav a:hover {
  color: #e64a19;
}

/* Header */
header {
  background: #FF5722;
  padding: 30px 20px;
  text-align: center;
  color: #fff;
}

header h1 {
  margin: 0;
  font-size: 2.5em;
}

/* Container */
.container {
  max-width: 1000px;
  margin: 30px auto;
  background: #fff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.08);
}

/* Headings */
h2 {
  margin-top: 0;
  color: #FF5722;
}

/* Tables */
table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
}

th, td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
}

th {
  background-color: #fafafa;
  font-weight: 600;
}

td {
  background-color: #fff;
}

/* Total Section */
.total {
  text-align: right;
  font-size: 1.2em;
  font-weight: bold;
  margin-top: 20px;
}

/* Buttons */
.btn {
  display: inline-block;
  padding: 12px 20px;
  background: #FF5722;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
  transition: background 0.3s ease;
}

.btn:hover {
  background: #e64a19;
}

/* Forms */
form {
  max-width: 500px;
  margin: 30px auto;
  padding: 20px;
  background: #fafafa;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.06);
}

form input[type="text"],
form input[type="email"] {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

form input[type="submit"] {
  background: #FF5722;
  border: none;
  width: 100%;
  padding: 12px;
  color: #fff;
  font-size: 1em;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s ease;
}

form input[type="submit"]:hover {
  background: #e64a19;
}

/* Responsive Design */
@media (max-width: 768px) {
  .container {
    margin: 20px;
    padding: 20px;
  }
  
  nav {
    flex-direction: column;
    gap: 10px;
  }
}

  </style>
</head>
<body>
  <nav>
    <a href="homepage.php">Home</a>
    <a href="homepage.php">Shop</a>
    <a href="categories.php">Categories</a>
    <a href="cart.php">cart</a>
    <a href="profile.php">Profile</a>
    <a href="contact.php">Contact Us</a>
    <?php if(isset($_SESSION['email'])): ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
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
