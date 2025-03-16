<?php 

include 'connect.php';
if(isset($_POST['signUp'])){
    $firstName = mysqli_real_escape_string($conn, $_POST['fName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Encrypt password
    $password = md5($password);

    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if($result->num_rows > 0){
        echo "Email Address Already Exists!";
    } else {
        // Insert new user with Address & Phone
        $insertQuery = "INSERT INTO users (firstName, lastName, email, password, address, phone)
                        VALUES ('$firstName', '$lastName', '$email', '$password', '$address', '$phone')";

        if($conn->query($insertQuery) === TRUE){
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}



if(isset($_POST['signIn'])){
   $email = $_POST['email'];
   $password = $_POST['password'];
   
   // Check for admin credentials before the regular user query
   if($email === 'admin@admin.com' && $password === 'admin'){
       session_start();
       $_SESSION['email'] = $email;
       header("Location: admin_dashboard.php");
       exit();
   }
   
   // For non-admin, encrypt the password and check in the users table
   $password = md5($password);
   $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
   $result = $conn->query($sql);
   
   if($result->num_rows > 0){
       session_start();
       $row = $result->fetch_assoc();
       $_SESSION['email'] = $row['email'];
       header("Location: homepage.php");
       exit();
   } else {
       echo "Not Found, Incorrect Email or Password";
   }
}

?>