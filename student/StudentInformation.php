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

                    <button type="button" id="closeBtn" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center mt-3" style="height: auto; width: auto; background-color: #D9F0FF;">
                        <span class="fs-3" style="margin-right: 0.5rem; color: #0D67A1;">CLOSE</span>
                        <i class="bi bi-box-arrow-in-right fs-3" style="color: #0D67A1;"></i>
                    </button>
                </div>
            </div>


            <!-- Violation Table Section -->
            <div class="container-fluid content-area col-lg-6 col-md-12 d-flex flex-column align-items-center justify-content-center text-center ms-lg-2 me-lg-5 mt-4 mt-lg-0">
                <div class="student-info-area violation-table-area w-100">
                    <h1 class="text-center mb-4 text-white">Violations</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped bg-white">
                            <thead>
                                <tr>
                                    <th>Violation Type</th>
                                    <th>Violation Date</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                </tr>
                            </thead>
                            <tbody id="violationDetailsTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php require('../js/components/ui/image_modal.php'); ?>
    </div>

</body>
<?php require('../js/student/student_information.js.php') ?>
</html>