from fastapi import FastAPI, File, UploadFile
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
import numpy as np
import uvicorn
import cv2
from ultralytics import YOLO
from pathlib import Path
from typing import List
from collections import defaultdict

# -----------------------------
# Configuration
# -----------------------------

# Folder where manually uploaded images are organized by student
UPLOAD_ROOT = Path("../content/manual_uploads")

# Define the classes used by the YOLO model
CLASS_NAMES = ['uniform', 'no_uniform']

# Load the YOLOv8 model 
yolo_model = YOLO("best.pt")


# -----------------------------
# Initialize FastAPI app
# -----------------------------

app = FastAPI()

# Enable CORS so the API can be called from a web frontend (like React or Vue)
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Allow all domains
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# -----------------------------
# Routes
# -----------------------------

@app.get("/")
def read_root():
    return {"message": "Uniform Detection API is running."}

@app.get("/favicon.ico")
def favicon():
    return None  # Prevent unnecessary browser favicon requests


# -----------------------------
# Utility: Get the largest detection from the image
# -----------------------------

def get_largest_detection(image: np.ndarray):
    """
    Detects objects in the image using the YOLO model and returns the largest detection (by area).
    """
    results = yolo_model(image)
    boxes = results[0].boxes

    if boxes is None or len(boxes) == 0:
        return None

    max_area = -1
    best_detection = None

    for box in boxes:
        cls_id = int(box.cls[0])  # Class ID
        conf = float(box.conf[0])  # Confidence
        x1, y1, x2, y2 = map(int, box.xyxy[0])  # Bounding box
        area = (x2 - x1) * (y2 - y1)

        if area > max_area:
            max_area = area
            best_detection = {
                "label": CLASS_NAMES[cls_id],
                "confidence": round(conf, 2),
                "bbox": [x1, y1, x2, y2]
            }

    return best_detection


# -----------------------------
# POST /predict/ (single image upload)
# -----------------------------

@app.post("/predict/")
async def predict(file: UploadFile = File(...)):
    """
    Accepts an image upload and returns the top prediction (uniform / no_uniform).
    """
    contents = await file.read()
    nparr = np.frombuffer(contents, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

    if img is None:
        return JSONResponse({"error": "Invalid image"}, status_code=400)

    detection = get_largest_detection(img)

    if detection is None:
        return JSONResponse({"error": "No detections found"}, status_code=400)

    return detection


# -----------------------------
# GET /predict/manual_folder/ (batch prediction on folder structure)
# -----------------------------

@app.get("/predict/manual_folder/")
def predict_manual_folder():
    """
    Scans each student folder inside UPLOAD_ROOT and returns predictions for each image.
    Expects structure: /manual_uploads/{student_name}/{image_files}
    """
    results = defaultdict(list)

    for student_folder in UPLOAD_ROOT.iterdir():
        if not student_folder.is_dir():
            continue

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

                detection = get_largest_detection(img)
                if detection is None:
                    results[student_name].append({
                        "filename": img_path.name,
                        "error": "No person detected"
                    })
                else:
                    results[student_name].append({
                        "filename": img_path.name,
                        "prediction": detection["label"],
                        "confidence": detection["confidence"]
                    })

            except Exception as e:
                results[student_name].append({
                    "filename": img_path.name,
                    "error": str(e)
                })

    return results


# -----------------------------
# Entry point for local testing (VS Code debugger, terminal, etc.)
# -----------------------------

if __name__ == "__main__":
    uvicorn.run("main:app", host="127.0.0.1", port=8000, reload=True)