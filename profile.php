<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($userQuery);

$orderQuery = mysqli_query($conn, "SELECT o.id AS order_id, o.fullName, o.email, o.address, o.total, o.created_at, o.status, o.phone, 
                                          oi.itemName, oi.quantity, oi.itemPrice 
                                   FROM orders o 
                                   JOIN order_items oi ON o.id = oi.order_id 
                                   WHERE o.email='$email' 
                                   ORDER BY o.created_at DESC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_details'])) {
        $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
        $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        $updateQuery = "UPDATE users SET firstName='$firstName', lastName='$lastName', phone='$phone', address='$address' WHERE email='$email'";
        
        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Error updating profile.');</script>";
        }
    }

    if (isset($_POST['cancel_order'])) {
        $orderId = intval($_POST['order_id']);
        $cancelQuery = "DELETE FROM orders WHERE id='$orderId' AND email='$email'";

        if (mysqli_query($conn, $cancelQuery)) {
            echo "<script>alert('Order cancelled successfully!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Error cancelling order.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
      /* Global Styles */
* {
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: #f9f9f9;
  margin: 0;
  padding: 0;
  color: #333;
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
  position: sticky; /* keeps the nav at the top */
  top: 0;
  z-index: 1000;    /* ensures the nav is above other elements */
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

/* Main Container */
.container {
  max-width: 800px;
  margin: 80px auto 20px auto; /* 80px top margin so content sits below sticky nav */
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

/* Headings */
h2 {
  color: #2E7D32;
  margin-top: 0;
}

/* Form and Inputs */
.form-group {
  margin-bottom: 15px;
}

label {
  font-weight: bold;
}

input, textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

/* Buttons */
.btn {
  padding: 10px 15px;
  background: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  transition: background 0.3s ease;
}

.btn:hover {
  background: #388E3C;
}

.cancel-btn {
  background: #D32F2F;
  border: none;
  color: white;
  padding: 8px 12px;
  cursor: pointer;
  border-radius: 5px;
  margin-top: 10px;
}

.cancel-btn:hover {
  background: #B71C1C;
}

/* Order List */
.order-list {
  margin-top: 20px;
}

.order-item {
  padding: 10px;
  border: 1px solid #ddd;
  margin-bottom: 10px;
  border-radius: 5px;
  background: #f4f4f4;
}

/* Responsive */
@media (max-width: 768px) {
  .container {
    margin: 80px 10px 20px 10px; /* same top margin for sticky nav */
    padding: 15px;
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

<div class="container">
    <h2>My Profile</h2>
    
    <form method="POST">
        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($user['firstName']) ?>" required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($user['lastName']) ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" required><?= htmlspecialchars($user['address']) ?></textarea>
        </div>

        <button type="submit" name="update_details" class="btn">Update Profile</button>
    </form>

    <h2>My Orders</h2>
    <div class="order-list">
        <?php 
        $previousOrderId = null; 
        while ($order = mysqli_fetch_assoc($orderQuery)) : 
            if ($previousOrderId !== $order['order_id']) {
                if ($previousOrderId !== null) {
                    echo "</ul>";
                    echo "<form method='POST' style='display:inline;'>
                            <input type='hidden' name='order_id' value='{$previousOrderId}'>
                            <button type='submit' name='cancel_order' class='cancel-btn' onclick='return confirm(\"Are you sure you want to cancel this order?\")'>Cancel Order</button>
                          </form>";
                    echo "</div>";
                }
                ?>
                <div class="order-item">
                    <p><strong>Order ID:</strong> <?= $order['order_id'] ?></p>
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['fullName']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
                    <p><strong>Order Date:</strong> <?= $order['created_at'] ?></p>
                    <p><strong>Total Price:</strong> $<?= number_format($order['total'], 2) ?></p>
                    <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
                    <p><strong>Items:</strong></p>
                    <ul>
            <?php 
            } 
            ?>
                    <li><?= htmlspecialchars($order['itemName']) ?> - <?= intval($order['quantity']) ?> pcs @ $<?= number_format($order['itemPrice'], 2) ?> each</li>
            <?php 
            $previousOrderId = $order['order_id']; 
        endwhile; 

        if ($previousOrderId !== null) {
            echo "</ul>";
            echo "<form method='POST' style='display:inline;'>
                    <input type='hidden' name='order_id' value='{$previousOrderId}'>
                    <button type='submit' name='cancel_order' class='cancel-btn' onclick='return confirm(\"Are you sure you want to cancel this order?\")'>Cancel Order</button>
                  </form>";
            echo "</div>";
        }

        if (mysqli_num_rows($orderQuery) == 0) : ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
