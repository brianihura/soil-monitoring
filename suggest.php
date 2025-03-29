<?php
// Execute Python script and capture output
$command = escapeshellcmd("python ml.py");
$output = shell_exec($command);

// Check for errors
if ($output === null) {
    $prediction = "Error: Unable to fetch prediction.";
} else {
    $prediction = trim($output);
}

?><?php
// Run the Python script
exec("python ml.py");

// Read the output file
$outputFile = "output.txt";

if (file_exists($outputFile)) {
    $prediction = file_get_contents($outputFile);
} else {
    $prediction = "Prediction not available";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crop Prediction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        .container {
            margin: auto;
            width: 50%;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #4CAF50;
        }
        .result {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Predicted Crop</h2>
    <div class="result"><?php echo htmlspecialchars($prediction); ?></div>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil Health Prediction</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .result {
            font-size: 24px;
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Soil Health Monitoring System</h2>
    <p>Predicted Crop Recommendation:</p>
    <div class="result"><?php echo $prediction; ?></div>
</body>
</html>
