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
    $exceptionID = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT) : null;

    if (!$exceptionID) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required field. Please provide ViolationID.'
        ]);
        exit();
    }

    try {
        // Delete from ExceptionDays table
        $sql = 'DELETE FROM ExceptionDays WHERE id = :id';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $exceptionID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $response = [
                    'status' => 'success',
                    'message' => 'Date deleted successfully.'
                ];
            } else {
                $response = [
                    'status' => 'failed',
                    'message' => 'No date found with the provided id.'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to delete date. Please try again.'
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
