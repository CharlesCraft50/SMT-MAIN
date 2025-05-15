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

    <style>
        /* Your existing CSS styles */
        body {
            font-family: "Roboto", sans-serif;
            margin: 0;
            display: flex;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 290px !important;
            height: 120vh;
            max-width: 240px !important;
            background-color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        .sidebar h2 {
            margin: 0;
            color: #007BFF;
        }

        .sidebar a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #333;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: #e7f3fe;
        }
        #idChart, #uniformChart {
          text-align: center;
        }

        .main-content {
            flex-grow: 1;
            background-color: #fff;
            display: flex;
            flex-direction: column;
        }

        .header {
            width: 100%;
            padding: 0;
            margin: 0;
        }

        .blue__bar {
            background-color: #0D67A1;
            width: 100%;
            padding: 20px;
            padding-bottom: 15px;
            color: white;
        }

        .yellow__bar {
            background-color: #FFF200;
            width: 100%;
            height: 2vh;
        }

        .content-area {
            display: flex;
            margin-top: 20px;
            padding: 20px;
            flex-wrap: wrap;
        }

        /* FONT WEIGHTS */
        .roboto-thin {
          font-family: "Roboto", sans-serif;
          font-weight: 100;
          font-style: normal;
        }

        .roboto-light {
          font-family: "Roboto", sans-serif;
          font-weight: 300;
          font-style: normal;
        }

        .roboto-regular {
          font-family: "Roboto", sans-serif;
          font-weight: 400;
          font-style: normal;
        }

        .roboto-medium {
          font-family: "Roboto", sans-serif;
          font-weight: 500;
          font-style: normal;
        }

        .roboto-bold {
          font-family: "Roboto", sans-serif;
          font-weight: 700;
          font-style: normal;
        }

        .roboto-black {
          font-family: "Roboto", sans-serif;
          font-weight: 900;
          font-style: normal;
        }

        .roboto-thin-italic {
          font-family: "Roboto", sans-serif;
          font-weight: 100;
          font-style: italic;
        }

        .roboto-light-italic {
          font-family: "Roboto", sans-serif;
          font-weight: 300;
          font-style: italic;
        }

        .roboto-regular-italic {
          font-family: "Roboto", sans-serif;
          font-weight: 400;
          font-style: italic;
        }

        .roboto-medium-italic {
          font-family: "Roboto", sans-serif;
          font-weight: 500;
          font-style: italic;
        }

        .roboto-bold-italic {
          font-family: "Roboto", sans-serif;
          font-weight: 700;
          font-style: italic;
        }

        .roboto-black-italic {
          font-family: "Roboto", sans-serif;
          font-weight: 900;
          font-style: italic;
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

        .card-body{
          background-color: #f5f5f5;
          border-radius: 30px;
          height: 100%;
          box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
        }
        .card{
          border: none;
        }
        .card-title, .card-text, .chart-title{
          color: #0D67A1;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            <!-- Logout Button -->
            <button type="button" class="btn btn-danger mt-auto" data-bs-toggle="modal" data-bs-target="#logoutModal" style="margin-top: auto; background-color: #0D67A1; border-color: #0D67A1;">
                Logout
            </button>

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
        </div>
    </div>

    <div class="main-content">

      <div class="header">
          <div class="blue__bar">
              <h2>Dashboard Analytics</h2>
          </div>
          <div class="yellow__bar"></div>
      </div>
      
      <h4 class="m-3 mx-3 roboto-medium fs-3" style="color: #0D67A1">Annual Summary</h4>

      <div class="row mx-3 g-5">
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="card-body">
              <p class="card-text fs-5 p-3">Program with The Most Number of Violations</p>
              <h2 class="card-title roboto-medium fs-2 fw-bold">BSCS - 999</h2>

            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="card-body">
              <p class="card-text fs-5 p-3">Total Number of Students Without Uniform</p>
              <h2 class="card-title roboto-medium fs-2 fw-bold">917</h2>
              
            </div>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card text-center">
            <div class="card-body">
            <p class="card-text fs-5 p-3">Total Number of Students Without ID</p>
            <h2 class="card-title roboto-medium fs-2 fw-bold">881</h2>

             
            </div>
          </div>
        </div>
      </div>

      <h4 class="m-3 mx-3 roboto-medium fs-3" style="color: #0D67A1">Monthly Summary</h4>

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
      </div>
        
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
      let showSideBar = true;

      $('.hamburger-button').click(function () {
          showSideBar = !showSideBar;

          if (!showSideBar) {
              $('.sidebar').animate({ width: '90px' });
              $('.sidebar-content').hide();
          } else {
              $('.sidebar').animate({ width: '290px' });
              $('.sidebar-content').show();
          }
      });

      $('#searchButton').click(function () {
          fetchViolationsData();
      });

      $('#searchQuery').on('input', function (e) {
          fetchViolationsData();
      });

      $('#programFilter').change(function () {
          fetchViolationsData();
      });
      
      function displayTable(data) {
          let tableBody = $('.violation-table-body');
          tableBody.empty();

          if (data.length === 0) {
              tableBody.append('<tr><td colspan="4">No violations found</td></tr>');
          } else {
              data.forEach(violation => {
                  let row = `
                    <tr data-id="${violation.StudentID}" data-name="${violation.StudentName}" 
                        data-year="${violation.Year}" data-program="${violation.ProgramCode}" 
                        data-violations="${violation.ViolationCount}" 
                        data-without-uniform="${violation.WithoutUniformCount}" 
                        data-without-id="${violation.WithoutIDCount}" 
                        data-latest-violation="${violation.LatestViolationDate}">
                        <td>${violation.StudentID}</td>
                        <td>${violation.StudentName}</td>
                        <td>${violation.Year} / ${violation.ProgramCode}</td>
                        <td>${violation.ViolationCount}</td>
                        <td>${violation.WithoutUniformCount}</td>
                        <td>${violation.WithoutIDCount}</td>
                        <td>${violation.LatestViolationDate ? new Date(violation.LatestViolationDate).toLocaleDateString() : 'N/A'}</td>
                    </tr>`;

                  tableBody.append(row);
              });
          }
      }

      function fetchViolationsData() {
        const searchQuery = $('#searchQuery').val();
        const programID = $('#programFilter').val();

        let searchData = {};
        if ($.isNumeric(searchQuery)) {
            searchData.StudentID = searchQuery;
        } else {
            searchData.StudentName = searchQuery;
        }

        searchData.ProgramID = programID;

        $.ajax({
            url: '../php-api/ReadViolations.php',
            type: 'GET',
            dataType: 'json',
            data: searchData,
            success: function (response) {
                if (response.status === 'success') {
                    displayTable(response.data);
                } else {
                    $('.violation-table-body').empty();
                    $('.violation-table-body').append('<tr><td colspan="4">No violations found</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
      }


      fetchViolationsData();

      fetchProgramsFilter();

        function fetchProgramsFilter() {
          $.ajax({
              url: '../php-api/ReadPrograms.php',
              type: 'GET',
              dataType: 'json',
              success: function (response) {
                  if (response.status === 'success') {
                      const programFilter = $('#programFilter');
                      programFilter.empty().append('<option value="">Select Program</option>');

                      for (const category in response.data) {
                          if (response.data.hasOwnProperty(category)) {
                              const optgroup = $('<optgroup>').attr('label', category);
                              
                              response.data[category].forEach(function(program) {
                                  optgroup.append(
                                      `<option value="${program.ProgramID}">${program.ProgramName}</option>`
                                  );
                              });

                              programFilter.append(optgroup);
                          }
                      }
                  } else {
                      console.error('Error fetching programs:', response.message);
                  }
              },
              error: function (xhr, status, error) {
                  console.error('AJAX Error:', error);
              }
          });
      }

      function fetchPrograms() {
        $.ajax({
            url: '../php-api/ReadPrograms.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const programDropdown = $('#studentProgram');
                    programDropdown.empty().append('<option value="">Select Program</option>');

                    for (const category in response.data) {
                        if (response.data.hasOwnProperty(category)) {
                            const optgroup = $('<optgroup>').attr('label', category);
                            
                            response.data[category].forEach(function(program) {
                                optgroup.append(
                                    `<option value="${program.ProgramID}">${program.ProgramName}</option>`
                                );
                            });

                            programDropdown.append(optgroup);
                        }
                    }
                } else {
                    console.error('Error fetching programs:', response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

       // Click event to fetch violations and display them in a modal
    $('table.violation-table').on('click', 'tr', function() {
        var studentID = $(this).data('id'); // Get the student ID from the clicked row

        // Send an AJAX request to fetch detailed violations for the student
        $.ajax({
            url: '../php-api/ReadStudentViolation.php', 
            method: 'GET',
            data: { StudentID: studentID },
            success: function(response) {
                if (response.status === 'success') {
                    var student = response.student;
                    var violations = response.violations;

                    // Populate student info in the modal
                    $('#studentNoDisplay').text(student.StudentID);
                    $('#studentNameDisplay').text(student.StudentName);
                    $('#studentProgramDisplay').text(student.ProgramName + " (" + student.ProgramCode + ")");

                    // Clear previous violations
                    $('#violationDetailsTableBody').empty();

                    // Loop through each violation and add it to the table
                    violations.forEach(function(violation) {
                        if(violation.Violated == 1) {
                            var violationRow = `
                                <tr>
                                    <td>${violation.ViolationType}</td>
                                    <td>${formatDateForDisplay(violation.ViolationDate)}</td>
                                    <td>${violation.Notes}</td>
                                    <td>${violation.ViolationStatus}</td>
                                    <td>
                                        <div class="inline-buttons">
                                            <button class="btn btn-warning" data-id="${violation.ViolationID}" onclick="editViolation(this)">Update</button>
                                            <button class="btn btn-danger" data-id="${violation.ViolationID}" onclick="deleteViolation(this)">&times;</button>
                                        </div>
                                    </td>
                                </tr>`;
                            $('#violationDetailsTableBody').append(violationRow);
                        }
                    });

                    // Show the modal
                    $('#violationDetailsModal').modal('show');
                } else {
                    alert('Failed to fetch violation details: ' + response.message);
                }
            },
            error: function() {
                alert('Error fetching violation details.');
            }
        });
    });

    // Function to format the date for the HTML input (YYYY-MM-DD)
    function formatDateForInput(date) {
        const d = new Date(date);
        if (isNaN(d)) return ''; // Handle invalid date
        const year = d.getFullYear();
        const month = ('0' + (d.getMonth() + 1)).slice(-2);
        const day = ('0' + d.getDate()).slice(-2);
        return `${year}-${month}-${day}`;
    }

    // Function to format the date for display in the table (optional, to make it look user-friendly)
    function formatDateForDisplay(date) {
        const d = new Date(date);
        if (isNaN(d)) return date; // Return original if invalid date
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return d.toLocaleDateString('en-US', options);
    }

    // Edit violation functionality (only modify Date, Notes, and Status)
    window.editViolation = (button) => {
        var violationID = $(button).data('id'); // Get the violation ID from the clicked button

        // Find the row corresponding to this violation
        var row = $(button).closest('tr');
        
        // Get the current values for the violation fields (excluding ViolationType)
        var violationDate = row.find('td').eq(1).text();
        var notes = row.find('td').eq(2).text();
        var violationStatus = row.find('td').eq(3).text();

        // Replace the ViolationDate, Notes, and ViolationStatus with input fields, but keep ViolationType unchanged
        row.find('td').eq(1).html(`<input type="date" class="form-control" id="violationDateInput" value="${formatDateForInput(violationDate)}">`);
        row.find('td').eq(2).html(`<input type="text" class="form-control" id="notesInput" value="${notes}">`);
        row.find('td').eq(3).html(`
            <select class="form-control" id="violationStatusDropdown">
                <option value="Pending" ${violationStatus === 'Pending' ? 'selected' : ''}>Pending</option>
                <option value="Reviewed" ${violationStatus === 'Reviewed' ? 'selected' : ''}>Reviewed</option>
            </select>
        `);

        // Change the Update button to a Save button and add the Delete button
        row.find('td').eq(4).html(`
            <div class="inline-buttons">
                <button class="btn btn-success" data-id="${violationID}" onclick="saveViolationChanges(this)">Save</button>
                <button class="btn btn-danger" data-id="${violationID}" onclick="deleteViolation(this)">Delete</button>
            </div>
        `);
    }

    // Save the updated violation details
    window.saveViolationChanges = (button) => {
        var violationID = $(button).data('id');
        var row = $(button).closest('tr');

        // Get the updated values from the input fields
        var updatedViolationDate = row.find('#violationDateInput').val();
        var updatedNotes = row.find('#notesInput').val();
        var updatedViolationStatus = row.find('#violationStatusDropdown').val();

        // Send an AJAX request to save the updated violation details
        $.ajax({
            url: '../php-api/UpdateStudentViolation.php', 
            method: 'POST',
            data: {
                ViolationID: violationID,
                ViolationDate: updatedViolationDate,
                Notes: updatedNotes,
                ViolationStatus: updatedViolationStatus
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Update the table row with the new values
                    row.find('td').eq(1).text(formatDateForDisplay(updatedViolationDate));
                    row.find('td').eq(2).text(updatedNotes);
                    row.find('td').eq(3).text(updatedViolationStatus);
                    row.find('td').eq(4).html(`
                        <div class="inline-buttons">
                            <button class="btn btn-warning" data-id="${violationID}" onclick="editViolation(this)">Update</button>
                            <button class="btn btn-danger" data-id="${violationID}" onclick="deleteViolation(this)">Delete</button>
                        </div>
                    `);
                } else {
                    alert('Failed to save changes: ' + response.message);
                }
            },
            error: function() {
                alert('Error saving violation changes.');
            }
        });
    }

    // Delete violation functionality
    window.deleteViolation = (button) => {
        var violationID = $(button).data('id');
        if (confirm('Are you sure you want to delete this violation?')) {
            $.ajax({
                url: '../php-api/DeleteStudentViolation.php',
                method: 'POST',
                data: { ViolationID: violationID },
                success: function(response) {
                    if (response.status === 'success') {
                        $(button).closest('tr').remove();
                        alert('Violation deleted successfully.');
                    } else {
                        alert('Failed to delete violation: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error deleting violation.');
                }
            });
        }
    }

    $('#addViolationButton').on('click', function () {
        // Get the current date in YYYY-MM-DD format
        var currentDate = new Date().toISOString().split('T')[0];

        // Create a new row with input fields for a new violation
        var newViolationRow = `
            <tr>
                <td>
                    <select class="form-control" id="newViolationTypeDropdown">
                        <option value="WithoutID">WithoutID</option>
                        <option value="WithoutUniform">WithoutUniform</option>
                    </select>
                </td>
                <td>
                    <input type="date" class="form-control" id="newViolationDateInput" value="${currentDate}">
                </td>
                <td>
                    <input type="text" class="form-control" id="newNotesInput" placeholder="Enter notes">
                </td>
                <td>
                    <span id="newViolationStatus">Pending</span>
                </td>
                <td>
                    <div class="inline-buttons">
                        <button class="btn btn-success" onclick="saveNewViolation(this)">Save</button>
                        <button class="btn btn-danger" onclick="cancelNewViolation(this)">Cancel</button>
                    </div>
                </td>
            </tr>`;
        
        // Append the new row to the violations table body
        $('#violationDetailsTableBody').prepend(newViolationRow);
    });


    // Function to save the new violation
    window.saveNewViolation = (button) => {
        // Get the row containing the new violation inputs
        var row = $(button).closest('tr');
        var violationType = row.find('#newViolationTypeDropdown').val();
        var violationDate = row.find('#newViolationDateInput').val();
        var notes = row.find('#newNotesInput').val();
        var status = 'Pending';

        if (!violationDate || !notes.trim()) {
            alert('Please fill out all fields.');
            return;
        }

        // Send an AJAX request to save the new violation
        $.ajax({
            url: '../php-api/AddStudentViolation.php',
            method: 'POST',
            data: {
                ViolationType: violationType,
                ViolationDate: violationDate,
                Notes: notes,
                ViolationStatus: status,
                StudentID: $('#studentNoDisplay').text() // Get the student ID from the modal
            },
            success: function (response) {
                if (response.status === 'success') {
                    // Replace input fields with the saved data
                    row.find('td').eq(0).text(violationType);
                    row.find('td').eq(1).text(formatDateForDisplay(violationDate));
                    row.find('td').eq(2).text(notes);
                    row.find('td').eq(3).text(status);
                    row.find('td').eq(4).html(`
                        <div class="inline-buttons">
                            <button class="btn btn-warning" data-id="${response.violationID}" onclick="editViolation(this)">Update</button>
                            <button class="btn btn-danger" data-id="${response.violationID}" onclick="deleteViolation(this)">&times;</button>
                        </div>
                    `);
                } else {
                    alert('Failed to add violation: ' + response.message);
                }
            },
            error: function () {
                alert('Error adding new violation.');
            }
        });
    };

    // Function to cancel adding a new violation
    window.cancelNewViolation = (button) => {
        $(button).closest('tr').remove(); // Remove the row from the table
    };


       var selectedStudentId = null;

    // Search for students
    $('#studentSearchInput').on('keyup', function() {
        var searchTerm = $(this).val().trim();

        if (searchTerm.length >= 2) {
            $.ajax({
                url: '../php-api/SearchStudents.php', 
                method: 'GET',
                dataType: 'json',
                data: { search: searchTerm },
                success: function(response) {
                    $('#studentDropdown').empty().show();
                    response.students.forEach(function(student) {
                        var studentOption = `
                            <li class="dropdown-item" data-id="${student.StudentID}">
                                ${student.StudentID} - ${student.StudentName}
                                (${student.ProgramCode}${student.Year})
                            </li>`;
                        $('#studentDropdown').append(studentOption);
                    });
                },
                error: function() {
                    alert('Error fetching students.');
                }
            });
        } else {
            $('#studentDropdown').hide();
        }
    });


    // Select student from dropdown
    $('#studentDropdown').on('click', 'li', function() {
        var studentText = $(this).text();
        selectedStudentId = $(this).data('id');
        $('#studentSearchInput').val(studentText);
        $('#studentDropdown').hide();
    });

    $('#addViolationStudent').click(function(){
        setCurrentDateTime();
    });

    // Preview selected picture
    $('#violationPictureInput').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#violationPicturePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Save Violation
    $('#saveViolationButton').on('click', function() {
        var formData = new FormData();
        formData.append('StudentID', selectedStudentId);
        formData.append('ViolationDate', $('#violationDateInput').val());
        formData.append('ViolationType', $('#violationTypeSelect').val());
        formData.append('Notes', $('#notesInput').val() || '');
        formData.append('ViolationStatus', 'Pending');
        formData.append('ViolationPicture', $('#violationPictureInput')[0].files[0] || 'images/placeholder.png');
        setCurrentDateTime();

        $.ajax({
            url: '../php-api/AddViolation.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    alert('Violation added successfully.');
                    $('#addViolationModal').modal('hide');
                    fetchViolationsData();
                } else {
                    alert('Error: ' + response.message);
                    console.log(response.message);
                }
            },
            error: function() {
                alert('Error saving violation.');
            }
        });
    });

    function setCurrentDateTime() {
        const now = new Date();
        const formattedDateTime = now.toISOString().slice(0, 16); // Format as 'YYYY-MM-DDTHH:MM'
        document.getElementById('violationDateInput').value = formattedDateTime;
    }

    $('#addStudentModal').on('show.bs.modal', function () {
        fetchPrograms();
      });

      $('#addStudentForm').submit(function(e) {
        e.preventDefault();

        const formData = {
          studentID: $('#studentNo').val(),
          studentName: $('#studentName').val(),
          year: $('#studentYear').val(),
          programID: $('#studentProgram').val()
        };

        $.ajax({
            url: '../php-api/CreateStudent.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === 'success') {
                    alert(jsonResponse.message);
                    $('#studentNo').val('');
                    $('#studentName').val('');
                    $('#studentYear').val('');
                    $('#studentProgram').val('');
                    fetchStudentData();
                } else {
                    alert(jsonResponse.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });


  });


</script>
</html>