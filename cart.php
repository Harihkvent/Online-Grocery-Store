<?php
session_start();
include("connect.php");

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$message = '';

// Handle actions: add, update, and remove
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'add' && isset($_GET['id'])) {
        $item_id = intval($_GET['id']);
        // Get item details from database
        $query = mysqli_query($conn, "SELECT * FROM items WHERE id = $item_id");
        if ($query && mysqli_num_rows($query) > 0) {
            $item = mysqli_fetch_assoc($query);
            // If item already in cart, increase quantity; otherwise, add new entry.
            if (isset($_SESSION['cart'][$item_id])) {
                $_SESSION['cart'][$item_id]['quantity']++;
            } else {
                $_SESSION['cart'][$item_id] = array(
                    'name'     => $item['name'],
                    'price'    => $item['price'],
                    'photo'    => $item['photo'],
                    'quantity' => 1
                );
            }
            $message = "Item added to cart.";
        } else {
            $message = "Item not found.";
        }
    }
    
    // Update quantity action
    if ($action == 'update' && isset($_GET['id']) && isset($_GET['qty'])) {
        $item_id = intval($_GET['id']);
        $qty = intval($_GET['qty']);
        if (isset($_SESSION['cart'][$item_id])) {
            if ($qty > 0) {
                $_SESSION['cart'][$item_id]['quantity'] = $qty;
                $message = "Quantity updated.";
            } else {
                // If quantity is zero or less, remove item
                unset($_SESSION['cart'][$item_id]);
                $message = "Item removed from cart.";
            }
        }
    }
    
    // Remove item action
    if ($action == 'remove' && isset($_GET['id'])) {
        $item_id = intval($_GET['id']);
        if (isset($_SESSION['cart'][$item_id])) {
            unset($_SESSION['cart'][$item_id]);
            $message = "Item removed from cart.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Your Cart - Grocery Store</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      margin: 0;
      padding: 0;
    }
    header {
      background: #4CAF50;
      padding: 20px;
      text-align: center;
      color: #fff;
    }
    nav {
      background: #333;
      padding: 10px;
      text-align: center;
    }
    nav a {
      display: inline-block;
      padding: 10px 15px;
      color: #fff;
      text-decoration: none;
      margin: 0 5px;
      border-radius: 3px;
    }
    nav a:hover {
      background: #555;
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
    .message {
      color: green;
      margin-bottom: 15px;
    }
    .btn {
      display: inline-block;
      padding: 10px 15px;
      background: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
    }
    .btn:hover {
      background: #45a049;
    }
    .qty-controls a {
      margin: 0 5px;
      text-decoration: none;
      font-weight: bold;
      color: #4CAF50;
    }
    .remove-link {
      color: red;
      text-decoration: none;
      margin-left: 10px;
    }
    .remove-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <header>
    <h1>Your Cart</h1>
  </header>

  <!-- Navigation buttons -->
  <nav>
    <a href="homepage.php">Home</a>
    <a href="login.php">Login</a>
  </nav>

  <div class="container">
    <?php if ($message) { echo '<p class="message">' . htmlspecialchars($message) . '</p>'; } ?>

    <?php if (!empty($_SESSION['cart'])): ?>
      <table>
        <tr>
          <th>Photo</th>
          <th>Item</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
        </tr>
        <?php 
          $total = 0;
          foreach ($_SESSION['cart'] as $id => $cartItem): 
            $subtotal = $cartItem['price'] * $cartItem['quantity'];
            $total += $subtotal;
        ?>
          <tr>
            <td>
              <img src="uploads/<?php echo htmlspecialchars($cartItem['photo']); ?>" alt="<?php echo htmlspecialchars($cartItem['name']); ?>" style="width:80px;">
            </td>
            <td><?php echo htmlspecialchars($cartItem['name']); ?></td>
            <td>$<?php echo number_format($cartItem['price'], 2); ?></td>
            <td>
              <div class="qty-controls">
                <!-- Minus button -->
                <a href="cart.php?action=update&id=<?php echo $id; ?>&qty=<?php echo $cartItem['quantity'] - 1; ?>">-</a>
                <?php echo $cartItem['quantity']; ?>
                <!-- Plus button -->
                <a href="cart.php?action=update&id=<?php echo $id; ?>&qty=<?php echo $cartItem['quantity'] + 1; ?>">+</a>
                <!-- Remove link -->
                <a class="remove-link" href="cart.php?action=remove&id=<?php echo $id; ?>">Remove</a>
              </div>
            </td>
            <td>$<?php echo number_format($subtotal, 2); ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="4" style="text-align:right;"><strong>Total:</strong></td>
          <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
        </tr>
      </table>
      <a class="btn" href="checkout.php">Proceed to Checkout</a>
    <?php else: ?>
      <p>Your cart is empty.</p>
    <?php endif; ?>
  </div>
</body>
</html>
