<?php
session_start();

// Check if user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    echo json_encode([
        'status' => 'error',
        'code' => 403,
        'message' => 'Unauthorized access.'
    ]);
    exit();
}

require('connect.php');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the input fields
    $studentID = isset($data['studentID']) ? trim($data['studentID']) : '';
    $rfid = isset($data['rfid']) ? trim($data['rfid']) : '';

    if (empty($studentID) || empty($rfid)) {
        $response = [
            'status' => 'error',
            'code' => 400,
            'message' => 'StudentID and RFID are required.'
        ];
    } else {
        try {
            // Check if the student exists
            $stmt = $conn->prepare("SELECT StudentID FROM Students WHERE StudentID = :studentID");
            $stmt->bindParam(':studentID', $studentID, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                $response = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Student not found.'
                ];
            } else {
                // Check if the RFID is already assigned to another student
                $stmt = $conn->prepare("SELECT StudentID FROM Students WHERE RFID = :rfid AND StudentID != :studentID");
                $stmt->bindParam(':rfid', $rfid, PDO::PARAM_STR);
                $stmt->bindParam(':studentID', $studentID, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $response = [
                        'status' => 'error',
                        'code' => 409,
                        'message' => 'RFID is already assigned to another student.'
                    ];
                } else {
                    // Proceed with update
                    $stmt = $conn->prepare("UPDATE Students SET RFID = :rfid WHERE StudentID = :studentID");
                    $stmt->bindParam(':rfid', $rfid, PDO::PARAM_STR);
                    $stmt->bindParam(':studentID', $studentID, PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $response = [
                            'status' => 'success',
                            'code' => 200,
                            'message' => 'RFID updated successfully.'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'code' => 200, // No DB error, but nothing changed
                            'message' => 'No changes were made. The RFID might already be set for this student.'
                        ];
                    }
                }
            }
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'code' => 500,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
} else {
    $response = [
        'status' => 'error',
        'code' => 405,
        'message' => 'Invalid request method. Only POST method is allowed.'
    ];
}

// Output response as JSON
echo json_encode($response);
?>
