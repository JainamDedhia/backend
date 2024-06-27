<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer's autoload file
require 'vendor/autoload.php';

use MongoDB\Client as MongoClient;

// MongoDB connection string and options
$mongoURI = 'mongodb://localhost:27017';
$options = [];

// Create a MongoDB client
try {
    $mongoClient = new MongoClient($mongoURI, $options);
} catch (Exception $e) {
    echo json_encode(["message" => "Failed to connect to MongoDB: " . $e->getMessage()]);
    exit;
}

// Select your database and collection
$databaseName = 'attendancetrial';
$collectionName = 'attendance';
try {
    $database = $mongoClient->$databaseName;
    $collection = $database->$collectionName;
} catch (Exception $e) {
    echo json_encode(["message" => "Failed to select database or collection: " . $e->getMessage()]);
    exit;
}

// Receive latitude and longitude from frontend
if (!isset($_POST['latitude']) || !isset($_POST['longitude']) || !isset($_POST['id']) || !isset($_POST['dob'])) {
    echo json_encode(["message" => "Invalid input."]);
    exit;
}

$employeeLatitude = (float) $_POST['latitude'];
$employeeLongitude = (float) $_POST['longitude'];
$id = $_POST['id'];
$dob = $_POST['dob'];

// Designated location coordinates
$designatedLatitude = 19.195114;
$designatedLongitude = 72.973338;

// Calculate distance using Haversine formula
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371.0; // Radius of the Earth in kilometers

    // Convert latitude and longitude from degrees to radians
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    // Haversine formula
    $dLat = $lat2 - $lat1;
    $dLon = $lon2 - $lon1;

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos($lat1) * cos($lat2) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Distance in meters
    $distance = $earthRadius * $c * 1000;

    return $distance;
}

$distance = calculateDistance($designatedLatitude, $designatedLongitude, $employeeLatitude, $employeeLongitude);

// Check if an attendance record exists for this employee and date
$existingRecord = $collection->findOne(['employee_id' => $id, 'date_time' => $dob]);

if ($existingRecord) {
    // Check if employee is within 100 meters
    if ($distance <= 100) {
        echo json_encode(["message" => "Employee is within the designated location. Attendance record is intact."]);
    } else {
        // Remove attendance record if the employee is outside 100 meters
        $deleteResult = $collection->deleteOne(['employee_id' => $id, 'date_time' => $dob]);
        if ($deleteResult->getDeletedCount() == 1) {
            echo json_encode(["message" => "Employee is outside the designated location. Attendance record removed."]);
        } else {
            echo json_encode(["message" => "Failed to remove attendance record."]);
        }
    }
} else {
    echo json_encode(["message" => "No existing attendance record found."]);
}
?>
