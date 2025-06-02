<?php
session_start();
if (!isset($_SESSION['student'])) {
  echo "No student session found. Exiting.";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
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

        <div class="container-fluid mt-5">
            <div class="row justify-content-center g-3">
                <!-- First Column -->
                <div class="col-md-4 mt-5">
                    <div class="content-area d-flex flex-column align-items-center justify-content-center text-center">
                        <div class="text-center w-100">
                            <h1 class="text-start mt-3 mb-0 fw-bolder display-4" style="color: #0D67A1;">Student</h1>
                            <h1 class="text-start mb-1 mt-0 fw-bolder display-4" style="color: #0D67A1;">Information</h1>
                            <div class="d-flex align-items-center rounded w-100 mb-1" style="color: #0D67A1;">
                                <strong class="fs-3 fw-bold text-start">Name:</strong>
                                <p class="fs-3 text-start mb-0 ms-1" id="studentNameDisplay"></p>
                            </div>
                            <div class="d-flex align-items-center rounded w-100 mb-1" style="color: #0D67A1;">
                                <strong class="fs-3 fw-bold text-start">Student No:</strong>
                                <p class="fs-3 text-start mb-0 ms-1" id="studentNoDisplay"></p>
                            </div>
                            <div class="d-flex align-items-center rounded w-100 mb-1" style="color: #0D67A1;">
                                <strong class="fs-3 fw-bold text-start">Year Level:</strong>
                                <p class="fs-3 text-start mb-0 ms-1" id="studentYearDisplay"></p>
                            </div>
                            <div class="d-flex align-items-center rounded w-100 mb-1" style="color: #0D67A1;">
                                <strong class="fs-3 fw-bold text-start">Program:</strong>
                                <p class="fs-3 text-start mb-0 ms-1" id="studentCourseDisplay"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Column -->
                <div class="col-md-4">
                    <div class="row">
                        <div class="content-area d-flex flex-column align-items-center justify-content-center text-center">
                            <div class="student-info-area text-center w-100">
                                <h1 class="text-center mb-4 text-white">Yearly Record</h1>
                                <div class="d-flex align-items-center mb-3 py-4 ps-2 rounded w-100 bg-lightblue">
                                    <strong class="fs-4 flex-fill text-start">Pending Violations:</strong>
                                    <p class="fs-3 flex-fill text-end mb-0 mx-3" id="studentTotalPendingViolations">0</p>
                                </div>
                                <div class="d-flex align-items-center mb-3 py-4 px-2 rounded w-100 bg-lightblue">
                                    <strong class="fs-4 flex-fill text-start">Attendance Record:</strong>
                                    <p class="fs-3 flex-fill text-end mb-0 mx-3" id="studentTotalAttendance">0</p>
                                </div>
                                <div class="d-flex align-items-center mb-3 py-4 px-2 rounded w-100 bg-lightblue">
                                    <strong class="fs-4 flex-fill text-start">Total Violations</strong>
                                    <p class="fs-3 flex-fill text-end mb-0 mx-3" id="studentTotalViolations">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <button type="button" id="closeBtn" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center mt-2 rounded-pill" style="height: auto; width: auto; background-color: #0D67A1;">
                                <span class="fs-4" style="margin-right: 0.5rem;">DONE</span>
                                <i class="bi bi-box-arrow-in-right fs-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require('../js/components/ui/image_modal.php'); ?>
    </div>

</body>
<?php require('../js/student/student_information.js.php') ?>
</html>