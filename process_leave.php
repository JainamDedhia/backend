<?php
// MongoDB connection (assuming you have the MongoDB PHP library installed)
require 'vendor/autoload.php'; // Include Composer's autoloader

use MongoDB\Client;

// MongoDB connection
$mongoClient = new Client('mongodb://localhost:27017');
$database = $mongoClient->leave_management;
$collection = $database->leave_applications;

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $start_date = isset($_POST['start_date']) ? htmlspecialchars($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? htmlspecialchars($_POST['end_date']) : '';
    $reason = isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : '';

    // Example employee_id (you should implement proper authentication and session handling)
    $employee_id = isset($_POST['employee_id']) ? htmlspecialchars($_POST['employee_id']) : ''; // Retrieve employee ID from form input

    // Validate dates (optional - depends on your specific requirements)
    if (!isValidDate($start_date) || !isValidDate($end_date)) {
        die('Invalid date format');
    }

    // Convert dates to MongoDB\BSON\UTCDateTime
    $start_date = new MongoDB\BSON\UTCDateTime(strtotime($start_date) * 1000);
    $end_date = new MongoDB\BSON\UTCDateTime(strtotime($end_date) * 1000);

    // Insert the leave application into MongoDB
    $insertResult = $collection->insertOne([
        'employee_id' => $employee_id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'reason' => $reason,
        'status' => 'pending' // Initial status
    ]);

    if ($insertResult->getInsertedCount() > 0) {
        echo "Leave application submitted successfully.";
    } else {
        echo "Failed to submit leave application.";
    }
}

// Function to validate date format (example function)
function isValidDate($date) {
    return (bool)strtotime($date);
}
?>
