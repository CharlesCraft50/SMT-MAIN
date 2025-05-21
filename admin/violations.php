<?php
    require('../js/components/ui/admin_header.php');
    $activeTab = "violations";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .violation-table {
            flex: 3;
            margin-right: 20px;
        }

        .violation-info h1 {
            margin-top: 0;
            color: #0D67A1;
        }

        .violation-info-area {
            background-color: #0D67A1;
            color: white;
            padding: 24px;
            border-radius: 18px;
            flex: 1;
        }

        .search-bar {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            padding-bottom: 13px;
        }

        .search-bar input {
            padding: 10px;
            padding-left: 20px;
            border: 1px solid #0D67A1;
            border-radius: 30px;
            width: 300px;
            margin-right: 10px;
            font-size: 16px;
        }

        .circle-button {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            background-color: #0D67A1;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .circle-button:hover {
            background-color: #095386;
        }

        .circle-button:active {
            transform: scale(0.95);
        }

        .circle-button i {
            font-size: 24px;
        }

        .violation-table {
            width: 100%;
            max-height: 700px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .violation-table th,
        .violation-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .violation-table th {
            background-color: #0D67A1;
            color: white;
            padding: 20px;
        }

        .violation-table td {
            background-color: #d9f0ff;
        }

        .violation-table tbody td:nth-child(1) {
            background-color: #aedbf7;
            transition: background-color 0.3s ease;
        }

        .violation-table tbody tr {
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .violation-table tbody tr:hover {
            background-color: #5b6c76;
        }

        .violation-table tbody tr:hover td {
            background-color: #5b6c76;
        }

        .violation-table tbody td:nth-child(1):hover {
            background-color: #d9f0ff;
        }


        .violations {
            margin-top: 10px;
            border: 1px solid #d9f0ff;
            border-radius: 18px;
            background-color: #d9f0ff;
            padding: 3px;
            color: #0D67A1;
            font-weight: 600;
            text-align: center;
        }

        .violations-area {
            padding: 30px;
        }

        .violations p {
            margin: 5px 0;
        }

        .violations strong {
            margin: 0;
            padding: 0;
            font-size: 80px;
        }

        .table-area {
            border: 1px solid #F3F3F3;
            max-height: 700px;
            min-height: 700px;
            overflow-x: hidden !important;
            background-color: #F3F3F3;
            border-radius: 18px;
            padding: 20px;
            margin: 20px;
            flex: 1;
        }

        .hamburger-button {
            background-color: #0D67A1;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            outline: none;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 5px;
        }

        .hamburger-button:hover {
            background-color: #095386;
        }

        @media (max-width: 768px) {
            .content-area {
                flex-direction: column;
            }

            .violation-table {
                margin-right: 0;
            }

            .violation-info {
                margin-top: 20px;
            }

            .search-bar input {
                width: 100%;
            }
        }

        .inline-buttons {
            display: flex;
            gap: 10px; /* Adjust the space between buttons */
        }

        #studentDropdown {
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/admin.css">
</head>

<body>
    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you really want to log out?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="../php-api/logout.php" class="btn btn-danger" style="background-color: #0D67a1; border-color: #0D67A1;">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php require('../js/components/ui/sidebar.php'); ?>

    <div class="main-content">
        <div id="responseMessage" class="modern-alert" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000;"></div>
        <div class="header">
            <div class="blue__bar">
                <h2>Violation Records</h2>
            </div>
            <div class="yellow__bar"></div>
        </div>

        <div class="content-area">
            <div class="table-area overflow-auto">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addViolationModal" id="addViolationStudent" style="background-color: #0D67A1; border-color: #0D67A1;">Add Violation</button>
                <div class="search-bar">
                  <input type="text" id="searchQuery" placeholder="Search by Student No. or Name">
                  <select id="programFilter" class="form-select" style="width: 100px;">
                      <option value="">Select Program</option>
                      <!-- Programs will be dynamically loaded here -->
                  </select>
                  <button class="circle-button" id="searchButton"><i class="bi bi-search"></i></button>
                </div>
                <table class="violation-table overflow-auto">
                    <thead>
                        <tr>
                            <th>Student No.</th>
                            <th>Student Name</th>
                            <th>Year/Program</th>
                            <th>Violation Count</th>
                            <th>Without Uniform Count</th>
                            <th>Without ID Count</th>
                            <th>Latest Violation Date</th>
                        </tr>
                    </thead>
                    <tbody class="violation-table-body">

                    </tbody>
                </table>
            </div>

            
        </div>
    </div>


    <!-- Modal for Detailed Violations -->
    <div class="modal fade" id="violationDetailsModal" tabindex="-1" role="dialog" aria-labelledby="violationDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="violationDetailsModalLabel">Student Violation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="student-info">
                        <h3>Student Information</h3>
                        <p><strong>Student No:</strong> <span id="studentNoDisplay">00000123456</span></p>
                        <p><strong>Name:</strong> <span id="studentNameDisplay">Jordan Limwell C. Marcelo</span></p>
                        <p><strong>Year/Program:</strong> <span id="studentProgramDisplay">4th BSCS</span></p>
                    </div>
                    
                    <h4 class="text-center">Violations</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Violation Type</th>
                                    <th>Violation Date</th>
                                    <th>Notes</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody id="violationDetailsTableBody">
                                <!-- Violations will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="addViolationButton">Add</button>
                    <button type="button" class="btn btn-primary" id="closeViolationModal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php require('../js/components/ui/image_modal.php'); ?>

    <!-- Add Violation Modal -->
    <div class="modal fade" id="addViolationModal" tabindex="-1" aria-labelledby="addViolationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addViolationModalLabel">Add New Violation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="addViolationForm">
              <!-- Searchable Student Input -->
              <div class="mb-3">
                <label for="studentSearchInput" class="form-label">Student</label>
                <input type="text" id="studentSearchInput" class="form-control" placeholder="Search by Student ID, Name, or Program">
                <ul id="studentDropdown" class="dropdown-menu w-100"></ul>
              </div>

              <!-- Violation Date -->
              <div class="mb-3">
                <label for="violationDateInput" class="form-label">Violation Date</label>
                <input type="datetime-local" class="form-control" id="violationDateInput">
              </div>

              <!-- Violation Type -->
              <div class="mb-3">
                <label for="violationTypeSelect" class="form-label">Violation Type</label>
                <select id="violationTypeSelect" class="form-control">
                  <option value="WithoutID">Without ID</option>
                  <option value="WithoutUniform">Without Uniform</option>
                </select>
              </div>

              <!-- Notes (Optional) -->
              <div class="mb-3">
                <label for="notesInput" class="form-label">Notes (Optional)</label>
                <textarea id="notesInput" class="form-control" rows="3" placeholder="Add notes here..."></textarea>
              </div>

              <!-- Violation Picture -->
              <div class="mb-3">
                <label for="violationPictureInput" class="form-label">Violation Picture</label>
                <input type="file" id="violationPictureInput" class="form-control">
                <img id="violationPicturePreview" src="images/placeholder.png" alt="Violation Picture" class="img-fluid mt-2" style="max-height: 150px;">
              </div>

              <!-- Violation Status -->
              <div class="mb-3">
                <label for="violationStatus" class="form-label">Violation Status</label>
                <input type="text" id="violationStatus" class="form-control" value="Pending" disabled>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="saveViolationButton">Save Violation</button>
          </div>
        </div>
      </div>
    </div>

    <?php require('../js/components/ui/add_student_modal.php'); ?>

</body>
<script src="../js/admin/violations.js"></script>
<script src="../js/admin/admin.js"></script>
</html>