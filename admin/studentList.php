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
    <title>Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        .rfidForm {
            display: none;
        }

        .hamburger-button:hover {
            background-color: #095386;
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
</head>

<body>

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
                        <button id="show_rfid-area">Update RFID</button>
                        <div class="rfid-area">
                            <div>Place your RFID</div>
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
          fetchStudentData();
      });

      $('#searchQuery').on('keyup', function (e) {
          if (e.key === 'Enter') {
              fetchStudentData();
          }
      });

      $('#programFilter').change(function () {
          fetchStudentData();
      });
      
      function displayTable(data) {
          let tableBody = $('.student-table-body');
          tableBody.empty();

          if (data.length === 0) {
              tableBody.append('<tr><td colspan="4">No students found</td></tr>');
          } else {
              data.forEach(student => {
                  let row = `
                      <tr data-id="${student.StudentID}" data-name="${student.StudentName}" 
                          data-year="${student.YearLevel}" data-program="${student.ProgramCode}" 
                          data-violations="${student.ViolationCount}"
                          data-without-uniform="${student.WithoutUniformCount}" 
                          data-without-id="${student.WithoutIDCount}"
                          data-rfid-status="${student.RFIDStatus}"
                          data-rfid="${student.RFID}">
                          <td>${student.StudentID}</td>
                          <td>${student.StudentName}</td>
                          <td>${student.YearLevel} Year / ${student.ProgramCode}</td>
                          <td>${student.RFIDStatus}</td>
                      </tr>`;

                  tableBody.append(row);
              });
          }
      }

      $('#searchQuery').on('input', function(){
        fetchStudentData();
      });

      function fetchStudentData() {
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
            url: '../php-api/ReadStudents.php',
            type: 'GET',
            dataType: 'json',
            data: searchData,
            success: function (response) {
                if (response.status === 'success') {
                    displayTable(response.data);
                } else {
                    $('.student-table-body').empty();
                    $('.student-table-body').append('<tr><td colspan="4">No students found</td></tr>');
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
      }


      fetchStudentData();

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

        var studentID_GLOBAL = "";
        var studentName_GLOBAL = "";
        var year_GLOBAL = "";
        var programID_GLOBAL = "";

      $('.student-table-body').on('click', 'tr', function () {
        const studentID = $(this).data('id');
        const studentName = $(this).data('name');
        const studentYear = $(this).data('year');
        const studentProgram = $(this).data('program');
        const violationCount = $(this).data('violations');
        const withoutUniformCount = $(this).data('without-uniform');
        const withoutIDCount = $(this).data('without-id');
        const RFIDStatus = $(this).data('rfid-status');
        const RFID = $(this).data('rfid');

        studentID_GLOBAL = studentID;
        studentName_GLOBAL = studentName;
        year_GLOBAL = studentYear;
        programID_GLOBAL = studentProgram;

        $('#studentNoDisplay').text(studentID);
        $('#studentNameDisplay').text(studentName);
        $('#studentProgramDisplay').text(`${studentYear} / ${studentProgram}`);

        $('#withoutUniformDisplay').text(withoutUniformCount);
        $('#withoutIDDisplay').text(withoutIDCount);

        if(RFIDStatus == "Registered") {
            // document.getElementById('studentRFID').value = RFID;
        }

        $('.rfidForm').show();
        $('#submitStudentRFID input:first').focus();
      });

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

    $('#submitStudentRFID').submit(function(e) {
        e.preventDefault();

        var formDataRFID = {
            studentID: studentID_GLOBAL,
            studentName: studentName_GLOBAL,
            year: year_GLOBAL,
            programID: programID_GLOBAL,
            rfid: $('#studentRFID').val()
        };

        $.ajax({
            url: '../php-api/UpdateRFID.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formDataRFID),
            success: function(response) {
                var jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                if (jsonResponse.status === 'success') {
                    alert(jsonResponse.message);
                    $('#studentNo').val('');
                    $('#studentName').val('');
                    $('#studentYear').val('');
                    $('#studentProgram').val('');
                    $('#studentRFID').val('');
                    fetchStudentData();
                    $('#studentRFID').val('');
                    $('#show_rfid-area').click();
                } else {
                    alert(jsonResponse.message);
                    console.log(formDataRFID);
                    $('#studentRFID').val('');
                }

                
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
                $('#studentRFID').val('');
                $('#show_rfid-area').click();
            }
        });
        

    });

    $('#show_rfid-area').click(function() {
        if ($('.rfid-area').css('display') === 'none') {
            $('.rfid-area').show();
            $('#show_rfid-area').text('Hide RFID');
            $('#submitStudentRFID input:first').focus();
        } else {
            $('.rfid-area').hide();
            $('#show_rfid-area').text('Update RFID');
        }
    });

    
  });


</script>
</html>