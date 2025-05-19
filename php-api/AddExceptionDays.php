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
    $startDate = isset($_POST['startDate']) ? filter_var($_POST['startDate'], FILTER_SANITIZE_STRING) : null;
    $endDate = isset($_POST['endDate']) ? filter_var($_POST['endDate'], FILTER_SANITIZE_STRING) : null;
    $weekDay = isset($_POST['weekDay']) ? filter_var($_POST['weekDay'], FILTER_SANITIZE_STRING) : null;
    $description = isset($_POST['description']) ? filter_var($_POST['description'], FILTER_SANITIZE_STRING) : null;



    if ((!$startDate && !$weekDay) || ($startDate && $weekDay)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Please choose either a date range or a weekday — not both.'
        ]);
        exit();
    }

    try {
        // Delete from new DailyRecords table
        $sql = 'INSERT INTO ExceptionDays (
                StartDate, EndDate, Weekday, Description
                )
                VALUES (
                    :StartDate, :EndDate, :Weekday, :Description
                )';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':StartDate', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':EndDate', $endDate, PDO::PARAM_STR);
        $stmt->bindParam(':Weekday', $weekDay, PDO::PARAM_STR);
        $stmt->bindParam(':Description', $description, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $response = [
                'status' => 'success',
                'message' => 'Days successfully added!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to add days. Please try again.'
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
