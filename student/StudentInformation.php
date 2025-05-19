<?php
session_start();
if (!isset($_SESSION['student'])) {
  // If not set, exit the script or redirect to a different page
  echo "No student session found. Exiting.";
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
        body {
            font-family: "Roboto", sans-serif;
            margin: 0;
            display: flex;
            background-color: #f4f4f4;
        }
        .header{
            width: 100%;
        }

        .blue__bar{
            background-color: #0D67A1;
            width: 100%;
            height: 8vh;
        }

        .yellow__bar{
            background-color: #FFF200;
            width: 100%;
            height: 2vh;
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
        .student-info-display{
            color: #0D67A1;
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
    <div class="main-content">
        <div class="header">
            <div class="blue__bar">
            </div>
            <div class="yellow__bar"></div>
        </div>

        <div class="d-flex align-items-center justify-content-center mt-1" style="height: 89vh;">
            <!-- Student Info Section -->
            <div class="content-area d-flex flex-column align-items-center justify-content-center text-align-center me-5" style="width: 40rem;">
                <div class="student-info-area text-align-center w-100">
                    <h1 style="text-align: center; margin-bottom: 2rem;">Student Information</h1>
                    <div class="d-flex align-items-center mb-4 p-3" 
                        style="background-color: #D9F0FF; border-radius: 10px; width: 100%;">
                        <strong class="fs-2 student-info-display" style="flex: 1; text-align: left;">Student No:</strong>
                        <p class="fs-1 student-info-display mb-0" style="flex: 1; text-align: right;" id="studentNoDisplay"></p>
                    </div>
                    <div class="d-flex align-items-center mb-4 p-3" 
                        style="background-color: #D9F0FF; border-radius: 10px; width: 100%;">
                        <strong class="fs-2 student-info-display" style="flex: 1; text-align: left;">Name:</strong>
                        <p class="fs-1 student-info-display mb-0" style="flex: 1; text-align: right;" id="studentNameDisplay"></p>
                    </div>
                    <div class="d-flex align-items-center mb-4 p-3" 
                        style="background-color: #D9F0FF; border-radius: 10px; width: 100%;">
                        <strong class="fs-2 student-info-display" style="flex: 1; text-align: left;">Year Level:</strong>
                        <p class="fs-1 student-info-display mb-0" style="flex: 1; text-align: right;" id="studentYearDisplay"></p>
                    </div>
                    <div class="d-flex align-items-center p-3" 
                        style="background-color: #D9F0FF; border-radius: 10px; width: 100%;">
                        <strong class="fs-2 student-info-display" style="flex: 1; text-align: left;">Program:</strong>
                        <p class="fs-1 student-info-display mb-0" style="flex: 1; text-align: right;" id="studentCourseDisplay"></p>
                    </div>
                </div>
            </div>

            <div class="content-area d-flex flex-column align-items-center justify-content-center text-align-center me-5" style="width: 40rem;">
                <div class="student-info-area text-align-center w-100">
                    <h1 style="text-align: center; margin-bottom: 2rem;">Violations</h1>
                    <div class="d-flex align-items-center mb-4 p-3" 
                        style="background-color: #D9F0FF; border-radius: 10px; width: 100%;">
                        <strong class="fs-2 student-info-display" style="flex: 1; text-align: left;" id="totalViolationsText">Total Violations:</strong>
                        <p class="fs-1 student-info-display mb-0" style="flex: 1; text-align: right;" id="studentTotalViolations"></p>
                    </div>
                
                    <!-- Button Section -->
                    <button type="button" id="closeBtn" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center" style="height: auto; width: auto; background-color: #D9F0FF;">
                        <span class="fs-1"style="margin-right: 0.5rem; color: #0D67A1;">CLOSE</span>
                        <i class="bi bi-box-arrow-in-right fs-1" style="color: #0D67A1;"></i>
                    </button>
                </div>
            </div>

            
        </div>

    
    </div>

</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {

    });
</script>
<script type="text/javascript">

$(document).ready(function(){

    $('#closeBtn').click(() => {
        window.location.href = '../';
    });
  window.fetchStudent = function() {
    $.ajax({
      url: '../php-api/ReadStudentRecord.php', 
      method: 'GET',
      data: { StudentID: <?php echo $_SESSION['student']['StudentID']; ?> },
      success: function(response) {
          if (response.status === 'success') {
              var student = response.student;
              var violations = response.violations;

              // Populate student info in the modal
              $('#studentNoDisplay').text(student.StudentID);
              $('#studentNameDisplay').text(student.StudentName);
              $('#studentYearDisplay').text(student.Year);
              $('#studentCourseDisplay').text(student.ProgramCode);
              $('#studentTotalViolations').text(response.violationCount);
              if(response.violationCount >= 1) {
                $('#totalViolationsText').text('Total Violation:');
              }

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
                                  <button class="btn btn-warning" data-id="${violation.ViolationID}" onclick="editViolation(this)">Update</button>
                                  <button class="btn btn-danger" data-id="${violation.ViolationID}" onclick="deleteViolation(this)">&times;</button>
                              </div>
                          </td>
                      </tr>`;
                  $('#violationDetailsTableBody').append(violationRow);
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
  };
  
  fetchStudent();
});

</script>
</html>