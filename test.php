<?php
// db_connect.php
$servername = "localhost";
$username = "root";        // Default XAMPP MySQL username is "root"
$password = "";            // Default password is empty
$dbname = "soil_monitoring";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}
echo "hello";
?>