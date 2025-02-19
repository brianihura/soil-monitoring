<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Suitability</title>
    <link rel="stylesheet" href="suggest.css">
</head>
<body>
<header>
    <div class="imgDiv">
        <img src="images/logo.jpg" alt="Logo">
    </div>
    <div class="navLinks">
        <nav>
            <ul>
                <li><a href="client.php">Home</a></li>
                <li><a href="graph.php">Graph</a></li>
                <li><a href="soilhealth.php">soili Health</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soil_monitoring";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch latest soil data
$sensor_sql = "SELECT nitrogen, phosphorous, potassium, moisture, temperature 
               FROM soil_data 
               ORDER BY id DESC 
               LIMIT 1";
$sensor_result = $conn->query($sensor_sql);

if ($sensor_result->num_rows > 0) {
    $sensor_data = $sensor_result->fetch_assoc();
} else {
    die("No sensor data found.");
}

// Prepare data for ML script
$nitrogen = $sensor_data['nitrogen'];
$phosphorous = $sensor_data['phosphorous'];
$potassium = $sensor_data['potassium'];
$moisture = $sensor_data['moisture'];
$temperature = $sensor_data['temperature'];

// Call Python ML script
$command = escapeshellcmd("python ml_predict.py $nitrogen $phosphorous $potassium $moisture $temperature");
$ml_output = shell_exec($command);
$ml_crop = trim($ml_output);

// Query crop requirements
$sql = "SELECT * FROM crop_requirements";
$result = $conn->query($sql);

$suitable_crops = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (
            $nitrogen >= $row['nitrogen_min'] && $nitrogen <= $row['nitrogen_max'] &&
            $phosphorous >= $row['phosphorus_min'] && $phosphorous <= $row['phosphorus_max'] &&
            $potassium >= $row['potassium_min'] && $potassium <= $row['potassium_max'] &&
            $moisture >= $row['moisture_min'] && $moisture <= $row['moisture_max'] &&
            $temperature >= $row['temperature_min'] && $temperature <= $row['temperature_max']
        ) {
            $suitable_crops[] = $row['crop_name'];
        }
    }
}

// Display results
if (!empty($suitable_crops)) {
    echo "<div class='table-container'><h2>Rule-Based Suggestion</h2><table>";
    echo "<tr><th>#</th><th>Crop Name</th></tr>";
    foreach ($suitable_crops as $index => $crop) {
        echo "<tr><td>" . ($index + 1) . "</td><td>" . $crop . "</td></tr>";
    }
    echo "</table></div>";
} else {
    echo "<p class='error'>Soil is unhealthy.</p>";
}

// Display ML-based prediction
echo "<div class='table-container'><h2>ML-Based Suggestion</h2>";
echo "<p>Predicted Best Crop: <strong>$ml_crop</strong></p>";
echo "</div>";

$conn->close();
?>
</div>
</body>
</html>
