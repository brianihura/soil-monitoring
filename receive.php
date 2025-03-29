<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soil_monitoring";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sample values (replace with real sensor data)
$nitrogen = 90;
$phosphorous = 50;
$potassium = 150;
$moisture = 50;
$temperature = 25;

// Insert into soil_data
$sql1 = "INSERT INTO soil_data (nitrogen, phosphorous, potassium, moisture, temperature)VALUES ('$nitrogen', '$phosphorous', '$potassium', '$moisture', '$temperature')";

// Insert into soil_data1 (renaming moisture as humidity)
$sql2 = "INSERT INTO soil_data1 (nitrogen, phosphorus, potassium, humidity, temperature)VALUES ('$nitrogen', '$phosphorous', '$potassium', '$moisture', '$temperature')";

// Execute both queries
if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
    echo "Data inserted successfully!";
} else {
    echo "Error: " . $conn->error;
}

// Close connection
$conn->close();
?>
