<?php
    require('../js/components/ui/admin_header.php');
    $activeTab = "add_violation";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Violation - Control Panel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        #studentDropdown {
            cursor: pointer;
        }
        
        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }
        
        /* Custom Toast Styles */
        .toast-success {
            background-color: #d1edff;
            border-left: 4px solid #0d6efd;
        }
        
        .toast-success .toast-header {
            background-color: #0d6efd;
            color: white;
        }
        
        .toast-error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        
        .toast-error .toast-header {
            background-color: #dc3545;
            color: white;
        }
        
        .toast-header .btn-close {
            filter: invert(1);
        }
    </style>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container">
        <!-- Success Toast -->
        <div id="successToast" class="toast toast-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Violation has been successfully added!
            </div>
        </div>
        
        <!-- Error Toast -->
        <div id="errorToast" class="toast toast-error" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong class="me-auto">Error</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Failed to add violation. Please try again.
            </div>
        </div>
    </div>

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
                <h2>Control Panel - Manual Violation</h2>
            </div>
            <div class="yellow__bar"></div>
        </div>

        <!-- Add Violation Form -->
        <div class="row mx-3 g-5 mt-3">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card shadow-lg border-0 rounded-5">
                    <div class="card-body p-5">
                        <h5 class="mb-4 fw-semibold text-primary text-center">Violation Entry Form</h5>

                        <form id="addViolationForm">
                            <!-- Searchable Student Input -->
                            <div class="mb-4">
                                <label for="studentSearchInput" class="form-label fw-medium">Student</label>
                                <input type="text" id="studentSearchInput" class="form-control form-control-lg" placeholder="Search by Student ID, Name, or Program" required>
                                <ul id="studentDropdown" class="dropdown-menu w-100"></ul>
                            </div>

                            <!-- Violation Date -->
                            <div class="mb-4">
                                <label for="violationDateInput" class="form-label fw-medium">Violation Date</label>
                                <input type="datetime-local" class="form-control form-control-lg" id="violationDateInput" required>
                            </div>

                            <!-- Violation Type -->
                            <div class="mb-4">
                                <label for="violationTypeSelect" class="form-label fw-medium">Violation Type</label>
                                <select id="violationTypeSelect" class="form-select form-select-lg" required>
                                    <option value="" disabled selected>Select violation type</option>
                                    <option value="WithoutID">Without ID</option>
                                    <option value="WithoutUniform">Without Uniform</option>
                                </select>
                            </div>

                            <!-- Notes (Optional) -->
                            <div class="mb-4">
                                <label for="notesInput" class="form-label fw-medium">Notes (Optional)</label>
                                <textarea id="notesInput" class="form-control form-control-lg rounded-3" rows="4" placeholder="Add notes here..."></textarea>
                            </div>

                            <!-- Violation Picture -->
                            <div class="mb-4">
                                <label for="violationPictureInput" class="form-label fw-medium">Violation Picture</label>
                                <input type="file" id="violationPictureInput" class="form-control form-control-lg" accept="image/*">
                                <img id="violationPicturePreview" src="images/placeholder.png" alt="Violation Picture" class="img-fluid mt-3" style="max-height: 200px; border-radius: 8px;">
                            </div>

                            <!-- Violation Status -->
                            <div class="mb-4">
                                <label for="violationStatus" class="form-label fw-medium">Violation Status</label>
                                <input type="text" id="violationStatus" class="form-control form-control-lg" value="Pending" disabled>
                            </div>

                            <div class="text-center mt-5">
                                <button type="button" class="btn btn-secondary btn-lg px-4 me-3" onclick="window.history.back()">
                                    <i class="bi bi-arrow-left me-2"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg px-5" id="saveViolationButton" style="background-color: #0D67A1; border-color: #0D67A1;">
                                    <i class="bi bi-plus-circle me-2"></i>Save Violation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('../js/components/ui/image_modal.php'); ?>
    <?php require('../js/components/ui/add_student_modal.php'); ?>

    <script>
        // Toast functions
        function showSuccessToast(message = 'Violation has been successfully added!') {
            const toast = document.getElementById('successToast');
            const toastBody = toast.querySelector('.toast-body');
            toastBody.textContent = message;
            
            const bsToast = new bootstrap.Toast(toast, {
                delay: 4000
            });
            bsToast.show();
        }

        function showErrorToast(message = 'Failed to add violation. Please try again.') {
            const toast = document.getElementById('errorToast');
            const toastBody = toast.querySelector('.toast-body');
            toastBody.textContent = message;
            
            const bsToast = new bootstrap.Toast(toast, {
                delay: 4000
            });
            bsToast.show();
        }

        // Make toast functions globally available
        window.showSuccessToast = showSuccessToast;
        window.showErrorToast = showErrorToast;
    </script>

</body>
<script src="../js/admin/violations.js"></script>
<script src="../js/admin/admin.js"></script>
</html>