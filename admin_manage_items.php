<?php
session_start();
include("connect.php");
// Optional: Add admin authentication check

$message = '';
// Handle delete and update stock actions
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "DELETE FROM items WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $message = "Item deleted successfully.";
        } else {
            $message = "Error deleting item: " . mysqli_error($conn);
        }
    }
    if ($_GET['action'] == 'update_stock' && isset($_GET['id']) && isset($_GET['stock'])) {
        $id = intval($_GET['id']);
        $stock = intval($_GET['stock']);
        $sql = "UPDATE items SET stock = $stock WHERE id = $id";
        if (mysqli_query($conn, $sql)) {
            $message = "Stock updated successfully.";
        } else {
            $message = "Error updating stock: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Items - Admin Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; margin: 0; padding: 20px; }
    h1 { text-align: center; }
    .message { color: green; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: center; }
    a { text-decoration: none; padding: 5px 8px; background: #4CAF50; color: #fff; border-radius: 3px; }
    a:hover { background: #45a049; }
    form { display: inline; }
    input[type="number"] { width: 60px; }
  </style>
</head>
<body>
  <h1>Manage Items</h1>
    <nav>
      <a href="admin_add_item.php">Add New Item</a>
      <a href="admin_manage_items.php">Manage Items</a>
      <a href="admin_orders.php">Manage Orders</a>
      <a href="admin_users.php">Manage Users</a>
        <p style="text-align:center;"><a href="admin_dashboard.php">Back to Dashboard</a></p>

    </nav>
  <?php if($message) { echo '<p class="message">' . htmlspecialchars($message) . '</p>'; } ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Price ($)</th>
      <th>Stock</th>
      <th>Actions</th>
    </tr>
    <?php
      $result = mysqli_query($conn, "SELECT * FROM items ORDER BY id DESC");
      while($item = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $item['id'] . "</td>";
          echo "<td>" . htmlspecialchars($item['name']) . "</td>";
          echo "<td>" . number_format($item['price'], 2) . "</td>";
          echo "<td>" . $item['stock'] . "</td>";
          echo "<td>";
          echo "<a href='admin_manage_items.php?action=delete&id=" . $item['id'] . "'>Delete</a> | ";
          echo "<form method='GET' action='admin_manage_items.php' style='display:inline;'>";
          echo "<input type='hidden' name='action' value='update_stock'>";
          echo "<input type='hidden' name='id' value='" . $item['id'] . "'>";
          echo "<input type='number' name='stock' value='" . $item['stock'] . "'>";
          echo "<input type='submit' value='Update'>";
          echo "</form>";
          echo "</td>";
          echo "</tr>";
      }
    ?>
  </table>
  <p style="text-align:center;"><a href="admin_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
