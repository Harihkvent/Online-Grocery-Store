<?php
session_start();
include("connect.php");
// Optional: Add admin authentication check

$message = '';
// Handle deletion if the action and id are set
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    // Delete user from database
    $deleteSql = "DELETE FROM users WHERE Id = $userId";
    if (mysqli_query($conn, $deleteSql)) {
        $message = "User deleted successfully.";
    } else {
        $message = "Error deleting user: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Users - Admin Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
    h1 { text-align: center; }
    .message { color: green; text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: center; }
    a.delete-btn {
      padding: 5px 10px;
      background: #d9534f;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
    }
    a.delete-btn:hover {
      background: #c9302c;
    }
  </style>
</head>
<body>
  <h1>Manage Users</h1>
  <?php if($message) { echo '<p class="message">' . htmlspecialchars($message) . '</p>'; } ?>
  <table>
    <tr>
      <th>User ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Actions</th>
    </tr>
    <?php
      // Fetch all users, ordered by Id descending
      $result = mysqli_query($conn, "SELECT * FROM users ORDER BY Id DESC");
      
      // Loop through each user record
      while($user = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          // Use 'Id' (capital 'I') to match your table schema
          echo "<td>" . $user['Id'] . "</td>";
          // Combine firstName and lastName
          echo "<td>" . htmlspecialchars($user['firstName'] . " " . $user['lastName']) . "</td>";
          // Display email
          echo "<td>" . htmlspecialchars($user['email']) . "</td>";
          // Add a delete action link
          echo "<td><a class='delete-btn' href='admin_users.php?action=delete&id=" . $user['Id'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a></td>";
          echo "</tr>";
      }
    ?>
  </table>
  <p style="text-align:center;"><a href="admin_dashboard.php">Back to Dashboard</a></p>
</body>
</html>
