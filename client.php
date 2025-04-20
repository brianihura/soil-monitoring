<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Soil Monitoring System</title>
    <style>
        :root {
            --primary: #2c8865;
            --secondary: #1e5c45;
            --accent: #3dcc91;
            --light: #f4f9f6;
            --dark: #1a3c2e;
            --danger: #e63946;
            --warning: #f9c74f;
            --success: #43aa8b;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, rgba(244, 249, 246, 0.95), rgba(230, 244, 237, 0.9));
            color: var(--dark);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .blur-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.gardeningknowhow.com/wp-content/uploads/2020/12/soil-health.jpg') no-repeat center center;
            background-size: cover;
            filter: blur(8px);
            opacity: 0.15;
            z-index: -1;
        }
        
        header {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 1rem 0;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .brand img {
            height: 40px;
        }
        
        .navLinks ul {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .navLinks a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }
        
        .navLinks a:hover {
            color: var(--primary);
            background-color: rgba(44, 136, 101, 0.1);
        }
        
        .navLinks a.active {
            color: white;
            background-color: var(--primary);
        }
        
        .dashboard-title {
            text-align: center;
            margin: 2rem 0;
            font-size: 2.2rem;
            color: var(--primary);
            position: relative;
        }
        
        .dashboard-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background-color: var(--accent);
            margin: 0.5rem auto;
            border-radius: 2px;
        }
        
        .dashboard-subtitle {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 400;
            color: var(--secondary);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .mainview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 1rem;
            margin-bottom: 3rem;
        }
        
        .sensor-card {
            background-color: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .sensor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        .sensor-card h2 {
            color: var(--primary);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .sensor-card .icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--accent);
        }
        
        .sensor-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 1rem 0;
            color: var(--dark);
        }
        
        .sensor-unit {
            color: var(--secondary);
            font-size: 1rem;
            font-weight: 500;
        }
        
        .sensor-status {
            margin-top: 1rem;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: inline-block;
        }
        
        .status-optimal {
            background-color: rgba(67, 170, 139, 0.2);
            color: var(--success);
        }
        
        .status-warning {
            background-color: rgba(249, 199, 79, 0.2);
            color: #d97706;
        }
        
        .status-critical {
            background-color: rgba(230, 57, 70, 0.2);
            color: var(--danger);
        }
        
        .last-updated {
            text-align: center;
            margin-top: 1rem;
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .dashboard-footer {
            text-align: center;
            margin: 3rem 0 1rem;
            font-size: 0.9rem;
            color: var(--secondary);
        }
        
        .progress-ring {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            position: relative;
        }
        
        .progress-ring circle {
            fill: none;
            stroke-width: 8;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            stroke-linecap: round;
        }
        
        .progress-ring .progress-ring-bg {
            stroke: #e5e7eb;
        }
        
        .progress-ring .progress-ring-meter {
            stroke: var(--accent);
            transition: stroke-dashoffset 0.5s ease;
        }
        
        @media (max-width: 768px) {
            .mainview {
                grid-template-columns: 1fr;
            }
            
            .navLinks ul {
                gap: 1rem;
            }
            
            .sensor-value {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Blurred Background Overlay -->
    <div class="blur-background"></div>

    <!-- Header Section -->
    <header>
        <div class="container">
            <nav>
                <div class="brand">
                    <span>🌱 AgriSense</span>
                </div>
                <div class="navLinks">
                    <ul>
                        <li><a href="client.php" class="active">Dashboard</a></li>
                        <li><a href="graph.php">Analytics</a></li>
                        <li><a href="suggest1.php">Recommendations</a></li>
                        <li><a href="soilhealth.php">Soil Health</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="container">
        <h1 class="dashboard-title">Smart Soil Monitoring Dashboard</h1>
        <p class="dashboard-subtitle">Real-time soil nutrient analysis for optimal crop health and sustainable farming practices</p>
        
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
        $sql = "SELECT * FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch data as an associative array
            $row = $result->fetch_assoc();

            // Extract sensor data
            $nitrogen = $row['nitrogen'];
            $phosphorus = $row['phosphorus'];
            $potassium = $row['potassium'];
            $timestamp = $row['timestamp'];
            
            // Format the timestamp
            $formattedTime = date("F j, Y, g:i a", strtotime($timestamp));
        } else {
            // Set default values if no data is found
            $nitrogen = "0";
            $phosphorus = "0";
            $potassium = "0";
            $formattedTime = "No data available";
        }

        // Define status based on nutrient levels
        function getStatus($value, $nutrient) {
            switch ($nutrient) {
                case 'nitrogen':
                    if ($value < 140) return ["warning", "Low"];
                    else if ($value > 250) return ["critical", "High"];
                    else return ["optimal", "Optimal"];
                case 'phosphorus':
                    if ($value < 25) return ["warning", "Low"];
                    else if ($value > 50) return ["critical", "High"];
                    else return ["optimal", "Optimal"];
                case 'potassium':
                    if ($value < 150) return ["warning", "Low"];
                    else if ($value > 250) return ["critical", "High"];
                    else return ["optimal", "Optimal"];
                default:
                    return ["warning", "Unknown"];
            }
        }
        
        $nStatus = getStatus($nitrogen, 'nitrogen');
        $pStatus = getStatus($phosphorus, 'phosphorus');
        $kStatus = getStatus($potassium, 'potassium');

        // Close the database connection
        $conn->close();
        ?>

        <div class="mainview">
            <div class="sensor-card">
                <div class="icon">🌿</div>
                <h2>Nitrogen (N)</h2>
                <svg class="progress-ring" width="120" height="120" viewBox="0 0 120 120">
                    <circle class="progress-ring-bg" cx="60" cy="60" r="52" />
                    <circle class="progress-ring-meter" cx="60" cy="60" r="52" stroke="<?php echo $nStatus[0] === 'optimal' ? '#43aa8b' : ($nStatus[0] === 'warning' ? '#f9c74f' : '#e63946'); ?>" stroke-dasharray="326.73" stroke-dashoffset="<?php echo 326.73 - (326.73 * min(max($nitrogen / 300, 0), 1)); ?>" />
                </svg>
                <div class="sensor-value"><?php echo $nitrogen; ?> <span class="sensor-unit">mg/kg</span></div>
                <div class="sensor-status status-<?php echo $nStatus[0]; ?>"><?php echo $nStatus[1]; ?></div>
            </div>
            
            <div class="sensor-card">
                <div class="icon">🧪</div>
                <h2>Phosphorus (P)</h2>
                <svg class="progress-ring" width="120" height="120" viewBox="0 0 120 120">
                    <circle class="progress-ring-bg" cx="60" cy="60" r="52" />
                    <circle class="progress-ring-meter" cx="60" cy="60" r="52" stroke="<?php echo $pStatus[0] === 'optimal' ? '#43aa8b' : ($pStatus[0] === 'warning' ? '#f9c74f' : '#e63946'); ?>" stroke-dasharray="326.73" stroke-dashoffset="<?php echo 326.73 - (326.73 * min(max($phosphorus / 70, 0), 1)); ?>" />
                </svg>
                <div class="sensor-value"><?php echo $phosphorus; ?> <span class="sensor-unit">mg/kg</span></div>
                <div class="sensor-status status-<?php echo $pStatus[0]; ?>"><?php echo $pStatus[1]; ?></div>
            </div>
            
            <div class="sensor-card">
                <div class="icon">⚡</div>
                <h2>Potassium (K)</h2>
                <svg class="progress-ring" width="120" height="120" viewBox="0 0 120 120">
                    <circle class="progress-ring-bg" cx="60" cy="60" r="52" />
                    <circle class="progress-ring-meter" cx="60" cy="60" r="52" stroke="<?php echo $kStatus[0] === 'optimal' ? '#43aa8b' : ($kStatus[0] === 'warning' ? '#f9c74f' : '#e63946'); ?>" stroke-dasharray="326.73" stroke-dashoffset="<?php echo 326.73 - (326.73 * min(max($potassium / 300, 0), 1)); ?>" />
                </svg>
                <div class="sensor-value"><?php echo $potassium; ?> <span class="sensor-unit">mg/kg</span></div>
                <div class="sensor-status status-<?php echo $kStatus[0]; ?>"><?php echo $kStatus[1]; ?></div>
            </div>
        </div>
        
        <p class="last-updated">Last updated: <?php echo $formattedTime; ?></p>
        
        <div class="dashboard-footer">
            <p>AgriSense Smart Soil Monitoring System &copy; 2025</p>
        </div>
    </div>
</body>
</html>