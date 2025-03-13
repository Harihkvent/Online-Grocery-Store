<?php
session_start();
include("connect.php");
// Optional: Check if admin is logged in
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; }
    .container { max-width: 800px; margin: auto; padding: 20px; text-align: center; }
    nav a { margin: 10px; display: inline-block; padding: 10px 15px; background: #4CAF50; color: #fff; text-decoration: none; border-radius: 3px; }
    nav a:hover { background: #45a049; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Admin Dashboard</h1>
    <nav>
      <a href="admin_add_item.php">Add New Item</a>
      <a href="admin_manage_items.php">Manage Items</a>
      <a href="admin_orders.php">Manage Orders</a>
      <a href="admin_users.php">Manage Users</a>
    </nav>
  </div>
</body>
</html>
