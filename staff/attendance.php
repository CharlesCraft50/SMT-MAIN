<?php
session_start();
if (!isset($_SESSION['student'])) {
  // If not set, exit the script or redirect to a different page
  echo "No student session found. Exiting.";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="main-content">
        <div class="header">
            <div class="blue__bar">
            </div>
            <div class="yellow__bar"></div>
        </div>

        <div class="container-fluid d-flex flex-column flex-lg-row align-items-center justify-content-center mt-1">
            <!-- Student Info Section -->
            <div class="content-area col-lg-6 col-md-12 d-flex flex-column align-items-center justify-content-center text-center me-lg-2 ms-lg-5">
                <div class="student-info-area text-center w-100">
                    <h1 class="text-center mb-4 text-white">Student Information</h1>
                    <div class="d-flex align-items-center mb-3 p-3 rounded w-100 bg-lightblue">
                        <strong class="fs-4 flex-fill text-start">Student No:</strong>
                        <p class="fs-3 flex-fill text-end mb-0" id="studentNoDisplay"></p>
                    </div>
                    <div class="d-flex align-items-center mb-3 p-3 rounded w-100 bg-lightblue">
                        <strong class="fs-4 flex-fill text-start">Name:</strong>
                        <p class="fs-3 flex-fill text-end mb-0" id="studentNameDisplay"></p>
                    </div>
                    <div class="d-flex align-items-center mb-3 p-3 rounded w-100 bg-lightblue">
                        <strong class="fs-4 flex-fill text-start">Year Level:</strong>
                        <p class="fs-3 flex-fill text-end mb-0" id="studentYearDisplay"></p>
                    </div>
                    <div class="d-flex align-items-center p-3 rounded w-100 bg-lightblue">
                        <strong class="fs-4 flex-fill text-start">Program:</strong>
                        <p class="fs-3 flex-fill text-end mb-0" id="studentCourseDisplay"></p>
                    </div>

                    <div class="d-flex align-items-center p-3 rounded w-100 bg-lightblue mt-3">
                        <strong class="fs-4 flex-fill text-start" id="totalViolationsText">Total Violations:</strong>
                        <p class="fs-3 flex-fill text-end mb-0" id="studentTotalViolations"></p>
                    </div>
                </div>
            </div>

            <!-- Camera Section -->
            <div class="content-area col-lg-6 col-md-12 d-flex flex-column align-items-center justify-content-center text-center me-lg-5 mt-4 mt-lg-0">
                <div class="card shadow-lg border-0 rounded-4 text-center">
                    <video id="video" autoplay playsinline class="rounded border shadow w-100 mb-3"></video>
                    <canvas id="canvas" class="rounded border shadow w-100 d-none"></canvas>

                    
                    <div id="camera-ui" class="text-center">

                        <div class="d-flex justify-content-center gap-3 mb-3 d-none">
                            <button id="startCamera" class="btn btn-outline-primary px-4">
                                <i class="bi bi-camera-video"></i> Start Camera
                            </button>
                            <button id="capture" class="btn btn-primary px-4" disabled>
                                <i class="bi bi-camera"></i> Capture Photo
                            </button>
                        </div>

                        <div class="p-5 pt-0">
                            <div id="timer" class="text-danger fw-bold fs-1 mb-2">2</div>
                            <div id="instruction" class="text-danger fs-5 mb-3">Please face the camera with your uniform or ID visible.</div>

                            <!-- Loading Spinner -->
                            <div id="loading">
                                <div class="spinner mb-2"></div>
                                <div id="loading-text" class="text-center">Processing... Please wait.</div>
                            </div>
                        </div>
                        
                    </div>

                    <!-- Floating Message Box -->
                    <div id="responseMessage" class="modern-alert position-fixed top-50 start-50 translate-middle d-none"></div>
                </div>
            </div>
        </div>


    
    </div>

</body>
<?php require('../js/staff/attendance.js.php') ?>
</html>