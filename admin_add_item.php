<?php
session_start();
include("connect.php");
// Optional: Add admin authentication check

// Fetch all categories for the dropdown (if you have a categories table)
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input values
    $name        = mysqli_real_escape_string($conn, $_POST['name']);
    $price       = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $offers      = mysqli_real_escape_string($conn, $_POST['offers']);
    $stock       = intval($_POST['stock']);
    
    // Category logic:
    // If the user chose to add a new category, insert it into the categories table.
    // Otherwise, use the selected category from the dropdown.
    $selected_category = $_POST['category_id'];
    $categoryName = "Uncategorized"; // default
    $category_id = NULL;

    if ($selected_category === "new") {
        // Sanitize and check the new category input
        if (isset($_POST['new_category']) && trim($_POST['new_category']) != "") {
            $new_category = mysqli_real_escape_string($conn, trim($_POST['new_category']));
            // Insert the new category into the database
            $insert_cat = "INSERT INTO categories (category_name) VALUES ('$new_category')";
            if(mysqli_query($conn, $insert_cat)) {
                $category_id = mysqli_insert_id($conn);
                $categoryName = $new_category;
            } else {
                $message = "Error adding new category: " . mysqli_error($conn);
            }
        } else {
            $message = "Please enter a valid new category name.";
        }
    } elseif (intval($selected_category) > 0) {
        $category_id = intval($selected_category);
        $catQuery = mysqli_query($conn, "SELECT category_name FROM categories WHERE id = $category_id");
        if ($catQuery && mysqli_num_rows($catQuery) > 0) {
            $catRow = mysqli_fetch_assoc($catQuery);
            $categoryName = mysqli_real_escape_string($conn, $catRow['category_name']);
        }
    }

    // Only proceed with item insertion if there's no error message so far
    if(!isset($message)) {
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
                    // Insert item into database with stock quantity, category_id, and category name
                    $sql = "INSERT INTO items 
                            (name, price, photo, description, offers, stock, category_id, category) 
                            VALUES 
                            ('$name', '$price', '$photo_name', '$description', '$offers', $stock, " . 
                            ($category_id !== NULL ? $category_id : "NULL") . ", 
                            '$categoryName')";
                    
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Add New Item</title>
  <style>
    body {
      font-family: Arial, sans-serif; 
      padding: 30px; 
      background: #f8f8f8;
    }
    form {
      background: #fff; 
      padding: 20px; 
      border: 1px solid #ddd; 
      max-width: 500px; 
      margin: 0 auto; 
      border-radius: 5px;
    }
    label {
      display: block; 
      margin-top: 15px; 
      font-weight: bold;
    }
    input[type="text"], input[type="number"], select, textarea {
      width: 100%; 
      padding: 8px; 
      margin-top: 5px; 
      border: 1px solid #ccc; 
      border-radius: 3px;
    }
    input[type="file"] {
      margin-top: 5px;
    }
    input[type="submit"] {
      margin-top: 20px; 
      background: #4CAF50; 
      color: #fff; 
      border: none; 
      padding: 10px 15px; 
      border-radius: 3px; 
      cursor: pointer;
    }
    .message {
      text-align: center; 
      color: #d8000c; 
      margin-bottom: 15px;
    }
    nav {
      text-align: center;
      margin-bottom: 20px;
    }
    nav a {
      margin: 0 10px;
      text-decoration: none;
      background: #4CAF50;
      color: #fff;
      padding: 8px 12px;
      border-radius: 3px;
    }
    nav a:hover {
      background: #45a049;
    }
  </style>
  <script>
    // JavaScript to show/hide the new category input field based on dropdown selection
    function toggleNewCategoryField() {
      var categorySelect = document.getElementById('category_id');
      var newCategoryField = document.getElementById('new_category');
      var newCategoryLabel = document.getElementById('new_category_label');
      if(categorySelect.value === "new") {
        newCategoryField.style.display = 'block';
        newCategoryLabel.style.display = 'block';
      } else {
        newCategoryField.style.display = 'none';
        newCategoryLabel.style.display = 'none';
      }
    }
    window.onload = function() {
      toggleNewCategoryField();
      document.getElementById('category_id').addEventListener('change', toggleNewCategoryField);
    };
  </script>
</head>
<body>
  <h2 style="text-align:center;">Add New Grocery Item</h2>
  <?php if(isset($message)) { echo '<p class="message">' . $message . '</p>'; } ?>
  
  <nav>
    <a href="admin_add_item.php">Add New Item</a>
    <a href="admin_manage_items.php">Manage Items</a>
    <a href="admin_orders.php">Manage Orders</a>
    <a href="admin_users.php">Manage Users</a>
    <a href="admin_dashboard.php">Back to Dashboard</a>
  </nav>

  <form action="admin_add_item.php" method="post" enctype="multipart/form-data">
    <label for="name">Item Name:</label>
    <input type="text" id="name" name="name" required>
    
    <label for="price">Price ($):</label>
    <input type="number" step="0.01" id="price" name="price" required>
    
    <label for="stock">Stock Quantity:</label>
    <input type="number" id="stock" name="stock" required>

    <label for="category_id">Select Category:</label>
    <select id="category_id" name="category_id">
      <option value="0">-- Uncategorized --</option>
      <?php
      // Populate dropdown with categories from DB
      if ($categories && mysqli_num_rows($categories) > 0) {
          while($cat = mysqli_fetch_assoc($categories)) {
              echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['category_name']) . '</option>';
          }
      }
      ?>
      <option value="new">-- Add New Category --</option>
    </select>
    
    <label for="new_category" id="new_category_label" style="display:none;">New Category Name:</label>
    <input type="text" id="new_category" name="new_category" style="display:none;">
    
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
