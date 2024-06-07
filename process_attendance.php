<?php
require_once 'mongod_connect.php'; // Include MongoDB connection script

// Retrieve form data
$name = $_POST['name'];
$date = $_POST['date'];
$status = $_POST['status'];

// MongoDB collection
$collection = $db->attendance; // Replace 'attendance' with your collection name

// Data to insert into MongoDB
$data = [
    'name' => $name,
    'date' => new MongoDB\BSON\UTCDateTime(strtotime($date) * 1000), // Convert date to MongoDB date format
    'status' => $status
];

// Insert data into MongoDB
try {
    $insertOneResult = $collection->insertOne($data);
    echo "Attendance record inserted successfully. Inserted ID: " . $insertOneResult->getInsertedId();
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error inserting attendance record: " . $e->getMessage();
}
?>
