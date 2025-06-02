<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];

    // Sanitize and validate input
    $recordID = isset($_POST['ViolationID']) ? filter_var($_POST['ViolationID'], FILTER_SANITIZE_NUMBER_INT) : null;

    if (!$recordID) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required field. Please provide ViolationID.'
        ]);
        exit();
    }

    try {
        // Delete from new DailyRecords table
        $sql = 'DELETE FROM DailyRecords WHERE RecordID = :RecordID';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':RecordID', $recordID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Violation deleted successfully.'
                ];
            } else {
                $response = [
                    'status' => 'failed',
                    'message' => 'No violation found with the provided RecordID.'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to delete violation. Please try again.'
            ];
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $response = [
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Use POST.'
    ]);
}
?>
