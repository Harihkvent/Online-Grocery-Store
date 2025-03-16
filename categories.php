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
  <style>/* Global Styles */
* {
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: #f0f2f5;
  margin: 0;
  padding: 0;
  color: #333;
  line-height: 1.6;
}

/* Navigation */
nav {
  background: #fff;
  border-bottom: 1px solid #e0e0e0;
  padding: 10px 20px;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 15px;
  position: sticky; /* keeps the nav bar at the top when scrolling */
  top: 0;
  z-index: 1000; /* ensure the nav stays above other elements */
}

nav a {
  color: #FF5722;
  text-decoration: none;
  font-weight: bold;
  padding: 5px 10px;
  transition: color 0.3s ease;
}

nav a:hover {
  color: #e64a19;
}

/* Optional header (if used) */
header {
  background: #FF5722;
  padding: 30px 20px;
  text-align: center;
  color: #fff;
}

header h1 {
  margin: 0;
  font-size: 2.5em;
}

/* Main container below nav */
main, 
.main-content { /* Use either <main> or a .main-content div */
  max-width: 1000px;
  margin: 0 auto;
  padding: 30px;
  margin-top: 80px; /* ensures space below the sticky nav bar */
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.08);
}

/* Headings */
h2 {
  margin-top: 0;
  color: #FF5722;
}

/* Tables */
table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
}

th, td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid #e0e0e0;
}

th {
  background-color: #fafafa;
  font-weight: 600;
}

td {
  background-color: #fff;
}

/* Total Section */
.total {
  text-align: right;
  font-size: 1.2em;
  font-weight: bold;
  margin-top: 20px;
}

/* Buttons */
.btn {
  display: inline-block;
  padding: 12px 20px;
  background: #FF5722;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
  transition: background 0.3s ease;
}

.btn:hover {
  background: #e64a19;
}

/* Forms */
form {
  max-width: 500px;
  margin: 30px auto;
  padding: 20px;
  background: #fafafa;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.06);
}

form input[type="text"],
form input[type="email"] {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

form input[type="submit"] {
  background: #FF5722;
  border: none;
  width: 100%;
  padding: 12px;
  color: #fff;
  font-size: 1em;
  border-radius: 4px;
  cursor: pointer;
  transition: background 0.3s ease;
}

form input[type="submit"]:hover {
  background: #e64a19;
}

/* Responsive Design */
@media (max-width: 768px) {
  main, .main-content {
    margin: 20px;
    margin-top: 80px; /* same offset for the sticky nav */
    padding: 20px;
  }

  nav {
    flex-direction: column;
    gap: 10px;
  }
}


.sidebar {
  /* Adjust the width as needed */
  width: 220px;
  background-color: #333;
  color: #fff;
  padding: 20px;
  box-sizing: border-box;
  /* Optional: If you want the sidebar to take full height and stay on the left:
     position: fixed;
     top: 0;
     left: 0;
     height: 100vh;
     overflow-y: auto;
  */
}

.sidebar h2 {
  margin-top: 0;
  margin-bottom: 1em;
  font-size: 1.5em;
  border-bottom: 1px solid #444;
  padding-bottom: 10px;
}

.sidebar a {
  display: block;
  color: #fff;
  text-decoration: none;
  padding: 8px 0;
  border-radius: 4px;
  transition: background 0.3s ease, padding-left 0.3s ease;
}

.sidebar a:hover {
  background-color: #444;
  padding-left: 10px;
}

/* Optional: For mobile responsiveness */
@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    /* If you want the sidebar to appear at the top on mobile, remove fixed positioning
       and maybe place it above main content in HTML, etc. */
  }
}

  </style>
</head>

    <nav>
    <a href="homepage.php">Home</a>
    <a href="homepage.php">Shop</a>
    <a href="categories.php">Categories</a>
    <a href="cart.php">cart</a>
    <a href="profile.php">Profile</a>
    <a href="contact.php">Contact Us</a>
    <?php if(isset($_SESSION['email'])): ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
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
