<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
 <div class = "login-container">
        <form action="login.php" method="POST">
            <h2> Login </h2>
            <img src="login.jpeg" alt="Login Image">
            <div class="input-group">
                <label for="username">Name:</label>
                <input placeholder="Enter your Name" type="text" id="username" name="username" required >
            </div>
            <br>
            <div class = "input-group">
            <label for="password">Password:</label>
            <input placeholder="Enter your Password" type="password" id="password" name="password" required>
        </div>
        <br><br>
        <button type="submit">Login</button>
        </form>
    </div>

<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

use MongoDB\Client as MongoClient;

session_start(); // Start the session

// Check if session exists
if (isset($_SESSION['username'])) {
    // Redirect to appropriate page based on role
    if ($_SESSION['role'] == 'admin') {
        header("Location: adlacadmin.php");
        exit();
    } else {
        header("Location: adlacuser.html");
        exit();
    }
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $client = new MongoClient("mongodb://localhost:27017");
        $collection = $client->user_management->users;

        $user = $collection->findOne(['username' => $username]);

        if ($user && password_verify($password, $user['password'])) {
            // Start a session and store user information
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            // Redirect to appropriate page based on role
            if ($user['role'] == 'admin') {
                header("Location: adlacadmin.php");
            } else {
                header("Location: adlacuser.html");
            }
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } catch (Exception $e) {
        echo "Error: Could not connect to MongoDB: " . $e->getMessage();
    }
}
?>

</body>
</html>
