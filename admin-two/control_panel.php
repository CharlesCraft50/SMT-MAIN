<?php
    require('../js/components/ui/admin_header.php');
    $activeTab = "control_panel";
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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/admin.css">

    <style>
        .table-area {
            display: flex;
            justify-content: center;
            border: 1px solid #F3F3F3;
            max-height: 700px;
            min-height: 300px;
            overflow-x: hidden !important;
            background-color: #F3F3F3;
            border-radius: 18px;
            padding: 20px;
            margin: 20px;
            flex: 1;
        }
        .exception-days-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .exception-days-table th,
        .exception-days-table td {
            padding: 0.75rem;
            font-size: 0.95rem;
            vertical-align: middle;
            text-align: center;
            white-space: nowrap;
        }

        /* Hover improvements */
        .exception-days-table tbody tr:hover td {
            background-color: #e0f0ff;
            transition: background-color 0.2s ease-in-out;
        }

        @media (max-width: 768px) {
            .exception-days-table th,
            .exception-days-table td {
                font-size: 0.875rem;
                padding: 0.5rem;
            }
        }
    </style>
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
            <h2>Control Panel</h2>
            </div>
            <div class="yellow__bar"></div>
        </div>

        <!-- Title with dropdown filter -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mx-3 mt-3">
            <h4 class="roboto-medium fs-3 mb-2" style="color: #0D67A1">Exception Days</h4>
        </div>

        <div class="row mx-3 g-5 mt-2">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5 text-center">
                    <h5 class="mb-4 fw-semibold text-primary">Add Event Dates</h5>

                    <div class="mb-4">
                        <label for="dateRange" class="form-label fs-6 fw-medium">Select Date Range</label>
                        <input type="text" id="dateRange" class="form-control form-control-lg text-center" placeholder="e.g., 2025-05-18 to 2025-05-20" />
                    </div>

                    <div class="my-4">
                        <span class="text-muted fw-semibold">or</span>
                    </div>

                    <div class="mb-4">
                        <label for="weekDay" class="form-label fs-6 fw-medium">Choose Recurring Weekday</label>
                        <select name="weekDay" id="weekDay" class="form-select form-select-lg text-center">
                        <option selected disabled>Select a day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fs-6 fw-medium">Event Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control form-control-lg rounded-3" rows="4" placeholder="Describe the event..."></textarea>
                    </div>

                    <button id="submitDateEvent" class="btn btn-primary btn-lg px-5 mt-3">
                        Submit
                    </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8 mx-auto">
                <div class="table-area overflow-auto card shadow-lg border-0 rounded-4">
                <div class="table-responsive">
                    <table class="exception-days-table table table-hover align-middle text-nowrap">
                    <thead class="table-primary">
                        <tr>
                        <th scope="col">Dates/Weekday</th>
                        <th scope="col">Description</th>
                        <th></th>
                        </tr>
                    </thead>
                    <tbody class="exception-days-table-body">
                        <!-- rows rendered by JS -->
                    </tbody>
                    </table>
                </div>
                </div>

                
            </div>

            <div class="row mx-3 g-5 mt-2">
                <div class="col-12 col-md-8 mx-auto">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-body p-5 text-center">
                            <h5 class="mb-4 fw-semibold text-primary">Scanning of Violations</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="automaticChecking" checked>
                                <label class="form-check-label fs-5" for="automaticChecking" id="automaticCheckingText">Automatic Checking</label>
                            </div>

                            <div id="manualUpdateViolationsArea">
                                <input type="button" value="Update Violations Manually" id="manualUpdateViolations" class="btn btn-primary mt-5 mx-3" />

                                <div id="manualUpdateViolationsLoading" class="d-none">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                                        <div class="spinner-border text-primary" role="status" style="width: 5rem; height: 5rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-body">
                        <!-- Logout Button -->
                        <button type="button" class="btn btn-danger mt-auto" data-bs-toggle="modal" data-bs-target="#logoutModal" style="margin-top: auto; background-color: #0D67A1; border-color: #0D67A1;">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <?php require('../js/components/ui/add_student_modal.php'); ?>

</body>
<script src="../js/admin/control_panel.js"></script>
<script src="../js/admin/admin.js"></script>

</html>