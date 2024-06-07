<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// MongoDB connection parameters
$mongoHost = 'localhost'; // MongoDB server host
$mongoPort = 27017;       // MongoDB server port
$mongoDB = 'attendance_db'; // MongoDB database name

// Connect to MongoDB server
try {
    $mongoClient = new MongoDB\Client("mongodb://$mongoHost:$mongoPort");
    $db = $mongoClient->$mongoDB;
    echo "Connected successfully to MongoDB.";
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error connecting to MongoDB: " . $e->getMessage();
    exit;
}
?>
