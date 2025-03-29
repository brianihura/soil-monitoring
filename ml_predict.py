import sys
import joblib
import numpy as np

# Load trained model and preprocessing tools
model = joblib.load("crop_model.pkl")
label_encoder = joblib.load("label_encoder.pkl")
scaler = joblib.load("scaler.pkl")

# Get input values from PHP script
try:
    nitrogen = float(sys.argv[1])
    phosphorus = float(sys.argv[2])
    potassium = float(sys.argv[3])
    moisture = float(sys.argv[4])  # In database, it's called moisture
    temperature = float(sys.argv[5])

    # Rename moisture to humidity for model compatibility
    humidity = moisture  # Model expects humidity, but we store it as moisture

    # Create feature array (Ensure correct order for model)
    features = np.array([[nitrogen, phosphorus, potassium, temperature, humidity]])

    # Scale the features before prediction
    features_scaled = scaler.transform(features)

    # Predict crop
    predicted_crop_index = model.predict(features_scaled)[0]
    predicted_crop = label_encoder.inverse_transform([predicted_crop_index])[0]

    # Print the predicted crop (so PHP can read it)
    print(predicted_crop)

except IndexError:
    print("Error: Missing input values")
except ValueError:
    print("Error: Invalid input format")
