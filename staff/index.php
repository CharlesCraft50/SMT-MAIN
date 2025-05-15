<?php include '../header.html';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff</title>
    <link rel="stylesheet" href="../assets/styles.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <body>
    <div class="container" style="margin-top: 3rem !important;">
        <div class="content d-flex justify-content-center align-items-center">
            <h1 class="text-center roboto-black f-blue">SIGN-IN</h1>
        </div>
    </div>

    <div class="container" style="">
      <div class="content d-flex justify-content-center align-items-center">
        <div class="">
          <div>
              <!-- <input type="number" id="studentID" required>
              <div class="underline"></div>
              <label>Student Number</label>
              <button type="submit" class="submit-btn">
                <i class="bi bi-arrow-right fs-2"></i>
              </button> -->
              <div class="card" style="width: 18rem;">
                <img src="../images/tap-id.png" class="card-img-top" alt="...">
                <div class="card-body">
                  <div id="responseMessage" class="mt-3 text-center d-flex justify-content-center align-items-center"></div>
                  <p class="card-text text-center">Please Tap Your ID</p>
                  
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container mt-3 mb-5" style="">
      <div class="content d-flex justify-content-center align-items-center">
        <div class="wrapper">
          <form class="input-data" id="attendanceForm">
              <input type="number" id="studentID" required>
              <div class="underline"></div>
              <label>Student Number</label>
              <button type="submit" class="submit-btn">
                <i class="bi bi-arrow-right fs-2"></i>
              </button>
          </form>
        </div>
      </div>
    </div>

    

    <!-- Floating Icon -->
    <div class="position-fixed bottom-0 end-0 m-3">
        <i class="bi bi-person-circle custom-size icon-circle text-primary cursor-pointer" data-bs-toggle="modal" data-bs-target="#changeUserTypeModal" style="color: #0d67a1 !important;"></i>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="changeUserTypeModal" tabindex="-1" aria-labelledby="changeUserTypeModalLabel" aria-hidden="true">
      <div class="modal-dialog custom-modal position-absolute" style="bottom: 60px; right: 30px; width: 20rem;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeUserTypeModalLabel">Change User?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- <div class="modal-body">
                    Change User?
                </div> -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="../index.php" class="btn btn-primary">Confirm</a>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="js/main.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
      .wrapper{
        width: 450px;
        background: #fff;
        padding: 30px;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        border-radius: 20px;
        background-color: #D9F0FF;
      }
      .wrapper .input-data{
        height: 40px;
        width: 100%;
        position: relative;
        display: flex; /* Use flexbox for alignment */
        align-items: center;
      }
      .wrapper .input-data input{
        height: 100%;
        width: 100%;
        border: none;
        font-size: 17px;
        border-bottom: 2px solid silver;
        outline: none;
        background-color: #D9F0FF;
      }
      .input-data input:focus ~ label,
      .input-data input:valid ~ label{
        transform: translateY(-20px);
        font-size: 15px;
        color: #4158d0;
      }
      .wrapper .input-data label{
        position: absolute;
        bottom: 10px;
        left: 0;
        color: grey;
        pointer-events: none;
        transition: all 0.3s ease;
      }
      .input-data .underline{
        position: absolute;
        height: 2px;
        width: 100%;
        bottom: 0;
      }
      .input-data .underline:before{
        position: absolute;
        content: "";
        height: 100%;
        width: 100%;
        background: #4158d0;
        transform: scaleX(0);
        transform-origin: center;
        transition: transform 0.3s ease;
      }
      .input-data input:focus ~ .underline:before,
      .input-data input:valid ~ .underline:before{
        transform: scaleX(1);
      }
      .submit-btn {
        background: none;
        border: none;
        cursor: pointer;
        margin-left: 10px;
      }

      .submit-btn i {
        font-size: 24px;
        color: silver;
        transition: color 0.3s ease;
      }

      .submit-btn:hover i {
        color: #1a73e8;
      }
      input[type="number"] {
        -moz-appearance: textfield;
        -webkit-appearance: none;
        appearance: none;
      }
      input[type="number"]::-webkit-inner-spin-button, 
      input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      .cursor-pointer {
        cursor: pointer;
      }
      .icon-circle {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        background-color: #D9F0FF;
        border-radius: 50%;
        padding: 0;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      .icon-circle:hover {
        transform: scale(1.1);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3); 
      }
      .custom-size{
        font-size: 3.5rem;
      }
    </style>

    <script>
      $(document).ready(function(){
        $('#studentID').focus();
        $('#attendanceForm').submit(function(event){
          event.preventDefault();
          const studentID = $('#studentID').val();

          $.ajax({
            url: '../php-api/AttendanceHandler.php',
            type: 'POST',
            data: { studentID: studentID },
            dataType: 'json',
            success: function (response) {
              const responseMessage = $('#responseMessage');
              responseMessage.empty();

              if (response.status === 'success') {
                responseMessage.html(`<div class="modern-alert modern-alert-success" style="position: absolute;">${response.message}</div>`);
                setTimeout(function () {
                  window.location.href = 'staffRecord.php';
                }, 300);
              } else {
                responseMessage.html(`<div class="modern-alert modern-alert-danger" style="position: absolute;">${response.message}</div>`);
              }
            },
            error: function () {
              $('#responseMessage').html('<div class="modern-alert modern-alert-danger" style="position: absolute;">An error occurred while processing your request.</div>');
            }
          });

          setTimeout(() => {
            $('.modern-alert').fadeOut(1000, function () {
              $(this).remove();
            });
          }, 1000);
        });

        function detectNumber(event) {
            // Get the key code (which is a number representing the key pressed)
            const keyCode = event.keyCode || event.which; // Compatibility for old browsers

            // Check if the key is a number (0-9)
            if (keyCode >= 48 && keyCode <= 57) {
                $('#studentID').focus();
            } else {

            }
        }

        // Add event listener for keydown or keypress event
        document.addEventListener('keydown', detectNumber);

        
      });
    </script>
</body>
</html>