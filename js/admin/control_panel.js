$(document).ready(function () {
    flatpickr("#dateRange", {
        mode: "range", // Enables start-to-end date picking
        dateFormat: "Y-m-d",
        minDate: "today", // Optional: disallow past dates
    });

    let isClearing = false;
    let turnOn;

    $('#dateRange').on('input', function () {
        if (isClearing) return;
        if ($(this).val()) {
            isClearing = true;
            $('#weekDay').val('');
            isClearing = false;
        }
    });

    $('#weekDay').on('change', function () {
        if (isClearing) return;
        if ($(this).val()) {
            isClearing = true;
            $('#dateRange').val('');
            isClearing = false;
        }
    });

    $('#submitDateEvent').click(() => {
        let dateRange = $('#dateRange').val() || null;
        let weekDay = $('#weekDay').val() || null;
        let description = $('#description').val() || null;

        if((!dateRange && !weekDay) || (dateRange && weekDay)) {
            showResponseMessage('#responseMessage', 'Please choose either a date range or a weekday — not both.', 'danger');
            return;
        }

        let queryData = {
            description: description
        };

        if (dateRange) {
            const [start, end] = dateRange.split(' to ');
            queryData.startDate = start;
            queryData.endDate = end;
        } else if (weekDay) {
            queryData.weekDay = weekDay;
        }
        

        $.ajax({
            url: '../php-api/AddExceptionDays.php',
            method: 'POST',
            dataType: 'json',
            data: queryData,
            success: (response) => {
                showResponseMessage('#responseMessage', response.message, 'success');
                fetchExceptionDays();
            },
            error: (xhr, status, error) => {
                console.error('AJAX Error:', error);
                
                const responseText = xhr.responseText;

                if (responseText && responseText.includes('Duplicate entry')) {
                    showResponseMessage('#responseMessage', 'Please choose another date as it has already been set!', 'danger');
                } else {
                    showResponseMessage('#responseMessage', 'Something went wrong. Please try again.', 'danger');
                }
            }
        });
    });

   $("#automaticChecking").change(function () {
        $.ajax({
            url: '../php-api/UpdateCheckingBehavior.php',
            method: 'POST',
            data: JSON.stringify({ turnOn: this.checked }),
            dataType: 'json',
            success: (response) => {
                let checkingBehavior = this.checked ? 'Automatic' : 'Manual';
                showResponseMessage('#responseMessage', 'Updated to ' + checkingBehavior, 'success');

                $('#automaticCheckingText').text(checkingBehavior + ' Checking');

            },
            error: (xhr, status, error) => {
                showResponseMessage('#responseMessage', 'Error updating checking behavior.', 'danger');
                console.error('AJAX Error:', error);
            }
        });

        if (this.checked) {
            $('#manualUpdateViolationsArea').slideUp();
        } else {
            $('#manualUpdateViolationsArea').slideDown();
        }
    });

    const fetchCheckingBehavior = () => {
        $.ajax({
            url: '../php-api/ReadCheckingBehavior.php',
            method: 'GET',
            dataType: 'json',
            success: (response) => {
                let checkingBehavior = response.turnOn ? 'Automatic' : 'Manual';
                $('#automaticCheckingText').text(checkingBehavior + ' Checking');
                $("#automaticChecking").prop('checked', turnOn = response.turnOn);
                if (response.turnOn) {
                    $('#manualUpdateViolationsArea').slideUp();
                } else {
                    $('#manualUpdateViolationsArea').slideDown();
                }

            },
            error: (xhr, status, error) => {
                console.error('AJAX Error:', error);
            }
        });
    };

    const fetchExceptionDays = () => {
        $.ajax({
            url: '../php-api/ReadExceptionDays.php',
            method: 'GET',
            dataType: 'json',
            success: (response) => {
                displayTable(response.data);
            },
            error: (xhr, status, error) => {
                console.error('AJAX Error:', error);
            }
        });
    };

    $('#manualUpdateViolations').click(function() {
        $('#manualUpdateViolationsLoading').removeClass('d-none');
        $.ajax({
            url: "http://127.0.0.1:8000/predict/manual_folder/",
            method: "GET",
            processData: false,
            contentType: false,
            success: function (response) {
                Object.entries(response).forEach(([key, predictions]) => {
                const studentID = key.split('-').pop(); // get ID after last hyphen
                const studentName = key.split('-').slice(0, -1).join('_'); // student name
                    
                predictions.forEach(pred => {
                    const violationType = pred.prediction.toLowerCase() === 'no_uniform' ? 'WithoutUniform' : null;
                    const fileName = pred.filename;
                    const studentNameFolder = key;

                    if (violationType) {
                        const violationForm = new FormData();
                        violationForm.append('ViolationType', violationType); // 0 means Non-Uniform
                        violationForm.append('StudentID', studentID);
                        violationForm.append('StudentFolderName', studentNameFolder);
                        violationForm.append('FileName', fileName);

                        $.ajax({
                            url: '../php-api/AddViolationManually.php',
                            method: 'POST',
                            data: violationForm,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                $('#manualUpdateViolationsLoading').addClass('d-none');
                                showResponseMessage('#responseMessage', response.message, 'success');
                            },
                            error: function () {
                                showResponseMessage('#responseMessage', 'Failed to insert violation for', studentName, 'danger');
                            }
                        });
                    }
                });
                });
            },
            error: function (xhr, status, error) {
                $('#manualUpdateViolationsLoading').addClass('d-none');
                console.error('Violation POST failed:', xhr.responseText);
                showResponseMessage('#responseMessage', `Failed to insert violation for ${studentName}`, 'danger');
            }
            });

    });

    const displayTable = (data) => {
        let tableBody = $('.exception-days-table-body');
        tableBody.empty();

        if (data.length === 0) {
            tableBody.append('<tr><td colspan="3">No exception days found</td></tr>');
        } else {
            data.forEach(eday => {
                let displayStartDate = eday.StartDate ? eday.StartDate : '';
                let displayEndDate = eday.EndDate ? eday.EndDate : '';
                let displayWeekday = eday.Weekday ? eday.Weekday : '';
                let displayDescription = eday.Description ? eday.Description : '';

                let row = `
                    <tr data-id="${eday.id}" 
                        data-dates="${eday.Dates}" 
                        data-weekday="${eday.Weekday}" 
                        data-description="${eday.Description}">
                        <td>${(displayStartDate) ? (displayStartDate + " to " + displayEndDate) : displayWeekday}</td>
                        <td>${displayDescription}</td>
                        <td><button class="btn btn-danger" data-id="${eday.id}" onclick="deleteExceptionDay(this)">&times;</button></td>
                    </tr>`;

                tableBody.append(row);
            });
        }
    };

    window.deleteExceptionDay = (button) => {
        var exceptionDayId = $(button).data('id');
        // if (confirm('Are you sure you want to delete this date?')) {
            $.ajax({
                url: '../php-api/DeleteExceptionDay.php',
                method: 'POST',
                data: { id: exceptionDayId },
                success: function(response) {
                    if (response.status === 'success') {
                        const $row = $(button).closest('tr');
                        const $tableBody = $row.closest('tbody');

                        $row.remove();

                        if ($tableBody.find('tr').length === 0) {
                            $tableBody.append('<tr><td colspan="3">No exception days found</td></tr>');
                        }
                        //showResponseMessage('#responseMessage', 'Date deleted successfully!', 'success');
                    } else {
                        showResponseMessage('#responseMessage', 'Failed to delete violation: ' + response.message, 'danger');
                    }
                },
                error: function() {
                    showResponseMessage('#responseMessage', 'Error deleting violation.', 'danger');
                }
            });
        // }
    }

    $('#archiveRecordsBtn').click(function () {
        // Show loading spinner
        $('#archiveLoading').removeClass('d-none');

        $.ajax({
            url: '../php-api/ArchiveStudentRecords.php',
            method: 'POST',
            dataType: 'json',
            success: function (response) {
                $('#archiveLoading').addClass('d-none');

                if (response.success) {
                    showResponseMessage('#responseMessage', response.message || 'Student records successfully archived.', 'success');
                } else {
                    showResponseMessage('#responseMessage', response.message || 'Archiving failed.', 'danger');
                }
            },
            error: function (xhr, status, error) {
                $('#archiveLoading').addClass('d-none');
                console.error('AJAX Error:', error);
                showResponseMessage('#responseMessage', 'An error occurred while archiving student records.', 'danger');
            }
        });
    });


    fetchExceptionDays();
    fetchCheckingBehavior();
});