<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $period = $_GET['period'] ?? 'all';
    $dateCondition = '';

    switch ($period) {
        case 'daily':
            $dateCondition = 'AND DATE(ViolationDate) = CURDATE()';
            break;
        case 'monthly':
            $dateCondition = 'AND MONTH(ViolationDate) = MONTH(CURDATE()) AND YEAR(ViolationDate) = YEAR(CURDATE())';
            break;
        case 'yearly':
            $dateCondition = 'AND YEAR(ViolationDate) = YEAR(CURDATE())';
            break;
    }

    $sql = "
        SELECT
            COUNT(DISTINCT CASE WHEN ViolationType LIKE '%WithoutUniform%' THEN StudentID END) AS total_without_uniform,
            COUNT(DISTINCT CASE WHEN ViolationType LIKE '%WithoutID%' THEN StudentID END) AS total_without_id
        FROM StudentArchive
        WHERE Violated = 1 $dateCondition
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $totals = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'message' => 'Totals fetched successfully.',
            'period' => $period,
            'data' => [
                'total_without_uniform' => (int) $totals['total_without_uniform'],
                'total_without_id' => (int) $totals['total_without_id']
            ]
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}
?>