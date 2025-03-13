<?php
session_start();
include("connect.php");
// Optional: Add admin authentication check

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input values
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $price       = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $offers      = mysqli_real_escape_string($conn, $_POST['offers']);
    $stock       = intval($_POST['stock']);

    // File upload processing
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = __DIR__ . "/uploads/";   // Ensure this directory exists and is writable
        $photo_name = basename($_FILES["photo"]["name"]);
        $target_file = $upload_dir . $photo_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if(in_array($imageFileType, $allowed_types)) {
            if(move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
                // Insert item into database with stock quantity
                $sql = "INSERT INTO items (name, price, photo, description, offers, stock) 
                        VALUES ('$name', '$price', '$photo_name', '$description', '$offers', $stock)";
                if(mysqli_query($conn, $sql)) {
                    $message = "Item added successfully!";
                } else {
                    $message = "Error: " . mysqli_error($conn);
                }
            } else {
                $message = "Error uploading the photo.";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG & GIF are allowed.";
        }
    } else {
        $message = "Please upload a photo.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Add New Item</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 30px; background: #f8f8f8; }
    form { background: #fff; padding: 20px; border: 1px solid #ddd; max-width: 500px; margin: 0 auto; border-radius: 5px; }
    label { display: block; margin-top: 15px; font-weight: bold; }
    input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 3px; }
    input[type="file"] { margin-top: 5px; }
    input[type="submit"] { margin-top: 20px; background: #4CAF50; color: #fff; border: none; padding: 10px 15px; border-radius: 3px; cursor: pointer; }
    .message { text-align: center; color: #d8000c; margin-bottom: 15px; }
  </style>
</head>
<body>
  <h2 style="text-align:center;">Add New Grocery Item</h2>
  <?php if(isset($message)) { echo '<p class="message">' . $message . '</p>'; } ?>
    <nav>
      <a href="admin_add_item.php">Add New Item</a>
      <a href="admin_manage_items.php">Manage Items</a>
      <a href="admin_orders.php">Manage Orders</a>
      <a href="admin_users.php">Manage Users</a>
        <p style="text-align:center;"><a href="admin_dashboard.php">Back to Dashboard</a></p>

    </nav>
  <form action="admin_add_item.php" method="post" enctype="multipart/form-data">
    <label for="name">Item Name:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="price">Price ($):</label>
    <input type="number" step="0.01" id="price" name="price" required>
    
    <label for="stock">Stock Quantity:</label>
    <input type="number" id="stock" name="stock" required>
    
    <label for="photo">Photo:</label>
    <input type="file" id="photo" name="photo" accept="image/*" required>
    
    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" required></textarea>
    
    <label for="offers">Offers (if any):</label>
    <input type="text" id="offers" name="offers">
    
    <input type="submit" value="Add Item">
  </form>
</body>
</html>
