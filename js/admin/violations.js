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

    const fetchViolationsData = () => {
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
    };


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
                            let imgUrl = '../php-api/' + violation.ViolationPicture;
                            var violationRow = `
                                <tr>
                                    <td>${violation.ViolationType}</td>
                                    <td>${formatDateForDisplay(violation.ViolationDate)}</td>
                                    <td>${violation.Notes}</td>
                                    <td>${violation.ViolationStatus}</td>
                                    <td>
                                        <img class="img-thumbnail violation-img" 
                                            style="max-width: 80px; height: auto; cursor: pointer;" 
                                            src="${imgUrl}" 
                                            data-full="${imgUrl}" 
                                            alt="Violation Image">
                                    </td>
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
                                    <img class="img-thumbnail violation-img" 
                                        style="max-width: 80px; height: auto; cursor: pointer;" 
                                        src="${imgUrl}" 
                                        data-full="${imgUrl}" 
                                        alt="Violation Image">
                                </td>
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

    $(document).on('click', '.violation-img', function () {
        const imgSrc = $(this).attr('src');
        const dateText = $(this).closest('tr').find('td').eq(1).text();
        $('#violationModalImg').attr('src', imgSrc);
        $('#violationImageModalLabel').text(dateText);
        $('#violationImageModal').modal('show');
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
        var violationPicture = row.find('td').eq(4).html();

        // Replace the ViolationDate, Notes, and ViolationStatus with input fields, but keep ViolationType unchanged
        row.find('td').eq(1).html(`<input type="date" class="form-control" id="violationDateInput" value="${formatDateForInput(violationDate)}">`);
        row.find('td').eq(2).html(`<input type="text" class="form-control" id="notesInput" value="${notes}">`);
        row.find('td').eq(3).html(`
            <select class="form-control" id="violationStatusDropdown">
                <option value="Pending" ${violationStatus === 'Pending' ? 'selected' : ''}>Pending</option>
                <option value="Reviewed" ${violationStatus === 'Reviewed' ? 'selected' : ''}>Reviewed</option>
            </select>
        `);
        row.find('td').eq(4).html(
            violationPicture
        );

        // Change the Update button to a Save button and add the Delete button
        row.find('td').eq(5).html(`
            <div class="inline-buttons">
                <button class="btn btn-success" data-id="${violationID}" onclick="saveViolationChanges(this)">Save</button>
                <button class="btn btn-danger" data-id="${violationID}" onclick="deleteViolation(this)">&times;</button>
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
        var updatedViolationPicture = row.find('#violation-img').html();

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
                    row.find('td').eq(4).html(updatedViolationPicture);
                    row.find('td').eq(5).html(`
                        <div class="inline-buttons">
                            <button class="btn btn-warning" data-id="${violationID}" onclick="editViolation(this)">Update</button>
                            <button class="btn btn-danger" data-id="${violationID}" onclick="deleteViolation(this)">&times;</button>
                        </div>
                    `);
                    
                    if(response.no_changes) {
                        showResponseMessage('#responseMessage', response.message, 'success');
                    }
                } else {
                    showResponseMessage('#responseMessage', 'Failed to save changes: ' + response.message, 'warning');
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
        // if (confirm('Are you sure you want to delete this violation?')) {
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
        // }
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
                    <input type="file" class="form-control violationNewPictureInput">
                    <img class="img-thumbnail violation-img" 
                        style="max-width: 80px; height: auto; cursor: pointer;" 
                        src="" 
                        alt="Violation Image">
                </td>
                <td>
                    <div class="inline-buttons">
                        <button class="btn btn-success" onclick="saveNewViolation(this)">Save</button>
                        <button class="btn btn-danger" onclick="cancelNewViolation(this)">Cancel</button>
                    </div>
                </td>
            </tr>`;

        // Append the new row to the violations table body
        var $newRow = $(newViolationRow);
        $('#violationDetailsTableBody').prepend($newRow);

        // Bind change event to the newly added file input
        $newRow.find('.violationNewPictureInput').on('change', function (e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    $(e.target).siblings('img.violation-img').attr('src', event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Function to save the new violation
    window.saveNewViolation = (button) => {
        // Get the row containing the new violation inputs
        var row = $(button).closest('tr');
        var violationType = row.find('#newViolationTypeDropdown').val();
        var violationDate = row.find('#newViolationDateInput').val();
        var notes = row.find('#newNotesInput').val();
        var violationImageInput = row.find('.violationNewPictureInput')[0].files[0] || 'images/placeholder.png';
        var violationImageSrc = row.find('img.violation-img').attr('src');
        var status = 'Pending';

        if (!violationDate) {
            showResponseMessage('#responseMessage', 'Please fill out all fields.', 'danger');
            return;
        }

        var formData = new FormData();
        formData.append('ViolationType', violationType);
        formData.append('ViolationDate', violationDate);
        formData.append('Notes', notes);
        formData.append('ViolationStatus', status);
        formData.append('StudentID', $('#studentNoDisplay').text());

        if (violationImageInput instanceof File) {
            formData.append('ViolationPicture', violationImageInput);
        } else {
            formData.append('ViolationPicture', '');
        }

        $.ajax({
            url: '../php-api/AddStudentViolation.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status === "success") {
                    // Replace input fields with the saved data
                    row.find('td').eq(0).text(violationType);
                    row.find('td').eq(1).text(formatDateForDisplay(violationDate));
                    row.find('td').eq(2).text(notes);
                    row.find('td').eq(3).text(status);
                    row.find('td').eq(4).html(`<img class="img-thumbnail violation-img" 
                        style="max-width: 80px; height: auto; cursor: pointer;" 
                        src="${violationImageSrc}" 
                        alt="Violation Image">`);
                    row.find('td').eq(5).html(`
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

});