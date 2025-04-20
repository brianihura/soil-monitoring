<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil Monitoring System</title>
    <style>
        :root {
            --primary: #5e8b45;
            --secondary: #3c5a29;
            --accent: #8bc34a;
            --light: #f8f9f4;
            --dark: #2a3b20;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, rgba(248, 249, 244, 0.95), rgba(238, 242, 228, 0.9));
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
            border-bottom: 3px solid var(--accent);
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        nav {
            display: flex;
            justify-content: center;
            align-items: center;
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
            background-color: rgba(94, 139, 69, 0.1);
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
            border-top: 5px solid var(--accent);
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
        
        .wheat-decoration {
            position: absolute;
            width: 40px;
            height: 100px;
            opacity: 0.1;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="%235e8b45" d="M5,6A1,1 0 0,0 4,7V17H5V7H19V9H18.5C18,9 18,9 17.5,9.5C17,10 16,11.5 16,11.5C16,11.5 16,11 15.5,10.5C15,10 14.5,10 14.5,10H14V11H13.5C13,11 13,11 12.5,11.5C12,12 11,13.5 11,13.5C11,13.5 11,13 10.5,12.5C10,12 9.5,12 9.5,12H9V13H8.5C8,13 8,13 7.5,13.5C7,14 6,15.5 6,15.5C6,15.5 6,15 5.5,14.5C5,14 4.5,14 4.5,14H4V20H5V16.5C5,16.5 6,15 6.5,14.5C7,14 7.5,14 7.5,14H8V15.5C8,15.5 9,14 9.5,13.5C10,13 10.5,13 10.5,13H11V14.5C11,14.5 12,13 12.5,12.5C13,12 13.5,12 13.5,12H14V13.5C14,13.5 15,12 15.5,11.5C16,11 16.5,11 16.5,11H17V12.5C17,12.5 18,11 18.5,10.5C19,10 19.5,10 19.5,10H20V17H21V7A1,1 0 0,0 20,6H5Z" /></svg>') no-repeat center center;
        }
        
        .wheat-left {
            left: 10px;
            top: 20px;
            transform: rotate(-15deg);
        }
        
        .wheat-right {
            right: 10px;
            top: 20px;
            transform: rotate(15deg);
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
                <div class="navLinks">
                    <ul>
                        <li><a href="client.php" class="active">Home</a></li>
                        <li><a href="graph.php">Graph</a></li>
                        <li><a href="suggest1.php">Suggest</a></li>
                        <li><a href="soilhealth.php">Soil Health</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="container">
        <h1 class="dashboard-title">Soil Nutrient Monitoring</h1>
        <p class="dashboard-subtitle">Real-time soil analysis for optimal crop growth and harvest yields</p>
        
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

        // Close the database connection
        $conn->close();
        ?>

        <div class="mainview">
            <div class="sensor-card">
                <div class="wheat-decoration wheat-left"></div>
                <div class="wheat-decoration wheat-right"></div>
                <h2>Nitrogen (N)</h2>
                <svg class="progress-ring" width="120" height="120" viewBox="0 0 120 120">
                    <circle class="progress-ring-bg" cx="60" cy="60" r="52" />
                    <circle class="progress-ring-meter" cx="60" cy="60" r="52" stroke="#8bc34a" stroke-dasharray="326.73" stroke-dashoffset="<?php echo 326.73 - (326.73 * min(max($nitrogen / 300, 0), 1)); ?>" />
                </svg>
                <div class="sensor-value"><?php echo $nitrogen; ?> <span class="sensor-unit">mg/kg</span></div>
            </div>
            
            <div class="sensor-card">
                <div class="wheat-decoration wheat-left"></div>
                <div class="wheat-decoration wheat-right"></div>
                <h2>Phosphorus (P)</h2>
                <svg class="progress-ring" width="120" height="120" viewBox="0 0 120 120">
                    <circle class="progress-ring-bg" cx="60" cy="60" r="52" />
                    <circle class="progress-ring-meter" cx="60" cy="60" r="52" stroke="#8bc34a" stroke-dasharray="326.73" stroke-dashoffset="<?php echo 326.73 - (326.73 * min(max($phosphorus / 70, 0), 1)); ?>" />
                </svg>
                <div class="sensor-value"><?php echo $phosphorus; ?> <span class="sensor-unit">mg/kg</span></div>
            </div>
            
            <div class="sensor-card">
                <div class="wheat-decoration wheat-left"></div>
                <div class="wheat-decoration wheat-right"></div>
                <h2>Potassium (K)</h2>
                <svg class="progress-ring" width="120" height="120" viewBox="0 0 120 120">
                    <circle class="progress-ring-bg" cx="60" cy="60" r="52" />
                    <circle class="progress-ring-meter" cx="60" cy="60" r="52" stroke="#8bc34a" stroke-dasharray="326.73" stroke-dashoffset="<?php echo 326.73 - (326.73 * min(max($potassium / 300, 0), 1)); ?>" />
                </svg>
                <div class="sensor-value"><?php echo $potassium; ?> <span class="sensor-unit">mg/kg</span></div>
            </div>
        </div>
        
        <p class="last-updated">Last updated: <?php echo $formattedTime; ?></p>
        
        <div class="dashboard-footer">
            <p>Soil Monitoring System &copy; 2025</p>
        </div>
    </div>
</body>
</html>