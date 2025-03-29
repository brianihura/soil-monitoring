import joblib
import numpy as np
import pymysql

# Step 1: Load the saved model and preprocessing tools
model = joblib.load("crop_model.pkl")            # Load trained model
label_encoder = joblib.load("label_encoder.pkl") # Load label encoder
scaler = joblib.load("scaler.pkl")               # Load scaler

# Step 2: Connect to MySQL Database
db = pymysql.connect(host="localhost", user="root", password="", database="soil_monitoring")
cursor = db.cursor()

# Step 3: Fetch Latest Soil Data
cursor.execute("SELECT nitrogen, phosphorus, potassium, humidity, temperature FROM soil_data1 ORDER BY id DESC LIMIT 1")
row = cursor.fetchone()

if row:
    nitrogen, phosphorus, potassium, humidity, temperature = row  # Assign values

    # Step 4: Default Values for Missing Features
    pH = 6.5  # Default pH level
    rainfall = 200.0  # Default rainfall value

    # Step 5: Prepare Input for Model
    features = np.array([[nitrogen, phosphorus, potassium, temperature, humidity, pH, rainfall]])  # Use 7 features
    features_scaled = scaler.transform(features)  # Scale the features

    # Step 6: Predict Crop
    predicted_crop_index = model.predict(features_scaled)[0]
    predicted_crop = label_encoder.inverse_transform([predicted_crop_index])[0]

    # Step 7: Save result to file
    with open("output.txt", "w") as file:
        file.write(predicted_crop)
else:
    with open("output.txt", "w") as file:
        file.write("Error: No soil data found!")

# Step 8: Close Database Connection
cursor.close()
db.close()
