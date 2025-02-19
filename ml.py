import sys
import numpy as np
import pandas as pd
import joblib  # To load the trained ML model

# Load the trained model
model = joblib.load("crop_prediction_model.pkl")  # Ensure this file exists

# Get soil data from command line arguments
if len(sys.argv) != 6:
    print("Invalid input")
    sys.exit()

nitrogen = float(sys.argv[1])
phosphorous = float(sys.argv[2])
potassium = float(sys.argv[3])
moisture = float(sys.argv[4])
temperature = float(sys.argv[5])

# Create an input array for prediction
input_data = np.array([[nitrogen, phosphorous, potassium, moisture, temperature]])

# Make prediction
predicted_crop = model.predict(input_data)[0]
print(predicted_crop)
