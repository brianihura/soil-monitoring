<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="soilhealth.css">
    <title>Soil Health Analysis</title>
</head>
<body>
    <header>
    <div class="navLinks">
        <nav>
            <ul>
            <li><a href="client.php">Home</a></li>
                    <li><a href="graph.php">Graph</a></li>
                    <li><a href="suggest1.php">Suggest</a></li>
                    <li><a href="soilhealth.php">soil Health</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="container">
    <h1>Soil Health Analysis</h1>
    
    <form method="post">
        <label for="crop">Select crop you want to plant:</label>
        <select name="crop" id="crop">
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
            
            // Get list of available crops
            $crop_sql = "SELECT crop_name FROM crop_requirements";
            $crop_result = $conn->query($crop_sql);
            
            if ($crop_result->num_rows > 0) {
                while($row = $crop_result->fetch_assoc()) {
                    $selected = isset($_POST['crop']) && $_POST['crop'] == $row['crop_name'] ? 'selected' : '';
                    echo "<option value='".$row['crop_name']."' $selected>".$row['crop_name']."</option>";
                }
            } else {
                echo "<option value=''>No crops found</option>";
            }
            ?>
        </select>
        <input type="submit" value="Analyze Soil Health">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crop'])) {
        $selected_crop = $_POST['crop'];
        
        // Fetch the latest soil data
        $sql = "SELECT nitrogen, phosphorus, potassium FROM sensor_data ORDER BY id DESC LIMIT 1";
        $result = $conn->query($sql);
        
        // Fetch the crop requirements
        $crop_req_sql = "SELECT nitrogen_min, nitrogen_max, phosphorus_min, phosphorus_max, potassium_min, potassium_max FROM crop_requirements WHERE crop_name = '$selected_crop'";
        $crop_req_result = $conn->query($crop_req_sql);
        
        if ($result->num_rows > 0 && $crop_req_result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $crop_req = $crop_req_result->fetch_assoc();
            
            $nitrogen = $data['nitrogen'];
            $phosphorus = $data['phosphorus'];
            $potassium = $data['potassium'];
            
            // Get optimal ranges for the selected crop
            $nitrogen_min = $crop_req['nitrogen_min']; 
            $nitrogen_max = $crop_req['nitrogen_max'];
            $phosphorus_min = $crop_req['phosphorus_min']; 
            $phosphorus_max = $crop_req['phosphorus_max'];
            $potassium_min = $crop_req['potassium_min']; 
            $potassium_max = $crop_req['potassium_max'];

            // Determine soil health for the specific crop
            $problems = [];
            $solutions = [];

            if ($nitrogen < $nitrogen_min) {
                $problems[] = "Nitrogen level is too low for $selected_crop.";
                $solutions[] = "Apply organic manure or nitrogen-rich fertilizers like urea to increase nitrogen levels.";
            } elseif ($nitrogen > $nitrogen_max) {
                $problems[] = "Nitrogen level is too high for $selected_crop.";
                $solutions[] = "Reduce nitrogen fertilizer application and consider planting nitrogen-consuming cover crops before $selected_crop.";
            }

            if ($phosphorus < $phosphorus_min) {
                $problems[] = "Phosphorus level is too low for $selected_crop.";
                $solutions[] = "Apply phosphate fertilizers like DAP (Diammonium Phosphate) or bone meal.";
            } elseif ($phosphorus > $phosphorus_max) {
                $problems[] = "Phosphorus level is too high for $selected_crop.";
                $solutions[] = "Avoid phosphorus fertilizers and improve soil aeration. Consider planting phosphorus-hungry cover crops.";
            }

            if ($potassium < $potassium_min) {
                $problems[] = "Potassium level is too low for $selected_crop.";
                $solutions[] = "Apply potassium-rich fertilizers like Muriate of Potash (MOP) or use wood ash.";
            } elseif ($potassium > $potassium_max) {
                $problems[] = "Potassium level is too high for $selected_crop.";
                $solutions[] = "Increase soil organic matter and avoid potassium-rich fertilizers.";
            }

            $health_status = empty($problems) ? 
                "<span class='healthy'>Healthy for $selected_crop</span>" : 
                "<span class='unhealthy'>Unhealthy for $selected_crop</span>";

            // Display data in a table
            echo "<h2>Analysis for: $selected_crop</h2>";
            echo "<table>
                    <tr>
                        <th>Nutrient</th>
                        <th>Measured Value</th>
                        <th>Optimal Range for $selected_crop</th>
                    </tr>
                    <tr><td>Nitrogen</td><td>$nitrogen mg/kg</td><td>$nitrogen_min - $nitrogen_max mg/kg</td></tr>
                    <tr><td>Phosphorus</td><td>$phosphorus mg/kg</td><td>$phosphorus_min - $phosphorus_max mg/kg</td></tr>
                    <tr><td>Potassium</td><td>$potassium mg/kg</td><td>$potassium_min - $potassium_max mg/kg</td></tr>
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
            } else {
                echo "<div class='solution-box success'>";
                echo "<p>Your soil is well-suited for growing $selected_crop based on NPK levels!</p>";
                echo "</div>";
            }
        } else {
            if ($result->num_rows == 0) {
                echo "<p>No sensor data found in the database.</p>";
            }
            if ($crop_req_result->num_rows == 0) {
                echo "<p>No requirements found for the selected crop: $selected_crop.</p>";
            }
        }
    }

    $conn->close();
    ?>

</div>
</body>
</html>