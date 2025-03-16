<?php
session_start();
include("connect.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Orders - Admin Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
    h1 { text-align: center; }
    nav {
      text-align: center;
      margin-bottom: 20px;
    }
    nav a {
      margin: 0 5px;
      text-decoration: none;
      padding: 5px 8px;
      background: #4CAF50;
      color: #fff;
      border-radius: 3px;
    }
    nav a:hover {
      background: #45a049;
    }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: center;
      vertical-align: top;
    }
    a, button {
      text-decoration: none;
      padding: 5px 8px;
      color: #fff;
      border-radius: 3px;
      border: none;
      cursor: pointer;
    }
    .view-btn { background: #4CAF50; }
    .delete-btn { background: #e74c3c; }
    .complete-btn { background: #3498db; }
    a:hover, button:hover { opacity: 0.8; }
    select {
      padding: 5px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <h1>Manage Orders</h1>
  <nav>
      <a href="admin_add_item.php">Add New Item</a>
      <a href="admin_manage_items.php">Manage Items</a>
      <a href="admin_orders.php">Manage Orders</a>
      <a href="admin_users.php">Manage Users</a>
      <a href="admin_dashboard.php">Back to Dashboard</a>
  </nav>
  
  <table>
    <tr>
      <th>Order ID</th>
      <th>Customer</th>
      <th>Email</th>
      <th>Phone</th> <!-- Added Phone -->
      <th>Address</th>
      <th>Items</th>
      <th>Total ($)</th>
      <th>Date</th>
      <th>Status / Actions</th>
    </tr>
    <?php
      $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
      while($order = mysqli_fetch_assoc($result)) {
          $itemsResult = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = " . $order['id']);
          $itemsList = [];
          while($itemRow = mysqli_fetch_assoc($itemsResult)) {
              $itemsList[] = htmlspecialchars($itemRow['itemName']) . " (Qty: " . intval($itemRow['quantity']) . ")";
          }
          $itemsDisplay = implode("<br>", $itemsList);

          echo "<tr>";
            echo "<td>" . $order['id'] . "</td>";
            echo "<td>" . htmlspecialchars($order['fullName']) . "</td>";
            echo "<td>" . htmlspecialchars($order['email']) . "</td>";
            echo "<td>" . htmlspecialchars($order['phone']) . "</td>"; // Added Phone Display
            echo "<td>" . htmlspecialchars($order['address']) . "</td>";
            echo "<td>" . $itemsDisplay . "</td>";
            echo "<td>" . number_format($order['total'], 2) . "</td>";
            echo "<td>" . $order['created_at'] . "</td>";
            echo "<td>
                    <a class='view-btn' href='admin_order_details.php?id=" . $order['id'] . "'>View</a>
                    <form method='POST' action='update_order_status.php' style='display:inline;'>
                        <input type='hidden' name='order_id' value='" . $order['id'] . "'>
                        <select name='order_status'>
                            <option value='Pending' " . ($order['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                            <option value='Processing' " . ($order['status'] == 'Processing' ? 'selected' : '') . ">Processing</option>
                            <option value='Completed' " . ($order['status'] == 'Completed' ? 'selected' : '') . ">Completed</option>
                            <option value='Cancelled' " . ($order['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
                        </select>
                        <button class='complete-btn' type='submit'>Update</button>
                    </form>
                    <form method='POST' action='delete_order.php' style='display:inline;'>
                        <input type='hidden' name='order_id' value='" . $order['id'] . "'>
                        <button class='delete-btn' type='submit' onclick='return confirm(\"Are you sure?\");'>Delete</button>
                    </form>
                  </td>";
          echo "</tr>";
      }
    ?>
</table>

</body>
</html>
