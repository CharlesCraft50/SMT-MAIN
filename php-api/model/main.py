from fastapi import FastAPI, File, UploadFile
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
import numpy as np
import uvicorn
from keras.saving import register_keras_serializable
import tensorflow as tf
import cv2
from ultralytics import YOLO
from typing import List
from pathlib import Path
from collections import defaultdict

UPLOAD_ROOT = Path("../content/manual_uploads")

# Constants
IMG_WIDTH = 224
IMG_HEIGHT = 224
CLASS_NAMES = ['Non-Uniform', 'Uniform']

# Preprocessing function (REGISTERED for Keras deserialization)
@register_keras_serializable()
def vgg_preprocess(img):
    img = img * 255.0
    img = img[..., ::-1]  # RGB to BGR
    mean = [103.939, 116.779, 123.68]
    return img - mean

# Load models
yolo_model = YOLO("yolov8n.pt")  # Ensure this file is available in the working directory
uniform_model = tf.keras.models.load_model("uniform_detector_model_best.keras")

# Crop the nearest (most confident) person from the image
def crop_nearest_person(image):
    results = yolo_model(image)
    boxes = results[0].boxes.xyxy.cpu().numpy()
    class_ids = results[0].boxes.cls.cpu().numpy()
    confidences = results[0].boxes.conf.cpu().numpy()

    # Filter only person detections (class id == 0)
    person_detections = [
        (box, conf)
        for box, cls_id, conf in zip(boxes, class_ids, confidences)
        if int(cls_id) == 0
    ]

    if not person_detections:
        return None  # No person detected

    # Sort by confidence and then area
    person_detections.sort(
        key=lambda x: (x[1], (x[0][2] - x[0][0]) * (x[0][3] - x[0][1])), reverse=True
    )
    best_box, _ = person_detections[0]
    x1, y1, x2, y2 = map(int, best_box)
    cropped = image[y1:y2, x1:x2]
    return cropped

# Inference function
def predict_uniform_status(image: np.ndarray):
    resized = cv2.resize(image, (IMG_WIDTH, IMG_HEIGHT)) / 255.0
    processed = vgg_preprocess(resized)
    input_tensor = np.expand_dims(processed, axis=0)
    prediction = uniform_model.predict(input_tensor)[0][0]
    label = "Non-Uniform" if prediction < 0.5 else "Uniform"
    confidence = float(1 - prediction) if label == "Non-Uniform" else float(prediction)
    return label, confidence

# FastAPI app
app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
def read_root():
    return {"message": "Welcome to the Uniform Detection API!"}

@app.get("/favicon.ico")
def favicon():
    return None  # Returning no content for favicon.ico

@app.post("/predict/")
async def predict(file: UploadFile = File(...)):
    contents = await file.read()
    nparr = np.frombuffer(contents, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

    cropped_person = crop_nearest_person(img)
    if cropped_person is None:
        return JSONResponse({"error": "No person detected"}, status_code=400)

    label, confidence = predict_uniform_status(cropped_person)

    return {
        "prediction": label,
        "confidence": round(confidence, 2)
    }

@app.get("/predict/manual_folder/")
def predict_manual_folder():
    results = defaultdict(list)

    # Loop through each student folder
    for student_folder in UPLOAD_ROOT.iterdir():
        if not student_folder.is_dir():
            continue  # Skip files

        student_name = student_folder.name
        image_files = list(student_folder.glob("*.[jp][pn]g"))  # jpg, jpeg, png

        for img_path in image_files:
            try:
                img = cv2.imread(str(img_path))
                if img is None:
                    results[student_name].append({
                        "filename": img_path.name,
                        "error": "Could not read image"
                    })
                    continue

                cropped_person = crop_nearest_person(img)
                if cropped_person is None:
                    results[student_name].append({
                        "filename": img_path.name,
                        "error": "No person detected"
                    })
                    continue

                label, confidence = predict_uniform_status(cropped_person)

                results[student_name].append({
                    "filename": img_path.name,
                    "prediction": label,
                    "confidence": round(confidence, 2)
                })

            except Exception as e:
                results[student_name].append({
                    "filename": img_path.name,
                    "error": str(e)
                })

    return results

# Uncomment below to run with `python main.py`
if __name__ == "__main__":
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)
