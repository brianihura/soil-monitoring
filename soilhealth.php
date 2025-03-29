<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil Health Analysis</title>
    <header>
    <div class="navLinks">
        <nav>
            <ul>
                <li><a href="client.php">Home</a></li>
                <li><a href="graph.php">Graph</a></li>
                <li><a href="suggest1.php">suggest</a></li>
            </ul>
        </nav>
    </div>
</header>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            margin: 0;
            padding: 0;
            background-image: linear-gradient(rgba(8, 14, 10, 0.7),rgba(24, 34, 30, 0.8)), url(3.jpg);
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
        }
        h1 {
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .healthy {
            color: green;
            font-weight: bold;
        }
        .unhealthy {
            color: red;
            font-weight: bold;
        }
        .solution-box {
            margin-top: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 5px solid #f44336;
            text-align: left;
        }
        .solution-box h3 {
            margin: 0;
            color: #f44336;
        }
        .solution-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        header {
    background-color:  rgba(96, 190, 143, 0.9); /* Dark color with slight transparency */
    padding: 10px 20px;
    border-bottom: 4px solid rgba(255, 255, 255, 0.3); /* Subtle highlight */
    display: flex; /* Use flexbox for header layout */
    justify-content: space-between; /* Distribute space between the logo and nav */
    align-items: center; /* Vertically align items */
    font-size: 1.5rem;
    color: #ffffff;
    position: sticky; /* Keeps the header fixed at the top */
    top: 0;
    z-index: 10; /* Ensures the header stays above other content */
}

header nav ul {
    list-style-type: none; /* Remove default list style */
    display: flex; /* Align list items horizontally */
    margin: 0;
    padding: 0;
}

header nav ul li {
    margin-left: 20px; /* Space between the list items */
}

header a {
    color: #f4f4f9; /* Light link color */
    text-decoration: none; /* Remove underline from links */
}

header a:hover {
    color: aqua; /* Highlight links on hover */
    text-decoration: underline; /* Add underline on hover */
}

    </style>
</head>
<body>

<div class="container">
    <h1>Soil Health Analysis</h1>

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "soil_monitoring";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the latest soil data
    $sql = "SELECT nitrogen, phosphorous, potassium, moisture, temperature FROM soil_data ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $n = $data['nitrogen'];
        $p = $data['phosphorous'];
        $k = $data['potassium'];
        $moisture = $data['moisture'];
        $temperature = $data['temperature'];

        // Ideal soil health conditions (Kenya)
        $n_min = 100; $n_max = 300;  // Moderate nitrogen level
        $p_min = 20;  $p_max = 50;   // Balanced phosphorus level
        $k_min = 100; $k_max = 250;  // Moderate potassium level
        $moisture_min = 60; $moisture_max = 70; // Ideal moisture range
        $temp_min = 20; $temp_max = 28;  // Optimal temperature range

        // Determine soil health
        $problems = [];
        $solutions = [];

        if ($n < $n_min) {
            $problems[] = "Nitrogen level is too low.";
            $solutions[] = "Apply organic manure or nitrogen-rich fertilizers like urea.";
        } elseif ($n > $n_max) {
            $problems[] = "Nitrogen level is too high.";
            $solutions[] = "Reduce fertilizer application and plant nitrogen-consuming crops like maize.";
        }

        if ($p < $p_min) {
            $problems[] = "Phosphorus level is too low.";
            $solutions[] = "Use phosphate fertilizers like DAP (Diammonium Phosphate).";
        } elseif ($p > $p_max) {
            $problems[] = "Phosphorus level is too high.";
            $solutions[] = "Avoid overuse of phosphorus fertilizers and improve soil aeration.";
        }

        if ($k < $k_min) {
            $problems[] = "Potassium level is too low.";
            $solutions[] = "Apply potassium-rich fertilizers like Muriate of Potash (MOP).";
        } elseif ($k > $k_max) {
            $problems[] = "Potassium level is too high.";
            $solutions[] = "Increase soil organic matter and avoid over-fertilization.";
        }

        if ($moisture < $moisture_min) {
            $problems[] = "Soil moisture is too low.";
            $solutions[] = "Increase irrigation and apply mulching to retain soil moisture.";
        } elseif ($moisture > $moisture_max) {
            $problems[] = "Soil moisture is too high.";
            $solutions[] = "Improve drainage to prevent waterlogging.";
        }

        if ($temperature < $temp_min) {
            $problems[] = "Soil temperature is too low.";
            $solutions[] = "Use mulching to retain heat and avoid over-irrigation.";
        } elseif ($temperature > $temp_max) {
            $problems[] = "Soil temperature is too high.";
            $solutions[] = "Provide shade and increase irrigation to cool the soil.";
        }

        $health_status = empty($problems) ? "<span class='healthy'>Healthy</span>" : "<span class='unhealthy'>Unhealthy</span>";

        // Display data in a table
        echo "<table>
                <tr>
                    <th>Nutrient</th>
                    <th>Measured Value</th>
                    <th>Ideal Range</th>
                </tr>
                <tr><td>Nitrogen</td><td>$n mg/L</td><td>100 - 300 mg/L</td></tr>
                <tr><td>Phosphorus</td><td>$p mg/L</td><td>20 - 50 mg/L</td></tr>
                <tr><td>Potassium</td><td>$k mg/L</td><td>100 - 250 mg/L</td></tr>
                <tr><td>Moisture</td><td>$moisture%</td><td>60 - 70%</td></tr>
                <tr><td>Temperature</td><td>$temperature °C</td><td>20 - 28°C</td></tr>
            </table>";

        // Display soil health status
        echo "<h2>Soil Health Status: $health_status</h2>";

        // Display problems and solutions if soil is unhealthy
        if (!empty($problems)) {
            echo "<div class='solution-box'>";
            echo "<h3>Identified Problems:</h3><ul>";
            foreach ($problems as $problem) {
                echo "<li>$problem</li>";
            }
            echo "</ul><h3>Recommended Solutions:</h3><ul>";
            foreach ($solutions as $solution) {
                echo "<li>$solution</li>";
            }
            echo "</ul></div>";
        }

    } else {
        echo "<p>No sensor data found in the database.</p>";
    }

    $conn->close();
    ?>

</div>
</header>
</body>
</html>
