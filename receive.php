<?php
header("Content-Type: text/plain");

// Handle both GET and POST
$nitrogen = isset($_REQUEST['nitrogen']) ? intval($_REQUEST['nitrogen']) : -1;
$phosphorus = isset($_REQUEST['phosphorus']) ? intval($_REQUEST['phosphorus']) : -1;
$potassium = isset($_REQUEST['potassium']) ? intval($_REQUEST['potassium']) : -1;

echo "Received Data: ";
echo "Nitrogen: $nitrogen, Phosphorus: $phosphorus, Potassium: $potassium\n";

// Only insert if all values are > 0
if ($nitrogen > 0 && $phosphorus > 0 && $potassium > 0) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "soil_monitoring";

    // Create DB connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Insert into table
    $sql = "INSERT INTO sensor_data (nitrogen, phosphorus, potassium)
            VALUES ('$nitrogen', '$phosphorus', '$potassium')";

    if ($conn->query($sql) === TRUE) {
        echo "Data inserted successfully";
    } else {
        echo "Error inserting data: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid data. Skipping insert.";
}
?>
