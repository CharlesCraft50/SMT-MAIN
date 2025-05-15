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
    $violatedPicture = "images/placeholder.png";
    $notes = filter_input(INPUT_POST, 'Notes', FILTER_SANITIZE_STRING);
    $violationStatus = 'Pending'; // Default status

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
            $stmt->bindParam(':ViolationPicture', $violatedPicture, PDO::PARAM_STR);
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
