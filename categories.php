<?php
session_start();
include("connect.php");

// 1. Check if a category_id is selected
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// 2. Fetch all categories for the sidebar/list
$categories_result = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Categories</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f8f8f8;
      display: flex;
    }
    .sidebar {
      width: 200px;
      background: #333;
      color: #fff;
      padding: 20px;
      box-sizing: border-box;
      height: 100vh;
    }
    .sidebar a {
      display: block;
      color: #fff;
      text-decoration: none;
      margin-bottom: 10px;
    }
    .sidebar a:hover {
      background: #444;
      padding-left: 5px;
    }
    .main-content {
      flex: 1;
      padding: 20px;
    }
    .items-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 20px;
    }
    .item-card {
      background: #fff;
      padding: 15px;
      text-align: center;
      border-radius: 5px;
    }
    .item-card img {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
    }
    .btn {
      display: inline-block;
      padding: 8px 12px;
      margin-top: 10px;
      background: #4CAF50;
      color: #fff;
      text-decoration: none;
      border-radius: 3px;
    }
    .btn:hover {
      background: #45a049;
    }
  </style>
</head>
<body>
  <!-- SIDEBAR: list categories -->
  <div class="sidebar">
    <h2>Categories</h2>
    <?php
    while($cat = mysqli_fetch_assoc($categories_result)) {
        echo '<a href="categories.php?category_id=' . $cat['id'] . '">' . htmlspecialchars($cat['category_name']) . '</a>';
    }
    ?>
  </div>

  <!-- MAIN CONTENT: items in the selected category -->
  <div class="main-content">
    <h2>Items</h2>
    <?php
    // If a category is selected, show items for that category
    if ($category_id > 0) {
        $items_sql = "SELECT * FROM items WHERE category_id = $category_id ORDER BY id DESC";
    } else {
        // If no category is selected, show all items or prompt the user
        $items_sql = "SELECT * FROM items ORDER BY id DESC";
        echo "<p>Select a category on the left to filter items.</p>";
    }

    $items_result = mysqli_query($conn, $items_sql);

    if ($items_result && mysqli_num_rows($items_result) > 0) {
        echo '<div class="items-grid">';
        while($item = mysqli_fetch_assoc($items_result)) {
            echo '<div class="item-card">';
              // Photo
              echo '<img src="uploads/' . htmlspecialchars($item['photo']) . '" alt="' . htmlspecialchars($item['name']) . '">';
              // Name
              echo '<h3>' . htmlspecialchars($item['name']) . '</h3>';
              // Price
              echo '<p>$' . number_format($item['price'], 2) . '</p>';
              // Stock
              echo '<p>Stock: ' . (int)$item['stock'] . '</p>';
              // Add to cart button
              echo '<a class="btn" href="cart.php?action=add&id=' . $item['id'] . '">Add to Cart</a>';
              // Buy now button
              echo '<a class="btn" style="background:#FF5722; margin-left:5px;" href="checkout.php?id=' . $item['id'] . '">Buy Now</a>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        if ($category_id > 0) {
            echo "<p>No items found in this category.</p>";
        }
    }
    ?>
  </div>
</body>
</html>
