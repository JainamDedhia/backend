<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
 
    <style>
        body {
    font-family: cursive;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f2f2f2;
}

.leave-form-container {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    width: 400px;
}

.leave-form-container h2 {
    margin-bottom: 20px;
    text-align: center;
}

.input-group {
    margin-bottom: 20px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
}

.input-group input,
.input-group textarea {
    width: 96%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none; /* Prevent textarea resizing */
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 5px;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
.leave-application-image {
    width: 80px; /* Set the width of the image */
    height: auto; /* Maintain aspect ratio */
    position: relative;
    left: 170px;
    bottom: 20px;
    top: 9px;
}
h2 {
    
    top: 10px ;
    right: 90px;
    
}
input[type="text"],
input[type="date"],
select {
    width: calc(40% - 20px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    
   
}

.input-group1 input,
.input-group1 textarea {
    width: 96%;
    padding: 13px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none; /* Prevent textarea resizing */
    height: 67%;
}
        .date-group {
            display: flex;
            justify-content: space-between;
            gap: 10px; /* Reduce the gap to bring fields closer */
            margin-bottom: 20px;
        }

        .date-group label {
            width: 15%; /* Adjust label width */
        }

        .date-group input {
            width: calc(80% - 0px); /* Adjust input width to be longer */
            padding: 10px; /* Increase padding for larger input box */
        }
    </style>
</head>
<body>
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect unauthorized users to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}
?>
    <div class="leave-form-container">
        <img src="leave.jpeg" alt="Leave Application Image" class="leave-application-image">
        <h2>Leave Application</h2>
        <br>
        <form action="process_leave.php" method="post">
        
        <br>
            <div class="input-group1">
                <label for="employee_id">Employee ID:</label>
                <input type="text" id="employee_id" name="employee_id" required>
            </div>
            <br>
            <div class="date-group">
                <div class="input-group">
                    <label for="start_date">From:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="input-group">
                    <label for="end_date">To:</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div>
            </div>
            
            <div class="input-group1">
                <label for="reason">Leave Description:</label>
                <textarea id="reason" name="reason" required></textarea>
            </div>
            <br>
            <button type="submit">Apply for Leave</button>
        </form>
    </div>
</body>
</html>
