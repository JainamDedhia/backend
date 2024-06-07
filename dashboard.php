<?php
require_once 'mongod_connect.php'; // Include MongoDB connection script

// MongoDB collection
$collection = $db->attendance; // Replace 'attendance' with your collection name

// Process form submission
$attendanceRecords = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {
    $selectedDate = $_POST['date'];

    // Query MongoDB for attendance records for the selected date
    $filter = ['date' => new MongoDB\BSON\UTCDateTime(strtotime($selectedDate) * 1000)];
    $cursor = $collection->find($filter);

    foreach ($cursor as $document) {
        $attendanceRecords[] = $document;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Attendance Dashboard</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="date">Select Date:</label>
        <input type="date" id="date" name="date" required>
        <input type="submit" value="Filter">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])): ?>
        <h3>Attendance for <?php echo date('Y-m-d', strtotime($selectedDate)); ?></h3>
        <?php if (!empty($attendanceRecords)): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach ($attendanceRecords as $record): ?>
                    <tr>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo date('Y-m-d', $record['date']->toDateTime()->getTimestamp()); ?></td>
                        <td><?php echo $record['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No attendance records found for <?php echo date('Y-m-d', strtotime($selectedDate)); ?></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
