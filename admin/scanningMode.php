<?php
    require('../js/components/ui/admin_header.php');
    $activeTab = "violation_scanning";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violation Scanning - Control Panel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Flatpickr CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            min-height: 100vh;
            background-color: #f8f9fa !important;
        }
        
        .main-content {
            min-height: 100vh;
            padding-bottom: 2rem;
        }
        
        .full-height-container {
            min-height: calc(100vh - 120px);
        }
        
        .report-card {
            transition: all 0.3s ease;
        }
        
        .report-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }
        
        .generate-report-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        
        .generate-report-btn:hover {
            background: linear-gradient(135deg, #218838 0%, #1e9b7a 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
    </style>
</head>

<body>
    <!-- Logout Modal -->
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
                <h2>Control Panel - General Settings</h2>
            </div>
            <div class="yellow__bar"></div>
        </div>

        <div class="container-fluid px-4 mt-4">
            <!-- First Row: Scanning Toggle Section -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-5 h-100">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                            <h5 class="mb-4 fw-semibold text-primary">
                                <i class="bi bi-shield-check me-2"></i>Scanning of Violations
                            </h5>
                            <div class="form-check form-switch d-flex justify-content-center">
                                <input class="form-check-input" type="checkbox" id="automaticChecking" checked style="transform: scale(1.5);">
                                <label class="form-check-label fs-5 ms-3" for="automaticChecking" id="automaticCheckingText">Automatic Checking</label>
                            </div>

                            <div id="manualUpdateViolationsArea" class="mt-4">
                                <input type="button" value="Update Violations Manually" id="manualUpdateViolations" class="btn btn-primary px-4 py-2" />

                                <div id="manualUpdateViolationsLoading" class="d-none mt-4">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Generation Section -->
                <div class="col-12 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-5 h-100 report-card">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                            <h5 class="mb-4 fw-semibold text-success">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Generate Daily Report
                            </h5>
                            <p class="text-muted mb-4">Generate a report containing today's violations and attendance statistics per program.</p>
                            
                            <button type="button" id="generateReportBtn" class="btn btn-success generate-report-btn">
                                <i class="bi bi-download me-2"></i>Generate PDF Report
                            </button>

                            <div id="reportGenerationLoading" class="d-none mt-4">
                                <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                                    <div class="spinner-border text-success" role="status" style="width: 3rem; height: 3rem;">
                                        <span class="visually-hidden">Generating Report...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-lg-6">
                    <div class="card shadow-lg border-0 rounded-5 h-100 report-card">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center">
                            <h5 class="mb-4 fw-semibold text-primary">
                                <i class="bi bi-archive me-2"></i>Archive Student Records
                            </h5>
                            <p class="text-muted mb-4">
                                Transfer student daily records into the archive table, summarizing attendance and violation data per student per month.
                            </p>
                            
                            <button type="button" id="archiveRecordsBtn" class="btn btn-primary">
                                <i class="bi bi-download me-2"></i>Archive Now
                            </button>

                            <div id="archiveLoading" class="d-none mt-4">
                                <div class="d-flex justify-content-center align-items-center" style="height: 100px;">
                                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                                        <span class="visually-hidden">Archiving Records...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <?php require('../js/components/ui/add_student_modal.php'); ?>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="../js/admin/control_panel.js"></script>
<script src="../js/admin/admin.js"></script>

</html>