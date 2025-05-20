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

        function showResponseMessage(message, type, fadeOut = true) {
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
            
        }
    
        fetchStudent();
    });
</script>