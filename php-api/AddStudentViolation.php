<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    $response = [
        'status' => 'error',
        'message' => 'Unauthorized access.'
    ];
    echo json_encode($response);
    exit();
}

require('connect.php');

// Initialize response
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize and validate input
    $studentID = filter_input(INPUT_POST, 'StudentID', FILTER_SANITIZE_NUMBER_INT);
    $violationType = filter_input(INPUT_POST, 'ViolationType', FILTER_SANITIZE_STRING);
    $violationDate = filter_input(INPUT_POST, 'ViolationDate', FILTER_SANITIZE_STRING);
    $violateAttendance = 1;
    $violateViolated = 1;

    $notes = filter_input(INPUT_POST, 'Notes', FILTER_SANITIZE_STRING);
    $violationStatus = 'Pending'; // Default status

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

    // Input validation
    if (!$studentID || !$violationType || !$violationDate) {
        http_response_code(400);
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields. Please provide StudentID, ViolationType, and ViolationDate.'
        ];
    } else {
        try {
            // Insert into the new DailyRecords table
            $sql = "INSERT INTO DailyRecords (
                        StudentID, ViolationType, ViolationDate, Attendance, 
                        Violated, Notes, ViolationPicture, ViolationStatus
                    ) VALUES (
                        :StudentID, :ViolationType, :ViolationDate, :Attendance, 
                        :Violated, :Notes, :ViolationPicture, :ViolationStatus
                    )";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':StudentID', $studentID, PDO::PARAM_INT);
            $stmt->bindParam(':ViolationType', $violationType, PDO::PARAM_STR);
            $stmt->bindParam(':ViolationDate', $violationDate, PDO::PARAM_STR);
            $stmt->bindParam(':Attendance', $violateAttendance, PDO::PARAM_INT);
            $stmt->bindParam(':Violated', $violateViolated, PDO::PARAM_INT);
            $stmt->bindParam(':Notes', $notes, PDO::PARAM_STR);
            $stmt->bindParam(':ViolationPicture', $violationPicture, PDO::PARAM_STR);
            $stmt->bindParam(':ViolationStatus', $violationStatus, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Violation added successfully.',
                    'recordID' => $conn->lastInsertId()
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to add violation. Please try again.'
                ];
            }
        } catch (PDOException $e) {
            http_response_code(500);
            $response = [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
} else {
    http_response_code(405);
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method. Use POST.'
    ];
}

// Final unified response
header('Content-Type: application/json');
echo json_encode($response);
?>
