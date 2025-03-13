<?php
session_start();
include("connect.php");
// Optional: Add admin authentication check
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Orders - Admin Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
    h1 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: center; }
    a { text-decoration: none; padding: 5px 8px; background: #4CAF50; color: #fff; border-radius: 3px; }
    a:hover { background: #45a049; }
  </style>
</head>
<body>
  <h1>Manage Orders</h1>
    <nav>
      <a href="admin_add_item.php">Add New Item</a>
      <a href="admin_manage_items.php">Manage Items</a>
      <a href="admin_orders.php">Manage Orders</a>
      <a href="admin_users.php">Manage Users</a>
        <p style="text-align:center;"><a href="admin_dashboard.php">Back to Dashboard</a></p>

    </nav>
  <table>
    <tr>
      <th>Order ID</th>
      <th>Customer</th>
      <th>Email</th>
      <th>Address</th>
      <th>Total ($)</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php
      $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
      while($order = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $order['id'] . "</td>";
          echo "<td>" . htmlspecialchars($order['fullName']) . "</td>";
          echo "<td>" . htmlspecialchars($order['email']) . "</td>";
          echo "<td>" . htmlspecialchars($order['address']) . "</td>";
          echo "<td>" . number_format($order['total'], 2) . "</td>";
          echo "<td>" . $order['created_at'] . "</td>";
          echo "<td><a href='admin_order_details.php?id=" . $order['id'] . "'>View Details</a></td>";
          echo "</tr>";
      }
    ?>
  </table>
  <p style="text-align:center;"><a href="admin_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
