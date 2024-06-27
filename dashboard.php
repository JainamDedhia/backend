<?php

session_start();

// Check if the user is logged in and is an admin


require 'vendor/autoload.php'; // Include Composer's autoload file

use MongoDB\Client as MongoClient;

// MongoDB connection string and options
$mongoURI = 'mongodb://localhost:27017';
$options = [];


session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    // Redirect unauthorized users to the login page
    header("Location: login.php");
    exit(); // Stop further execution
}



// Create a MongoDB client
try {
    $mongoClient = new MongoClient($mongoURI, $options);
    $db = $mongoClient->attendancetrial; // Select your database
    $collection = $db->attendance; // Select your collection
} catch (Exception $e) {
    echo "Failed to connect to MongoDB: " . $e->getMessage();
    exit;
}

// Process form submission
$attendanceRecords = [];
$filter = []; // Initialize an empty filter

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if date is set
    if (isset($_POST['date'])) {
        $selectedDate = $_POST['date'];
        $filter['date_time'] = $selectedDate; // Match against the 'date_time' field
    }

    // Check if status is set
    if (isset($_POST['status']) && $_POST['status'] != 'all') {
        $filter['status'] = $_POST['status'];
    }

    // Query MongoDB for attendance records based on the filter
    $cursor = $collection->find($filter);

    // Track unique employee_ids to prevent duplicates
    $uniqueEmployeeIds = [];

    foreach ($cursor as $document) {
        $employeeId = $document['employee_id'];
        if (!in_array($employeeId, $uniqueEmployeeIds)) {
            $uniqueEmployeeIds[] = $employeeId;
            $attendanceRecords[] = $document;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Dashboard</title>
    <style>
        body {
            font-family: cursive;
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        .container {
            margin-bottom: 30px;
        }

        .container img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            display: block;
            margin: auto;
            padding-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            margin-right: 10px;
        }

        input, select {
            padding: 8px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            padding: 8px 20px;
            background-color: black;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: rgb(75, 69, 69);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .centered_header {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="dashboardatt.jpeg" alt="Background Image">
    </div>

    <h2 class="centered_header">Attendance Dashboard</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="all">All</option>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
        </select>

        <input type="submit" value="Filter">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <?php if (!empty($attendanceRecords)): ?>
            <h3>Attendance for <?php echo htmlspecialchars($selectedDate); ?></h3>
            <table>
                <tr>
                    <th>Employee ID</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($attendanceRecords as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($record['date_time']); ?></td>
                        <td><?php echo htmlspecialchars($record['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No attendance records found<?php if (isset($selectedDate)) echo " for " . htmlspecialchars($selectedDate); ?></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
