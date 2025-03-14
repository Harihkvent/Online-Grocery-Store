<?php
session_start();
include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grocery Store Homepage</title>
  <style>
    /* General Styles */
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: #f9f9f9;
  color: #333;
}

/* Header */
header {
  background: linear-gradient(135deg, #4CAF50, #2E7D32);
  padding: 25px 20px;
  color: white;
  text-align: center;
  font-size: 22px;
  font-weight: 600;
}

/* Navigation Bar */
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #222;
  padding: 12px 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.nav-links {
  display: flex;
  gap: 20px;
}

nav a {
  color: white;
  font-size: 16px;
  text-decoration: none;
  padding: 10px 15px;
  transition: 0.3s;
  border-radius: 5px;
}

nav a:hover {
  background-color: #4CAF50;
  color: white;
}

/* Search Bar */
.search-container {
  display: flex;
}

.search-container input {
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px 0 0 4px;
  outline: none;
  width: 200px;
}

.search-container button {
  padding: 8px 12px;
  border: none;
  background: #4CAF50;
  color: white;
  border-radius: 0 4px 4px 0;
  cursor: pointer;
  transition: 0.3s;
}

.search-container button:hover {
  background: #388E3C;
}

/* Hero Section */
.hero {
  background: url('images/grocery-hero.jpg') no-repeat center center/cover;
  height: 400px;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-overlay {
  background: rgba(0, 0, 0, 0.6);
  color: white;
  padding: 20px 40px;
  font-size: 40px;
  font-weight: bold;
  border-radius: 10px;
}

/* Content Section */
.content {
  text-align: center;
  padding: 40px;
}

.content a {
  background: #4CAF50;
  color: white;
  padding: 12px 24px;
  font-size: 18px;
  border-radius: 6px;
  text-decoration: none;
  transition: 0.3s;
}

.content a:hover {
  background: #388E3C;
}

/* Product Section */
.items-container {
  max-width: 1200px;
  margin: 40px auto;
  text-align: center;
}

.items-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.item-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: 0.3s;
}

.item-card:hover {
  transform: translateY(-5px);
}

.item-card img {
  width: 100%; /* Ensures images fill the container */
  height: 200px; /* Set a fixed height */
  object-fit: cover; /* Maintain aspect ratio and cover the area */
  border-radius: 8px 8px 0 0;
}


.item-card h3 {
  margin: 15px 0 10px;
  font-size: 20px;
}

.item-card p {
  font-size: 16px;
}

.offer {
  color: #D32F2F;
  font-weight: bold;
}

/* Buttons */
.btn-group {
  margin-top: 15px;
}

.btn {
  padding: 10px 14px;
  text-decoration: none;
  font-size: 14px;
  border-radius: 5px;
  color: white;
  transition: 0.3s;
}

.add-to-cart {
  background: #2196F3;
}

.buy-now {
  background: #FF5722;
}

.add-to-cart:hover {
  background: #1E88E5;
}

.buy-now:hover {
  background: #E64A19;
}

/* Footer */
footer {
  background: #222;
  color: white;
  text-align: center;
  padding: 15px;
  margin-top: 30px;
  font-size: 14px;
}

  </style>
</head>
<body>
  <header>
    <h1>Welcome to Our Grocery Store</h1>
    <?php 
      if(isset($_SESSION['email'])): 
        $email = $_SESSION['email'];
        $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
        if($row = mysqli_fetch_array($query)) {
          echo "<p>Hello, " . $row['firstName'] . " " . $row['lastName'] . "!</p>";
        }
      endif;
    ?>
  </header>

  <nav>
    <a href="homepage.php">Home</a>
    <a href="homepage.php">Shop</a>
    <a href="categories.php">Categories</a>
    <a href="cart.php">cart</a>
    <a href="contact.php">Contact Us</a>
    <?php if(isset($_SESSION['email'])): ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
    <?php endif; ?>

    <!-- Search Form -->
    <div class="search-container">
      <form action="homepage.php" method="GET">
        <input type="text" name="search" placeholder="Search items..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
      </form>
    </div>
  </nav>

  <section class="hero">
    <div class="hero-overlay">
      Fresh Groceries Delivered
    </div>
  </section>

  <div class="content">
    <h2>Featured Products</h2>
    <p>Discover the freshest produce and quality products available at unbeatable prices.</p>
    <a href="shop.php">Shop Now</a>
  </div>

  <!-- START: Items Display Section -->
  <div class="items-container">
    <h2>Our Latest Items</h2>
    <?php
      // Check if a search query is present
      $whereClause = '';
      if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        // Sanitize user input
        $search = mysqli_real_escape_string($conn, trim($_GET['search']));
        // Search in name, description, or offers
        $whereClause = "WHERE name LIKE '%$search%' 
                        OR description LIKE '%$search%' 
                        OR offers LIKE '%$search%'";
      }

      // Query the database for items
      $sql = "SELECT * FROM `items` $whereClause ORDER BY id DESC";
      $result = mysqli_query($conn, $sql);

      if ($result && mysqli_num_rows($result) > 0) {
        echo '<div class="items-grid">';
        while ($item = mysqli_fetch_assoc($result)) {
          echo '<div class="item-card">';
            // Display item photo
            echo '<img src="uploads/' . htmlspecialchars($item['photo']) . '" alt="' . htmlspecialchars($item['name']) . '">';
            
            // Display item name
            echo '<h3>' . htmlspecialchars($item['name']) . '</h3>';
            
            // Display price
            echo '<p>$' . number_format($item['price'], 2) . '</p>';
            
            // Display description
            if(!empty($item['description'])) {
              echo '<p>' . htmlspecialchars($item['description']) . '</p>';
            }
            
            // Display offer if available
            if(!empty($item['offers'])) {
              echo '<p class="offer">' . htmlspecialchars($item['offers']) . '</p>';
            }
            
            // Add to Cart and Buy Now Buttons
            echo '<div class="btn-group">';
              echo '<a class="btn add-to-cart" href="cart.php?action=add&id=' . $item['id'] . '">Add to Cart</a>';
              echo '<a class="btn buy-now" href="checkout.php?id=' . $item['id'] . '">Buy Now</a>';
            echo '</div>';
            
          echo '</div>';
        }
        echo '</div>';
      } else {
        if(isset($search) && $search !== '') {
          echo '<p>No items found matching your search.</p>';
        } else {
          echo '<p>No items found.</p>';
        }
      }
    ?>
  </div>
  <!-- END: Items Display Section -->

  <footer>
    <p>&copy; <?php echo date("Y"); ?> Grocery Store. All rights reserved.</p>
  </footer>
</body>
</html>
