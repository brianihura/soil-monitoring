import joblib
import pandas as pd
import pymysql

# Load model and preprocessing tools
model = joblib.load("crop_model.pkl")
label_encoder = joblib.load("label_encoder.pkl")
scaler = joblib.load("scaler.pkl")

# Connect to MySQL
db = pymysql.connect(host="localhost", user="root", password="", database="soil_monitoring")
cursor = db.cursor()

# Fetch latest NPK data from sensor_data
cursor.execute("SELECT nitrogen, phosphorus, potassium FROM sensor_data ORDER BY id DESC LIMIT 1")
row = cursor.fetchone()

if row:
    nitrogen, phosphorus, potassium = row

    # Prepare data with feature names used in training
    input_data = pd.DataFrame([{
        'N': nitrogen,
        'P': phosphorus,
        'K': potassium
    }])

    # Scale and predict
    input_scaled = scaler.transform(input_data)
    predicted_index = model.predict(input_scaled)[0]
    predicted_crop = label_encoder.inverse_transform([predicted_index])[0]

    # Save result to file
    with open("output.txt", "w") as f:
        f.write(predicted_crop)
else:
    with open("output.txt", "w") as f:
        f.write("Error: No data found!")

# Close DB
cursor.close()
db.close()
