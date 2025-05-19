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
            $dateCondition = 'AND DATE(DailyRecords.ViolationDate) = CURDATE()';
            break;
        case 'monthly':
            $dateCondition = 'AND MONTH(DailyRecords.ViolationDate) = MONTH(CURDATE()) AND YEAR(DailyRecords.ViolationDate) = YEAR(CURDATE())';
            break;
        case 'yearly':
            $dateCondition = 'AND YEAR(DailyRecords.ViolationDate) = YEAR(CURDATE())';
            break;
    }

    $sql = "
        SELECT
            Program.ProgramName,
            Program.ProgramCode,
            COUNT(*) AS ViolationCount
        FROM DailyRecords
        JOIN Students ON DailyRecords.StudentID = Students.StudentID
        JOIN Program ON Students.ProgramID = Program.ProgramID
        WHERE DailyRecords.Violated = 1 $dateCondition
        GROUP BY Program.ProgramID
        ORDER BY ViolationCount DESC
        LIMIT 1
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $topProgram = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($topProgram) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Top program with most violations fetched.',
                'period' => $period,
                'data' => $topProgram
            ]);
        } else {
            echo json_encode([
                'status' => 'failed',
                'period' => $period,
                'message' => 'No violations found.'
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}
?>
