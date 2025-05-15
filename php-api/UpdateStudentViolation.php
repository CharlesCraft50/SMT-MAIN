<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array();

    // Sanitize and validate input
    $recordID = isset($_POST['RecordID']) ? filter_var($_POST['RecordID'], FILTER_SANITIZE_NUMBER_INT) : null;
    $violationDate = isset($_POST['ViolationDate']) ? filter_var($_POST['ViolationDate'], FILTER_SANITIZE_STRING) : null;
    $notes = isset($_POST['Notes']) ? filter_var($_POST['Notes'], FILTER_SANITIZE_STRING) : null;
    $violationStatus = isset($_POST['ViolationStatus']) ? filter_var($_POST['ViolationStatus'], FILTER_SANITIZE_STRING) : null;

    if (!$recordID || !$violationDate || !$violationStatus) {
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields. Please provide RecordID, ViolationDate, and ViolationStatus.'
        ];
        http_response_code(400);
    } else {
        try {
            // Update the DailyRecords table
            $sql = 'UPDATE DailyRecords 
                    SET ViolationDate = :ViolationDate, 
                        Notes = :Notes, 
                        ViolationStatus = :ViolationStatus 
                    WHERE RecordID = :RecordID';

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':ViolationDate', $violationDate, PDO::PARAM_STR);
            $stmt->bindParam(':Notes', $notes, PDO::PARAM_STR);
            $stmt->bindParam(':ViolationStatus', $violationStatus, PDO::PARAM_STR);
            $stmt->bindParam(':RecordID', $recordID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Violation updated successfully.'
                    ];
                } else {
                    $response = [
                        'status' => 'failed',
                        'message' => 'No changes made. Please ensure the data is different.'
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to execute update. Please try again.'
                ];
            }
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
            http_response_code(500);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Use POST.']);
}
?>
