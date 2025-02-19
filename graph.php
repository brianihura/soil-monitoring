<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soil_monitoring";  // Your database name

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the necessary data for the graph
$sql = "SELECT nitrogen, phosphorous, potassium, moisture, temperature, timestamp FROM soil_data ORDER BY timestamp DESC LIMIT 7";
$result = $conn->query($sql);

// Initialize arrays to hold data for the graphs
$nutrientData = [];
$moistureData = [];
$temperatureData = [];
$labels = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Store nutrient data in an array (nitrogen, phosphorus, potassium)
        $nutrientData[] = [
            'nitrogen' => $row['nitrogen'],
            'phosphorous' => $row['phosphorous'],
            'potassium' => $row['potassium']
        ];
        // Store moisture and temperature data
        $moistureData[] = $row['moisture'];
        $temperatureData[] = $row['temperature'];
        // Store formatted timestamp
        $labels[] = date('d M', strtotime($row['timestamp']));
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="graph.css">
  <title>Soil Data Dashboard</title>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header>
        <div class="imgDiv">
            <img src="images/logo.jpg" alt="logo not seen lol!">
        </div>
        <div class="navLinks">
            <nav>
                <ul>
                    <li><a href="client.php">Home</a></li>
                    <li><a href="suggest1.php">suggest</a></li>
                    <li><a href="soilhealth.php">soil Health</a></li>

                </ul>
            </nav>
        </div>
    </header>

<div class="chart-container">
  <canvas id="nutrientChart"></canvas>
</div>
<div class="chart-container">
  <canvas id="moistureChart"></canvas>
</div>
<div class="chart-container">
  <canvas id="temperatureChart"></canvas>
</div>

<script>
  // Data from PHP embedded directly in the page
  const dataFromPHP = <?php echo json_encode([
      'nutrient' => $nutrientData,
      'moisture' => $moistureData,
      'temperature' => $temperatureData,
      'labels' => array_reverse($labels)  // Reversing the labels for chronological order
  ]); ?>;

  const labels = dataFromPHP.labels;

  // Initialize nutrient chart
  const nutrientChart = new Chart(document.getElementById('nutrientChart'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Nitrogen (mg/L)',
          data: dataFromPHP.nutrient.map(n => n.nitrogen),
          borderColor: 'rgb(75, 192, 192)',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          fill: true
        },
        {
          label: 'Phosphorous (mg/L)',
          data: dataFromPHP.nutrient.map(n => n.phosphorous),
          borderColor: 'rgb(54, 162, 235)',
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          fill: true
        },
        {
          label: 'Potassium (mg/L)',
          data: dataFromPHP.nutrient.map(n => n.potassium),
          borderColor: 'rgb(255, 159, 64)',
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          fill: true
        }
      ]
    },
    options: {
      responsive: true
    }
  });

  // Initialize moisture chart
  const moistureChart = new Chart(document.getElementById('moistureChart'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Moisture Content (%)',
        data: dataFromPHP.moisture,
        borderColor: 'rgb(54, 162, 235)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        fill: true
      }]
    },
    options: {
      responsive: true
    }
  });

  // Initialize temperature chart
  const temperatureChart = new Chart(document.getElementById('temperatureChart'), {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Temperature (°C)',
        data: dataFromPHP.temperature,
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        fill: true
      }]
    },
    options: {
      responsive: true
    }
  });

</script>

</body>
</html>
