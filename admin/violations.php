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

    <div class="sidebar">
        <!-- Hamburger Button -->
        <button class="hamburger-button">
            <i class="bi bi-list"></i>
        </button>
        <hr>
        <div class="sidebar-content">
            <a href="dashboard.php"><i class="bi bi-grid" style="color: #0D67A1; font-size: 24px;"></i> Dashboard</a>
            <a href="studentList.php"><i class="bi bi-people" style="color: #0D67A1; font-size: 24px;"></i> Student List</a>
            <a href="violations.php"><i class="bi bi-table" style="color: #0D67A1; font-size: 24px;"></i> Violations</a>
            <a href="violations.php" id="addStudentNav" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="bi bi-person-plus" style="color: #0D67A1; font-size: 24px;"></i> Student Registration
            </a>
            <a href="control_panel.php"><i class="bi bi bi-card-list" style="color: #0D67A1; font-size: 24px;"></i> Control Panel</a>
            <!-- `Logout` Button -->
            <div class="logout-button-wrapper">
                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#logoutModal"
                    style="background-color: #0D67A1; border-color: #0D67A1;">
                    Logout
                </button>
            </div>
            
        </div>
    </div>

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
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addViolationModal" id="addViolationStudent">Add Violation</button>
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
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Violation Type</th>
                                <th>Violation Date</th>
                                <th>Notes</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id="violationDetailsTableBody">
                            <!-- Violations will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="addViolationButton">Add</button>
                    <button type="button" class="btn btn-primary" id="closeViolationModal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


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
                        data-year="${violation.YearLevel}" data-program="${violation.ProgramCode}" 
                        data-violations="${violation.ViolationCount}" 
                        data-without-uniform="${violation.WithoutUniformCount}" 
                        data-without-id="${violation.WithoutIDCount}" 
                        data-latest-violation="${violation.LatestViolationDate}">
                        <td>${violation.StudentID}</td>
                        <td>${violation.StudentName}</td>
                        <td>${violation.YearLevel} / ${violation.ProgramCode}</td>
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
                                            <button class="btn btn-warning" data-id="${violation.RecordID}" onclick="editViolation(this)">Update</button>
                                            <button class="btn btn-danger" data-id="${violation.RecordID}" onclick="deleteViolation(this)">&times;</button>
                                        </div>
                                    </td>
                                </tr>`;
                            $('#violationDetailsTableBody').append(violationRow);
                        }
                    });

                    // Show the modal
                    $('#violationDetailsModal').modal('show');
                } else if(response.status === 'empty') {
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
                        var violationRow = `
                            <tr>
                                <td>${violation.ViolationType}</td>
                                <td>${formatDateForDisplay(violation.ViolationDate)}</td>
                                <td>${violation.Notes}</td>
                                <td>${violation.ViolationStatus}</td>
                                <td>
                                    <div class="inline-buttons">
                                        <button class="btn btn-warning" data-id="${violation.RecordID}" onclick="editViolation(this)">Update</button>
                                        <button class="btn btn-danger" data-id="${violation.RecordID}" onclick="deleteViolation(this)">&times;</button>
                                    </div>
                                </td>
                            </tr>`;
                        $('#violationDetailsTableBody').append(violationRow);
                    });

                    // Show the modal
                    $('#violationDetailsModal').modal('show');
                } else {
                    showResponseMessage('#responseMessage', 'Failed to fetch violation details: ' + response.message, 'danger');
                }
            },
            error: function() {
                showResponseMessage('#responseMessage', 'Error fetching violation details.', 'danger');
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
                RecordID: violationID,
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
                    showResponseMessage('#responseMessage', 'Failed to save changes: ' + response.message, 'danger');
                }
            },
            error: function() {
                showResponseMessage('#responseMessage', 'Error saving violation changes.', 'danger');
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
                        showResponseMessage('#responseMessage', 'Violation deleted successfully.', 'success');
                    } else {
                        showResponseMessage('#responseMessage', 'Failed to delete violation: ' + response.message, 'danger');
                    }
                },
                error: function() {
                    showResponseMessage('#responseMessage', 'Error deleting violation.', 'danger');
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

        if (!violationDate) {
            showResponseMessage('#responseMessage', 'Please fill out all fields.', 'danger');
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
                if (response.status === "success") {
                    // Replace input fields with the saved data
                    row.find('td').eq(0).text(violationType);
                    row.find('td').eq(1).text(formatDateForDisplay(violationDate));
                    row.find('td').eq(2).text(notes);
                    row.find('td').eq(3).text(status);
                    row.find('td').eq(4).html(`
                        <div class="inline-buttons">
                            <button class="btn btn-warning" data-id="${response.recordID}" onclick="editViolation(this)">Update</button>
                            <button class="btn btn-danger" data-id="${response.recordID}" onclick="deleteViolation(this)">&times;</button>
                        </div>
                    `);
                } else {
                    showResponseMessage('#responseMessage', 'Failed to add violation: ' + response.message, 'danger');
                }
            },
            error: function () {
                showResponseMessage('#responseMessage', 'Error adding new violation.', 'danger');
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
                                (${student.ProgramCode} / ${student.YearLevel})
                            </li>`;
                        $('#studentDropdown').append(studentOption);
                    });
                },
                error: function() {
                    showResponseMessage('#responseMessage', 'Error fetching students.', 'danger');
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
                    showResponseMessage('#responseMessage', 'Violation added successfully.', 'success');
                    $('#addViolationModal').modal('hide');
                    fetchViolationsData();
                } else {
                    showResponseMessage('#responseMessage', 'Error: ' + response.message, danger);
                    console.log(response.message);
                }
            },
            error: function() {
                showResponseMessage('#responseMessage', 'Error saving violation.', 'danger');
            }
        });
    });

    function setCurrentDateTime() {
        const now = new Date();
        const formattedDateTime = now.toISOString().slice(0, 16); // Format as 'YYYY-MM-DDTHH:MM'
        document.getElementById('violationDateInput').value = formattedDateTime;
    }

  });
</script>

<script src="../js/admin.js"></script>
</html>