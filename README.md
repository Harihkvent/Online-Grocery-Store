# Online Grocery Store

## Project Overview
This is an Online Grocery Store web application built using PHP, MySQL, HTML, CSS, and JavaScript. Users can browse categories, add items to a cart, place orders, and track their purchases.

## Prerequisites
Before running the project locally, ensure you have the following installed:

- **XAMPP** (Recommended) or WAMP for running a local server.
- **PHP** (if not using XAMPP/WAMP)
- **MySQL Database**
- **Web Browser**

## Installation & Setup

### Step 1: Download and Extract the Project
1. Download the project ZIP file and extract it.
2. Move the extracted `Online-Grocery-Store` folder to your local web server directory:
   - For **XAMPP**: `C:\xampp\htdocs\`
   - For **WAMP**: `C:\wamp\www\`

### Step 2: Configure the Database
1. Start Apache and MySQL from XAMPP/WAMP control panel.
2. Open your browser and go to: `http://localhost/phpmyadmin/`
3. Create a new database (e.g., `grocery_store`).
4. Import the provided SQL file into the database:
   - Locate `database.sql` in the project files.
   - Click on **Import** in phpMyAdmin and upload `database.sql`.

### Step 3: Configure Database Connection
1. Open the `connect.php` file in the project folder.
2. Update the database credentials if needed:
   ```php
   $host = "localhost";
   $user = "root"; // Default user for XAMPP/WAMP
   $password = ""; // Default password is empty
   $database = "grocery_store";
   ```

### Step 4: Run the Project
1. Open your browser and visit:
   ```
   http://localhost/Online-Grocery-Store/
   ```
2. You should see the homepage of the Online Grocery Store.
3. Admin Dashboard (if available) can be accessed via:
   ```
   http://localhost/Online-Grocery-Store/admin_dashboard.php
   ```

## Features
- User Registration & Login
- Product Categorization
- Shopping Cart & Checkout
- Order Tracking
- Admin Panel for Managing Products & Orders

## Troubleshooting
- If the project does not load, ensure Apache and MySQL are running.
- Check if `connect.php` has the correct database credentials.
- If database tables are missing, re-import `database.sql`.

## Author
Developed by Harikiran
