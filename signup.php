<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sign-styles.css">
    <title>Sign Up Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .signup-container {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .signup-container img {
            display: block;
            margin: 0 auto 20px;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .input-group input,
        .input-group select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .input-group select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>');
            background-repeat: no-repeat;
            background-position-x: calc(100% - 10px);
            background-position-y: center;
            background-size: 18px;
            padding-right: 30px;
        }
        .input-group button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .input-group button:hover {
            background-color: #45a049;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .success-message {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .error-message {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="signup-container">
    <form id="signup-form" action="signup.php" method="POST">
        <img src="signup.jpg" alt="Sign Up Image">
        <div class="input-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="input-group">
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit">Sign Up</button>
    </form>
    <div class="login-link">
        <p>Already have an account? <a href="login.php">Click here to login</a></p>
    </div>

    <?php
    require 'vendor/autoload.php'; // Include Composer's autoloader
    
    use MongoDB\Client as MongoClient;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
    
        try {
            $client = new MongoClient("mongodb://localhost:27017");
            $collection = $client->user_management->users;
    
            // Check for duplicate username
            $existingUser = $collection->findOne(['username' => $username]);
            if ($existingUser) {
                echo "<div class='error-message'>Username already exists. Please choose a different username.</div>";
                exit();
            }
    
            // Check for duplicate email
            $existingEmail = $collection->findOne(['email' => $email]);
            if ($existingEmail) {
                echo "<div class='error-message'>Email already exists. Please choose a different email.</div>";
                exit();
            }
    
            $user = [
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role' => $role
            ];
    
            $insertResult = $collection->insertOne($user);
    
            if ($insertResult->getInsertedCount() > 0) {
                echo "<div class='success-message'>Registration successful! Welcome, $username.</div>";
                // Trigger JavaScript to show the modal
                echo "<script>document.addEventListener('DOMContentLoaded', function() {
                          document.getElementById('signupSuccessModal').style.display = 'block';
                      });</script>";
            } else {
                echo "<div class='error-message'>Error: Could not sign up.</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error-message'>Error: Could not connect to MongoDB: " . $e->getMessage() . "</div>";
        }
    }
    ?>

</div>

<!-- The Modal -->
<div id="signupSuccessModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Thank you for signing up!</h2>
        <p>Your registration was successful.</p>
        <p>You can now log in using your credentials.</p>
    </div>
</div>

<script>
    // Function to close the modal
    function closeModal() {
        document.getElementById('signupSuccessModal').style.display = 'none';
    }
</script>

</body>
</html>
