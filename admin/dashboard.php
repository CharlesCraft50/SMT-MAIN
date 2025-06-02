<?php
    require('../js/components/ui/admin_header.php');
    $activeTab = "dashboard";
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
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
    </script>

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
            <h2>Dashboard Analytics</h2>
            </div>
            <div class="yellow__bar"></div>
        </div>

        <!-- Title with dropdown filter -->
        <div class="d-flex justify-content-between align-items-center flex-wrap mx-3 mt-3">
            <h4 class="roboto-medium fs-3 mb-2" style="color: #0D67A1" id="textSummary">Daily Summary</h4>
            <select id="periodSelect" class="form-select w-auto" style="max-width: 200px;">
                <option value="daily" selected>Daily</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>
        </div>

        <div class="row mx-3 g-5 mt-2">
            <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                <p class="card-text fs-5 p-3">Program with The Most Number of Violations</p>
                <h2 class="card-title roboto-medium fs-2 fw-bold" id="mostSectionId"></h2>
                </div>
            </div>
            </div>
            <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                <p class="card-text fs-5 p-3">Total Number of Students Without Uniform</p>
                <h2 class="card-title roboto-medium fs-2 fw-bold" id="totalWithoutUniform"></h2>
                </div>
            </div>
            </div>
            <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                <p class="card-text fs-5 p-3">Total Number of Students Without ID</p>
                <h2 class="card-title roboto-medium fs-2 fw-bold" id="totalWithoutId"></h2>
                </div>
            </div>
            </div>
        </div>

      <h4 class="m-3 mx-3 roboto-medium fs-3" style="color: #0D67A1"><hr/></h4>

      <div class="row mx-3 g-5">
        <div class="col-sm-6">
          <div class="card text-center">
            <div class="card-body">
            <div class="chart-title mt-2" style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 10px;">
              No ID Violations
            </div>
            <div class="chart-container" id="idChart"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card text-center">
            <div class="card-body">
            <div class="chart-title mt-2" style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 10px;">
              No Uniform Violations
            </div>
            <div class="chart-container" id="uniformChart"></div>      
            </div>
          </div>
        </div>
      </div>
        
    </div>

    <?php require('../js/components/ui/add_student_modal.php'); ?>

</body>
<script src="../js/admin/dashboard.js"></script>
<script src="../js/admin/admin.js"></script>
</html>