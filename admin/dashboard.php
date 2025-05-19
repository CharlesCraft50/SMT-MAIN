<?php
session_start();
$isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : '';
if($isAdmin != true) {
  exit();
}
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
      google.charts.setOnLoadCallback(drawCharts);

      function drawCharts() {
        // Data for the first chart
        var data1 = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);

        // Options for the first chart
        var options1 = {
          title: '',
          titleTextStyle: {
          fontSize: 16,
          bold: true,
          color: '#333'
        },
          titleAlignment: 'center',
          pieHole: 0.5,
          chartArea: { left: 50, bottom: 0, top: 0, width: '100%', height: '75%' },
          backgroundColor: 'transparent'
        };

        // Render the first chart
        var chart1 = new google.visualization.PieChart(document.getElementById('idChart'));
        chart1.draw(data1, options1);

        // Data for the second chart
        var data2 = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     8],
          ['Eat',      3],
          ['Commute',  3],
          ['Watch TV', 4],
          ['Sleep',    6]
        ]);

        // Options for the second chart
        var options2 = {
          title: '',
            titleTextStyle: {
            fontSize: 16,
            bold: true,
            color: '#333'
          },
          titleAlignment: 'center',
          pieHole: 0.5,
          chartArea: { left: 50, bottom: 0, top: 0,  width: '100%', height: '75%' },
          backgroundColor: 'transparent'
        };

        // Render the second chart
        var chart2 = new google.visualization.PieChart(document.getElementById('uniformChart'));
        chart2.draw(data2, options2);
      }
    </script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/admin.css">
  </head>

<body>

    <div class="sidebar">
        <!-- Hamburger Button -->
        <button class="hamburger-button">
            <i class="bi bi-list"></i>
        </button>
        <hr>
        <div class="sidebar-content">
            <a href="#"><i class="bi bi-grid" style="color: #0D67A1; font-size: 24px;"></i> Dashboard</a>
            <a href="studentList.php"><i class="bi bi-people" style="color: #0D67A1; font-size: 24px;"></i> Student List</a>
            <a href="violations.php"><i class="bi bi-table" style="color: #0D67A1; font-size: 24px;"></i> Violations</a>
            <a href="violations.php" id="addStudentNav" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-person-plus" style="color: #0D67A1; font-size: 24px;"></i> Student Registration
            </a>
            <a href="control_panel.php"><i class="bi bi bi-card-list" style="color: #0D67A1; font-size: 24px;"></i> Control Panel</a>
            <!-- Logout Button -->
            <!-- <button type="button" class="btn btn-danger mt-auto" data-bs-toggle="modal" data-bs-target="#logoutModal" style="margin-top: auto; background-color: #0D67A1; border-color: #0D67A1;">
                Logout
            </button> -->

            <!-- Logout Confirmation Modal -->
            <!-- <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
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
            </div> -->
            
        </div>
    </div>

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
            <h4 class="roboto-medium fs-3 mb-2" style="color: #0D67A1">Annual Summary</h4>
            <select id="periodSelect" class="form-select w-auto" style="max-width: 200px;">
                <option value="yearly" selected>Yearly</option>
                <option value="monthly">Monthly</option>
                <option value="daily">Daily</option>
            </select>
        </div>

        <div class="row mx-3 g-5 mt-2">
            <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                <p class="card-text fs-5 p-3">Program with The Most Number of Violations</p>
                <h2 class="card-title roboto-medium fs-2 fw-bold" id="mostSectionId">BSCS - 999</h2>
                </div>
            </div>
            </div>
            <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                <p class="card-text fs-5 p-3">Total Number of Students Without Uniform</p>
                <h2 class="card-title roboto-medium fs-2 fw-bold" id="totalWithoutUniform">917</h2>
                </div>
            </div>
            </div>
            <div class="col-sm-4">
            <div class="card text-center">
                <div class="card-body">
                <p class="card-text fs-5 p-3">Total Number of Students Without ID</p>
                <h2 class="card-title roboto-medium fs-2 fw-bold" id="totalWithoutId">881</h2>
                </div>
            </div>
            </div>
        </div>

      <!-- <h4 class="m-3 mx-3 roboto-medium fs-3" style="color: #0D67A1">Monthly Summary</h4>

      <div class="row mx-3 g-5">
        <div class="col-sm-6">
          <div class="card text-center">
            <div class="card-body">
            <div class="chart-title mt-2" style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 10px;">
              No. of Students without ID
            </div>
            <div id="idChart" style="width: 34rem; height: 45vh; background-color: #f5f5f5;"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card text-center">
            <div class="card-body">
            <div class="chart-title mt-2" style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 10px;">
              No. of Students without Uniform
            </div>
            <div id="uniformChart" style="width: 34rem; height: 45vh; background-color: #f5f5f5;"></div>      
            </div>
          </div>
        </div>
      </div> -->
        
    </div>

    <div id="addStudentModal" class="modal" tabindex="-1">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Add Student</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <form id="addStudentForm">
                      <div class="mb-3">
                          <label for="studentNo" class="form-label">Student Number</label>
                          <input type="number" class="form-control" id="studentNo" required>
                      </div>
                      <div class="mb-3">
                          <label for="studentName" class="form-label">Student Name</label>
                          <input type="text" class="form-control" id="studentName" required>
                      </div>
                      <div class="mb-3">
                          <label for="studentProgram" class="form-label">Program</label>
                          <select class="form-select" id="studentProgram" required>
                              <option value="">Select Program</option>
                              <!-- Programs will be dynamically loaded -->
                          </select>
                      </div>
                      <div class="mb-3">
                          <label for="studentYear" class="form-label">Year</label>
                          <select class="form-select" id="studentYear" required>
                              <option value="">Select Year</option>
                              <option value="1st">1st Year</option>
                              <option value="2nd">2nd Year</option>
                              <option value="3rd">3rd Year</option>
                              <option value="4th">4th Year</option>
                          </select>
                      </div>
                      <button type="submit" class="btn btn-primary">Add Student</button>
                  </form>
              </div>
          </div>
      </div>
  </div>


</body>
<script type="text/javascript">
    $(document).ready(function () {
        const fetchSectionWithMostViolations = (period) => {
            $.ajax({
                url: '../php-api/ReadSectionWithMostViolations.php',
                type: 'GET',
                dataType: 'json',
                data: {period: period},
                success: function(response) {
                    $('#mostSectionId').text(response.data.ProgramCode + " - " + response.data.ViolationCount);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        };

        const fetchViolationTotals = (period) => {
            $.ajax({
                url: '../php-api/ReadViolationTotals.php',
                type: 'GET',
                dataType: 'json',
                data: {period: period},
                success: function(response) {
                    $('#totalWithoutUniform').text(response.data.total_without_uniform);
                    $('#totalWithoutId').text(response.data.total_without_id);
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        };
      

        $('#periodSelect').on('change', function () {
            const period = this.value;
            fetchSectionWithMostViolations(period);
            fetchViolationTotals(period);
        });
      
        fetchSectionWithMostViolations($('#periodSelect').val());
        fetchViolationTotals($('#periodSelect').val());

    });


</script>
<script src="../js/admin.js"></script>
</html>