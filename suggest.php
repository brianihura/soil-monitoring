<?php
// Database connection details
$servername = "localhost";
$username = "root";        // Default XAMPP MySQL username is "root"
$password = "";            // Default password is empty
$dbname = "soil_monitoring";  // Your database name

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch crop requirements
$sql = "SELECT * FROM crop_requirements";
$result = $conn->query($sql);

// Initialize a flag to indicate if any suitable crop is found
$suitable_crops = [];

if ($result->num_rows > 0) {
    // Compare each crop's requirements to the sensor data
    while ($row = $result->fetch_assoc()) {
        // Check if the sensor data falls within the range for each nutrient and condition
        if (
            $sensor_data['nitrogen'] >= $row['nitrogen_min'] && $sensor_data['nitrogen'] <= $row['nitrogen_max'] &&
            $sensor_data['phosphorus'] >= $row['phosphorus_min'] && $sensor_data['phosphorus'] <= $row['phosphorus_max'] &&
            $sensor_data['potassium'] >= $row['potassium_min'] && $sensor_data['potassium'] <= $row['potassium_max'] &&
            $sensor_data['moisture'] >= $row['moisture_min'] && $sensor_data['moisture'] <= $row['moisture_max'] &&
            $sensor_data['temperature'] >= $row['temperature_min'] && $sensor_data['temperature'] <= $row['temperature_max']
        ) {
            $suitable_crops[] = $row['crop_name'];
        }
    }
}

// Check if suitable crops are found and display the result
if (!empty($suitable_crops)) {
    echo "Suitable crops: " . implode(", ", $suitable_crops);
} else {
    echo "soil is unhealty.";
}

// Close the database connection
$conn->close();
?>
