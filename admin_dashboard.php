<?php
session_start();
include("connect.php");

// Optional: Check if admin is logged in

// Get counts from database
$orderCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS count FROM orders");
$orderCountData = mysqli_fetch_assoc($orderCountQuery);
$order_count = $orderCountData['count'];

$userCountQuery = mysqli_query($conn, "SELECT COUNT(*) AS count FROM users");
$userCountData = mysqli_fetch_assoc($userCountQuery);
$user_count = $userCountData['count'];

$stockCountQuery = mysqli_query($conn, "SELECT SUM(stock) AS totalStock FROM items");
$stockCountData = mysqli_fetch_assoc($stockCountQuery);
$stock_count = $stockCountData['totalStock'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body { 
      font-family: Arial, sans-serif; 
      background: #f8f8f8; 
      margin: 0; 
      padding: 0;
    }
    /* Top Navigation */
    nav.top-nav {
      background: #4CAF50;
      padding: 10px 20px;
      text-align: right;
    }
    nav.top-nav a {
      margin: 10px;
      display: inline-block;
      padding: 10px 15px;
      background: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
    }
    nav.top-nav a:hover {
      background: #45a049;
    }
    /* Stats Section */
    .stats {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin: 20px auto;
      max-width: 800px;
      padding: 10px;
    }
    .stat {
      background: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      flex: 1;
      text-align: center;
    }
    .stat h3 {
      margin: 0;
      font-size: 2em;
      color: #333;
    }
    .stat p {
      margin: 5px 0 0;
      font-size: 1em;
      color: #777;
    }
    /* Main Container */
    .container { 
      max-width: 800px; 
      margin: auto; 
      padding: 20px; 
      text-align: center; 
      background: #fff; 
      border-radius: 8px; 
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    nav.main-nav {
      margin: 20px 0;
    }
    nav.main-nav a {
      margin: 10px;
      display: inline-block;
      padding: 10px 15px;
      background: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
    }
    nav.main-nav a:hover {
      background: #45a049;
    }
  </style>
</head>
<body>
  <!-- Top Navigation -->
  <nav class="top-nav">
    <?php if(isset($_SESSION['email'])): ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
  
  <!-- Statistics Section -->
  <div class="stats">
    <div class="stat">
      <h3><?php echo $order_count; ?></h3>
      <p>Orders</p>
    </div>
    <div class="stat">
      <h3><?php echo $user_count; ?></h3>
      <p>Users</p>
    </div>
    <div class="stat">
      <h3><?php echo $stock_count; ?></h3>
      <p>Stock</p>
    </div>
  </div>

  <div class="container">
    <h1>Admin Dashboard</h1>
    <nav class="main-nav">
      <a href="admin_add_item.php">Add New Item</a>
      <a href="admin_manage_items.php">Manage Items</a>
      <a href="admin_orders.php">Manage Orders</a>
      <a href="admin_users.php">Manage Users</a>
    </nav>
  </div>
</body>
</html>
