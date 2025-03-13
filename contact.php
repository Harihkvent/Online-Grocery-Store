<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        header {
            background: #4CAF50;
            padding: 20px;
            color: white;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 5px;
            text-align: center;
        }
        iframe {
            width: 100%;
            height: 600px;
            border: none;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
         nav {
            background: #333;
            padding: 10px;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
        }
        nav a:hover {
            background: #4CAF50;
    </style>
</head>
<body>

<header>
    Contact Us
</header>
 <nav>
        <a href="homepage.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="categories.php">Categories</a>
        <a href="cart.php">Cart</a>
        <a href="contact.php">Contact Us</a>
    </nav>
<div class="container">
    <h2>We’d Love to Hear from You!</h2>
    <p>Please fill out the form below, and we’ll get back to you as soon as possible.</p>

    <!-- Replace the Google Form link below with your own -->
    <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfCKpIJjihDlS_LdRO3ZyTf0em4mAHIJtcplYTC9yXTJV_OSQ/viewform?usp=dialog"></iframe>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Grocery Store. All rights reserved.</p>
</footer>

</body>
</html>
