<div id="addStudentModal" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm">
                    <div class="mb-3">
                        <label for="studentNo" class="form-label">Student Number</label>
                        <input type="number" class="form-control" id="studentNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="studentName" required>
                    </div>
                    <div class="mb-3">
                        <label for="studentProgram" class="form-label">Program</label>
                        <select class="form-select" id="studentProgram" required>
                            <option value="">Select Program</option>
                            <!-- Programs will be dynamically loaded -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="studentYear" class="form-label">Year</label>
                        <select class="form-select" id="studentYear" required>
                            <option value="">Select Year</option>
                            <option value="1st">1st Year</option>
                            <option value="2nd">2nd Year</option>
                            <option value="3rd">3rd Year</option>
                            <option value="4th">4th Year</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</div>