$(document).ready(function(){
    let showSideBar = true;

    
    $('.hamburger-button').click(function () {
        showSideBar = !showSideBar;

        $('.hamburger-button i').toggleClass('bi-list bi-x');

        if (!showSideBar) {
            $('.sidebar').addClass('hidden');
            $('.main-content').addClass('expanded');
            $('.blue__bar').addClass('expanded');
        } else {
            $('.sidebar').removeClass('hidden');
            $('.main-content').removeClass('expanded');
            $('.blue__bar').removeClass('expanded');
        }
    });

    $('.hamburger-button i').toggleClass('bi-list bi-x');

    window.showResponseMessage = (id, message, type) => {
        const msgBox = document.querySelector(id);
        msgBox.className = `modern-alert ${type === 'success' ? 'modern-alert-success' : 'modern-alert-danger'}`;
        msgBox.textContent = message;
        msgBox.style.display = 'block';
        msgBox.style.opacity = '1';

        // Fade out after 2 seconds
        setTimeout(() => {
        msgBox.style.opacity = '0';
        setTimeout(() => msgBox.style.display = 'none', 300); // Wait for opacity transition to finish
        }, 3000);
    };

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
                    showResponseMessage('#responseMessage', jsonResponse.message, 'success');
                    $('#studentNo').val('');
                    $('#studentName').val('');
                    $('#studentYear').val('');
                    $('#studentProgram').val('');
                    fetchStudentData();
                } else {
                    showResponseMessage('#responseMessage', jsonResponse.message, 'danger');
                }
            },
            error: function(xhr, status, error) {
                showResponseMessage('#responseMessage', 'Error: ' + error, 'danger');
            }
        });
    });
});