$(document).ready(function () {
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
                        data-rfid="${student.RFID}" 
                        data-attendance="${student.AttendanceTotal}">
                        <td>${student.StudentID}</td>
                        <td>${student.StudentName}</td>
                        <td>${student.YearLevel} Year / ${student.ProgramCode}</td>
                        <td>${student.RFIDStatus}</td>
                        <td>${student.AttendanceTotal}</td>
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
                showResponseMessage('#responseMessage', jsonResponse.message, 'success');
                $('#studentNo').val('');
                $('#studentName').val('');
                $('#studentYear').val('');
                $('#studentProgram').val('');
                $('#studentRFID').val('');
                fetchStudentData();
                $('#studentRFID').val('');
                $('#show_rfid-area').click();
            } else {
                showResponseMessage('#responseMessage', jsonResponse.message, 'danger');
                console.log(formDataRFID);
                $('#studentRFID').val('');
            }

            
        },
        error: function(xhr, status, error) {
            showResponseMessage('#responseMessage', 'Error: ' + error, 'danger');
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