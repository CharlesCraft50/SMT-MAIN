<script type="text/javascript">
    $(document).ready(function(){
        $('#closeBtn').click(() => {
            window.location.href = "../";
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

                } else {
                    alert('Failed to fetch violation details: ' + response.message);
                }
            },
            error: function() {
                alert('Error fetching violation details.');
            }
            });
        };

        const showResponseMessage = (message, type, fadeOut = true) => {
            const msgBox = document.getElementById('responseMessage');
            msgBox.className = `modern-alert ${type === 'success' ? 'modern-alert-success' : 'modern-alert-danger'}`;
            msgBox.textContent = message;
            msgBox.style.display = 'block';
            msgBox.style.opacity = '1';

            if(fadeOut) {
                // Fade out after 2 seconds
                setTimeout(() => {
                    msgBox.style.opacity = '0';
                    setTimeout(() => msgBox.style.display = 'none', 300); // Wait for opacity transition to finish
                }, 3000);
            }
            
        };

        const fetchStudentViolationsData = () => {
            $.ajax({
                url: '../php-api/ReadStudentViolation.php', 
                method: 'GET',
                data: { StudentID: <?php echo $_SESSION['student']['StudentID']; ?> },
                success: function(response) {
                    if (response.status === 'success') {
                        var student = response.student;
                        var violations = response.violations;

                        $('#violationDetailsTableBody').empty();

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
                                    </tr>`;
                                $('#violationDetailsTableBody').append(violationRow);
                            }
                        });

                    } else if(response.status === 'empty') {
                        var student = response.student;
                        var violations = response.violations;

                        $('#violationDetailsTableBody').empty();


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

                    } else {
                        showResponseMessage('#responseMessage', 'Failed to fetch violation details: ' + response.message, 'danger');
                    }
                },
                error: function() {
                    showResponseMessage('#responseMessage', 'Error fetching violation details.', 'danger');
                }
            });
        }

        function formatDateForDisplay(date) {
            const d = new Date(date);
            if (isNaN(d)) return date; // Return original if invalid date
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return d.toLocaleDateString('en-US', options);
        }

        $(document).on('click', '.violation-img', function () {
            const imgSrc = $(this).attr('src');
            const dateText = $(this).closest('tr').find('td').eq(1).text();
            $('#violationModalImg').attr('src', imgSrc);
            $('#violationImageModalLabel').text(dateText);
            $('#violationImageModal').modal('show');
        });

        fetchStudentViolationsData();
    
        fetchStudent();
    });
</script>