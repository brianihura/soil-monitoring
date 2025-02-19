import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
import joblib  # To save the trained model

# Load dataset
cropdf = pd.read_csv("../input/crop-recommendation-dataset/Crop_recommendation.csv")  # Ensure this file exists

# Features (NPK, Moisture, Temperature)
X = df[['nitrogen', 'phosphorous', 'potassium', 'moisture', 'temperature']]
y = df['crop_label']  # Target: Crop names or encoded labels

# Split into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train Decision Tree Model
model = DecisionTreeClassifier()
model.fit(X_train, y_train)

# Save the trained model
joblib.dump(model, "crop_prediction_model.pkl")

print("Model trained and saved successfully!")
