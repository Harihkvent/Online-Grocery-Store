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
      vertical-align: top; /* So multi-line items look nicer */
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
      <th>Address</th>
      <th>Items</th> <!-- New column for ordered items -->
      <th>Total ($)</th>
      <th>Date</th>
      <th>Status / Actions</th>
    </tr>
    <?php
      // Fetch all orders
      $result = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC");
      while($order = mysqli_fetch_assoc($result)) {
          // For each order, fetch its items
          $itemsResult = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = " . $order['id']);
          $itemsList = [];
          while($itemRow = mysqli_fetch_assoc($itemsResult)) {
              // Build a string like "Apple (Qty: 2)"
              $itemsList[] = htmlspecialchars($itemRow['itemName']) 
                           . " (Qty: " . intval($itemRow['quantity']) . ")";
          }
          // Join all items with line breaks
          $itemsDisplay = implode("<br>", $itemsList);

          echo "<tr>";
            echo "<td>" . $order['id'] . "</td>";
            echo "<td>" . htmlspecialchars($order['fullName']) . "</td>";
            echo "<td>" . htmlspecialchars($order['email']) . "</td>";
            echo "<td>" . htmlspecialchars($order['address']) . "</td>";
            echo "<td>" . $itemsDisplay . "</td>"; // Display the ordered items
            echo "<td>" . number_format($order['total'], 2) . "</td>";
            echo "<td>" . $order['created_at'] . "</td>";
            echo "<td>
                    <a class='view-btn' href='admin_order_details.php?id=" . $order['id'] . "'>View</a>
                    <form method='POST' action='complete_order.php' style='display:inline;'>
                        <input type='hidden' name='order_id' value='" . $order['id'] . "'>
                        <button class='complete-btn' type='submit'>Complete</button>
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
