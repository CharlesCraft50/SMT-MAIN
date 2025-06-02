<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = [];

    try {
        $sql = 'SELECT id, StartDate, EndDate, Weekday, Description FROM ExceptionDays';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $exceptionDays = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response = [
            'status' => 'success',
            'message' => 'Days successfully read!',
            'data' => $exceptionDays
        ];
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
