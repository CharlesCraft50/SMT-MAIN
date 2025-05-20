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
            responseMessage.html(`<div class="modern-alert modern-alert-success" style="position: absolute;">Successfully Login!</div>`);
            setTimeout(function () {
                window.location.href = 'StudentInformation.php';
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