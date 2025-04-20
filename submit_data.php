<?php
header("Content-Type: text/plain");

// Handle both GET and POST
$nitrogen = isset($_REQUEST['nitrogen']) ? $_REQUEST['nitrogen'] : 'No data';
$phosphorus = isset($_REQUEST['phosphorus']) ? $_REQUEST['phosphorus'] : 'No data';
$potassium = isset($_REQUEST['potassium']) ? $_REQUEST['potassium'] : 'No data';

echo "Received Data: ";
echo "Nitrogen: $nitrogen, Phosphorus: $phosphorus, Potassium: $potassium\n";

// OPTIONAL: Save to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soil_monitoring"; // Make sure this matches your DB name

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Insert into table
$sql = "INSERT INTO soil_data (nitrogen, phosphorous, potassium)
        VALUES ('$nitrogen', '$phosphorus', '$potassium')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error inserting data: " . $conn->error;
}

$conn->close();
?>
