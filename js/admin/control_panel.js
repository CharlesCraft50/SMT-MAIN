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
                showResponseMessage('#responseMessage', 'Updated to ' + (this.checked ? 'Automatic' : 'Manual'), 'success');
            },
            error: (xhr, status, error) => {
                showResponseMessage('#responseMessage', 'Error updating checking behavior.', 'danger');
                console.error('AJAX Error:', error);
            }
        });
    });

    const fetchCheckingBehavior = () => {
        $.ajax({
            url: '../php-api/ReadCheckingBehavior.php',
            method: 'GET',
            dataType: 'json',
            success: (response) => {
                $("#automaticChecking").prop('checked', turnOn = response.turnOn);
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

    fetchExceptionDays();
    fetchCheckingBehavior();
});