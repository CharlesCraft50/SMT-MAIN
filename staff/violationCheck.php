<?php include '../header.html';?>

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
    <title>Select User</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      #video, #canvas {
        aspect-ratio: 4 / 3;
        object-fit: cover;
      }
      button {
        margin-top: 10px;
      }
      button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
      }
      #timer {
        font-size: 50px;
        color: red;
        text-align: center;
        font-weight: bold;
      }
      #instruction {
        font-size: 24px;
        color: red;
        text-align: center;
        font-weight: bold;
      }
      /* Updated loading div styles */
      #loading {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
      }

      /* Spinner styling */
      .spinner {
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
      }

      /* Spinner animation */
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      /* Optional: Loading text below spinner */
      #loading-text {
        margin-top: 10px;
        color: #3498db;
        font-size: 18px;
        font-weight: bold;
      }

      #responseMessage {
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
      }
    </style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="text-center">
      <h1 class="mb-4 fw-bold text-primary">Verification</h1>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <video id="video" autoplay playsinline class="rounded border shadow w-100 mb-3"></video>
          <canvas id="canvas" class="rounded border shadow w-100 d-none"></canvas>
        </div>
      </div>

      <div class="d-flex justify-content-center gap-3 mb-4 camera-buttons">
        <button id="startCamera" class="btn btn-outline-primary px-4">
          <i class="bi bi-camera-video"></i> Start Camera
        </button>
        <button id="capture" class="btn btn-primary px-4" disabled>
          <i class="bi bi-camera"></i> Capture Photo
        </button>
      </div>

      <div id="timer" class="text-danger fw-bold fs-1 mb-2"></div>
      <div id="instruction" class="text-danger fs-5 mb-4">Please face the camera with your uniform or ID visible.</div>

      <!-- Loading Spinner -->
      <div id="loading">
        <div class="spinner mb-2"></div>
        <div id="loading-text">Processing... Please wait.</div>
      </div>

      <!-- Floating Message Box -->
      <div id="responseMessage" class="modern-alert" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:10000;"></div>
    </div>
  </div>

  <script>
    $(document).ready(() => {
      let stream;
      let countRestart = 0;
      let homeRedirect = true;
      let exceptionDay = false;

      const canvas = document.getElementById('canvas');
      const video = document.getElementById('video');
      const startCameraButton = document.getElementById('startCamera');
      const captureButton = document.getElementById('capture');
      const timerElement = document.getElementById('timer');
      const instructionElement = document.getElementById('instruction');

      const hideButtons = () => {
        $('#loading').hide();
        $('#timer').hide();
        $('#instruction').hide();
        $('.camera-buttons').remove();
      };

      const goHome = () => {
        setTimeout(() => {
          if(homeRedirect) {
            window.location.href = "../staff";
          }
        }, 1500);
      };

      const fetchExceptionDays = () => {
          $.ajax({
              url: '../php-api/CheckIfExceptionDay.php',
              method: 'GET',
              dataType: 'json',
              success: (response) => {
                  exceptionDay = response.exceptionDay;
                  attendanceStatus = response.attendanceStatus;
                  
                  if (!exceptionDay) {
                    if(attendanceStatus == "not_attended") {
                      startCameraButton.click();
                    } else if(attendanceStatus == "attended_recently" || attendanceStatus == "attended_and_timed_out") {
                      showResponseMessage('🚫 Attended already!', 'danger');
                      hideButtons();
                      goHome();
                    } else if(attendanceStatus == "timeout_updated") {
                      showResponseMessage('⏰ Timed Out!', 'warning');
                      hideButtons();
                      goHome();
                    }
                  } else {
                    if(attendanceStatus == "auto_time_in") {
                      showResponseMessage('✅ Attended!', 'success');
                    } else if(attendanceStatus == "attended_recently" || attendanceStatus == "attended_and_timed_out") {
                      showResponseMessage('🚫 Attended already!', 'danger');
                    } else if(attendanceStatus == "timeout_updated") {
                      showResponseMessage('⏰ Timed Out!', 'warning');
                    }
                    
                    hideButtons();

                    goHome();
                  }
              },
              error: (xhr, status, error) => {
                  console.error('AJAX Error:', error);
              }
          });
      };

      async function startCamera() {
        try {
          stream = await navigator.mediaDevices.getUserMedia({ video: true });
          video.srcObject = stream;

          captureButton.disabled = false;

          video.addEventListener('loadeddata', () => {
            const checkInterval = setInterval(() => {
              const tempCanvas = document.createElement('canvas');
              tempCanvas.width = video.videoWidth;
              tempCanvas.height = video.videoHeight;
              const tempCtx = tempCanvas.getContext('2d');
              tempCtx.drawImage(video, 0, 0, tempCanvas.width, tempCanvas.height);

              const frame = tempCtx.getImageData(0, 0, tempCanvas.width, tempCanvas.height);
              const pixels = frame.data;

              let total = 0;
              for (let i = 0; i < pixels.length; i += 4) {
                const r = pixels[i];
                const g = pixels[i + 1];
                const b = pixels[i + 2];
                const brightness = (r + g + b) / 3;
                total += brightness;
              }

              const avgBrightness = total / (pixels.length / 4);

              if (avgBrightness > 30) {
                clearInterval(checkInterval);

                let countdown = 1;
                timerElement.textContent = countdown;

                const countdownInterval = setInterval(() => {
                  countdown--;
                  timerElement.textContent = countdown;

                  if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    captureButton.click();
                  }
                }, 1000);
              }
            }, 500);
          });

          captureButton.onclick = async () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            stopCamera(); // Stop camera & hide video
            await sendToPrediction(); // Send image to /predict
          };

        } catch (error) {
          console.error('Error accessing the camera:', error);
          alert('Unable to access the camera. Please allow camera access.');
        }
      }

      function stopCamera() {
        if (stream) {
          stream.getTracks().forEach(track => track.stop());
          video.style.display = 'none';
          captureButton.disabled = true;
        }
      }

      async function sendToPrediction() {
        const loadingDiv = document.getElementById('loading');
        loadingDiv.style.display = 'flex'; // Show loading

        canvas.toBlob(async function (blob) {
          const formData = new FormData();
          formData.append('file', blob, 'capture.jpg');

          try {
            const response = await fetch('http://127.0.0.1:8000/predict/', {  // Make sure the URL is correct
              method: 'POST',
              body: formData
            });

            const result = await response.json(); // Make sure the result is properly assigned

            // Now that we have the 'result', we can use it to set the uniform status
            formData.append('uniformStatus', result.prediction === "Uniform" ? 1 : 0);  // Send uniform status based on prediction result

            console.log(result.prediction);
            console.log(result.prediction === "Uniform" ? 1 : 0);

            // Send the image and uniform status to your PHP script
            const phpResponse = await fetch('../php-api/AddAttendance.php', {  // Update with actual PHP script path
              method: 'POST',
              body: formData
            });

            const phpResult = await phpResponse.json();
            loadingDiv.style.display = 'none'; // Hide loading

            

            if(result.error == "No person detected") {
              showResponseMessage("No person detected! Please Try Again!", phpResult.level);
              startCameraButton.click();
            } else {
              showResponseMessage(phpResult.message, phpResult.level);
              hideButtons();
            }

            goHome();
          } catch (error) {
            console.error('Prediction error:', error);
            loadingDiv.style.display = 'none'; // Hide loading
            showResponseMessage("⚠️ Error sending image for prediction.", 'danger');
            setTimeout(() => {
              if(countRestart <= 1) {
                startCameraButton.click();
              } else {
                if(homeRedirect) {
                  window.location.href = "../staff";
                }
              }
              
              countRestart++;
            }, 1000);
            
          }
        }, 'image/jpeg');
      }

      function showResponseMessage(message, type) {
        const msgBox = document.getElementById('responseMessage');
        msgBox.className = `modern-alert ${type === 'success' ? 'modern-alert-success' : 'modern-alert-danger'}`;
        msgBox.textContent = message;
        msgBox.style.display = 'block';
        msgBox.style.opacity = '1';

        // Fade out after 2 seconds
        setTimeout(() => {
          msgBox.style.opacity = '0';
          setTimeout(() => msgBox.style.display = 'none', 300); // Wait for opacity transition to finish
        }, 3000);
      }

      startCameraButton.addEventListener('click', startCamera);

      
        fetchExceptionDays();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
