<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Suitability</title>
    <link rel="stylesheet" href="suggest.css">
</head>
<body><header>
        <div class="imgDiv">
            <img src="images/logo.jpg" alt="nnnn">
        </div>
        <div class="navLinks">
            <nav>
                <ul>
                    <li><a href="client.php">Home</a></li>
                    <li><a href="graph.php">Graph</a></li>

                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
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

// Query to fetch the latest sensor data from the soil_data table
$sensor_sql = "SELECT nitrogen, phosphorous, potassium, moisture, temperature 
               FROM soil_data 
               ORDER BY id DESC 
               LIMIT 1"; // Fetch the most recent entry
$sensor_result = $conn->query($sensor_sql);

if ($sensor_result->num_rows > 0) {
    $sensor_data = $sensor_result->fetch_assoc();
} else {
    die("No sensor data found in the database.");
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
            $sensor_data['phosphorous'] >= $row['phosphorus_min'] && $sensor_data['phosphorous'] <= $row['phosphorus_max'] &&
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
    echo "<div class='table-container'><table>";
    echo "<tr><th>#</th><th>Crop Name</th></tr>";
    foreach ($suitable_crops as $index => $crop) {
        echo "<tr><td>" . ($index + 1) . "</td><td>" . $crop . "</td></tr>";
    }
    echo "</table></div>";
} else {
    echo "<p class='error'>No suitable crops found for the current soil conditions.</p>";
}

// Close the database connection
$conn->close();
?>
</div>
</body>
</html>