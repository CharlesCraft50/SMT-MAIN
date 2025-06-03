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
    $params = [];

    // Archive tables do not support daily period
    if ($period === 'daily') {
        echo json_encode([
            'status' => 'success',
            'message' => 'Daily period not supported for archived data.',
            'period' => $period,
            'data' => [
                'total_without_uniform' => 0,
                'total_without_id' => 0
            ]
        ]);
        exit();
    } elseif ($period === 'monthly') {
        $dateCondition = 'AND sa.ArchiveYear = YEAR(CURDATE()) AND sa.ArchiveMonth = MONTH(CURDATE())';
    } elseif ($period === 'yearly') {
        $dateCondition = 'AND sa.ArchiveYear = YEAR(CURDATE())';
    }

    $sql = "
        SELECT
            COUNT(DISTINCT CASE WHEN av.ViolationType LIKE '%WithoutUniform%' THEN sa.StudentID END) AS total_without_uniform,
            COUNT(DISTINCT CASE WHEN av.ViolationType LIKE '%WithoutID%' THEN sa.StudentID END) AS total_without_id
        FROM StudentArchive sa
        JOIN ArchivedViolations av ON sa.StudentID = av.StudentID
        WHERE 1=1
        $dateCondition
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $totals = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'message' => 'Totals fetched successfully from archive.',
            'period' => $period,
            'data' => [
                'total_without_uniform' => (int) ($totals['total_without_uniform'] ?? 0),
                'total_without_id' => (int) ($totals['total_without_id'] ?? 0)
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
