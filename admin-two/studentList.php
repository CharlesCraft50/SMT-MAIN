<?php
    require('../js/components/ui/admin_header.php');
    $activeTab = "studentList";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .student-table {
            flex: 3;
            margin-right: 20px;
        }

        .student-info h1 {
            margin-top: 0;
            color: #0D67A1;
        }

        .student-info-area {
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

        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .student-table th,
        .student-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        .student-table th {
            background-color: #0D67A1;
            color: white;
            padding: 20px;
        }

        .student-table td {
            background-color: #d9f0ff;
        }

        .student-table tbody td:nth-child(1) {
            background-color: #aedbf7;
            transition: background-color 0.3s ease;
        }

        .student-table tbody tr {
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .student-table tbody tr:hover {
            background-color: #5b6c76;
        }

        .student-table tbody tr:hover td {
            background-color: #5b6c76;
        }

        .student-table tbody td:nth-child(1):hover {
            background-color: #d9f0ff;
        }


        .card-1 {
            margin-top: 10px;
            border: 1px solid #d9f0ff;
            border-radius: 18px;
            background-color: #d9f0ff;
            padding: 3px;
            color: #0D67A1;
            font-weight: 600;
            text-align: center;
        }

        .rfid-area {
            padding: 30px;
            display: none;
        }

        .card-1 p {
            margin: 5px 0;
        }

        .card-1 strong {
            margin: 0;
            padding: 0;
            font-size: 80px;
        }

        .table-area {
            border: 1px solid #F3F3F3;
            background-color: #F3F3F3;
            border-radius: 18px;
            padding: 20px;
            margin: 20px;
            flex: 1;
        }

        .rfidForm {
            display: none;
        }

        @media (max-width: 768px) {
            .content-area {
                flex-direction: column;
            }

            .student-table {
                margin-right: 0;
            }

            .student-info {
                margin-top: 20px;
            }

            .search-bar input {
                width: 100%;
            }
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

    <?php require('../js/components/ui/sidebarTwo.php'); ?>

    <div class="main-content">
        <div id="responseMessage" class="modern-alert" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000;"></div>
        <div class="header">
            <div class="blue__bar">
                <h2>Student List</h2>
            </div>
            <div class="yellow__bar"></div>
        </div>

        <div class="content-area">
            <div class="table-area">
                <div class="search-bar">
                  <input type="text" id="searchQuery" placeholder="Search by Student No. or Name">
                  <select id="programFilter" class="form-select" style="width: 100px;">
                      <option value="">Select Program</option>
                      <!-- Programs will be dynamically loaded here -->
                  </select>
                  <button class="circle-button" id="searchButton"><i class="bi bi-search"></i></button>
                </div>
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>Student No.</th>
                            <th>Student Name</th>
                            <th>Year/Program</th>
                            <th>RFID Status</th>
                            <th>Attendance</th>
                        </tr>
                    </thead>
                    <tbody class="student-table-body">

                    </tbody>
                </table>
            </div>

            <div class="student-info">
                <h1 style="text-align: center; padding-top: 30px; padding-bottom: 30px;">Student Information</h1>
                <div class="student-info-area">
                    <span>Student No: <strong id="studentNoDisplay"></strong></span>
                    <br/>
                    <span>Name: <strong id="studentNameDisplay"></strong></span>
                    <br/>
                    <span>Year/Program: <strong id="studentProgramDisplay"></strong></span>
                    <h4 style="text-align: center; padding-top: 30px;">RFID Status</h4>
                    <div class="rfidForm">
                        <button id="show_rfid-area" class="btn btn-primary" style="background-color: #d9f0ff; color: #0d67a1;">Update RFID</button>
                        <div class="rfid-area">
                            <div>Place your RFID</div>
                            <img 
                                src="../images/tap-id.png" 
                                class="img-fluid my-3" 
                                style="max-width: 150px; height: auto;" 
                                alt="Tap RFID" 
                            />
                            <form id="submitStudentRFID" style="position: absolute; left: -9999px;">
                                <input type="text" placeholder="Enter RFID..." id="studentRFID" name="studentRFID" />
                                <input type="submit" id="submitRFID" />

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('../js/components/ui/add_student_modal.php'); ?>

</body>
<script src="../js/admin/student_list.js"></script>
<script src="../js/admin/admin.js"></script>
</html>