<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="client.css">
    <title>Soil Monitoring</title>
</head>
<body>
    <!-- Blurred Background Overlay -->
    <div class="blur-background"></div>

    <!-- Content Section -->
    <header>
        <div class="imgDiv">
            <img src="" alt="Soil Health Monitoring">
        </div>
        <div class="navLinks">
            <nav>
                <ul>
                    <li><a href="graph.php">Graph</a></li>
                    <li><a href="suggest1.php">Suggest</a></li>
                    <li><a href="soilhealth.php">soil Health</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="mainview">
        <?php
        // Database connection
        $servername = "localhost";
        $username = "root"; // Default username for XAMPP
        $password = ""; // Default password for XAMPP
        $dbname = "soil_monitoring"; // Replace with your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch the latest data from the database
        $sql = "SELECT * FROM soil_data ORDER BY timestamp DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch data as an associative array
            $row = $result->fetch_assoc();

            // Extract sensor data
            $temperature = $row['temperature'];
            $moisture = $row['moisture'];
            $nitrogen = $row['nitrogen'];
            $phosphorous = $row['phosphorous'];
            $potassium = $row['potassium'];
        } else {
            // Set default values if no data is found
            $temperature = "N/A";
            $moisture = "N/A";
            $nitrogen = "N/A";
            $phosphorous = "N/A";
            $potassium = "N/A";
        }

        // Close the database connection
        $conn->close();
        ?>
        <div class="temparature">
            <h1>Temperature</h1>
            <p><i><?php echo $temperature . " °C"; ?></i></p>
        </div>
        <div class="moisture">
            <h1>Moisture</h1>
            <p><i><?php echo $moisture . " %"; ?></i></p>
        </div>
        <div class="npk">
            <h2>NPK</h2>
            <div class="phosphorous">
                <h1>Phosphorous</h1>
                <i><?php echo $phosphorous . " mg/L"; ?></i>
            </div>
            <div class="potassium">
                <h1>Potassium</h1>
                <i><?php echo $potassium . " mg/L"; ?></i>
            </div>
            <div class="nitrogen">
                <h1>Nitrogen</h1>
                <i><?php echo $nitrogen . " mg/L"; ?></i>
            </div>
        </div>
    </div>
</body>
</html>
