<?php
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentID = $_POST['StudentID'];
    $violationDate = $_POST['ViolationDate'];
    $violationType = $_POST['ViolationType'];
    $notes = $_POST['Notes'];
    $violationStatus = 'Pending';

    // Default image if none uploaded
    $violationPicture = 'images/placeholder.png';

    // Handle file upload if image is provided
    if (!empty($_FILES['ViolationPicture']['name'])) {
        $targetDir = '../php-api/content/images/';
        $originalFileName = basename($_FILES['ViolationPicture']['name']);
        $fileExt = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // Generate unique filename with datetime
        $uniqueFileName = 'violation_' . date('Ymd_His') . '.' . $fileExt;
        $targetFilePath = $targetDir . $uniqueFileName;

        // Move uploaded file to target directory
        if (move_uploaded_file($_FILES['ViolationPicture']['tmp_name'], $targetFilePath)) {
            $violationPicture = 'content/images/' . $uniqueFileName;
        }
    }

    try {
        // Insert into DailyRecords table
        $sql = 'INSERT INTO DailyRecords 
                (StudentID, ViolationDate, Attendance, Violated, ViolationType, Notes, ViolationPicture, ViolationStatus) 
                VALUES 
                (:StudentID, :ViolationDate, 1, 1, :ViolationType, :Notes, :ViolationPicture, :ViolationStatus)';

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':StudentID' => $studentID,
            ':ViolationDate' => $violationDate,
            ':ViolationType' => $violationType,
            ':Notes' => $notes,
            ':ViolationPicture' => $violationPicture,
            ':ViolationStatus' => $violationStatus
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Violation added successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
