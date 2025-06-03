<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');  // your PDO connection in $conn

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $period = $_GET['period'] ?? 'all';
    $dateCondition = '';

    // Build date filtering condition for ArchivedViolations.ViolationDate
    switch ($period) {
        case 'daily':
            $dateCondition = "AND DATE(ArchivedViolations.ViolationDate) = CURDATE()";
            break;
        case 'monthly':
            $dateCondition = "AND MONTH(ArchivedViolations.ViolationDate) = MONTH(CURDATE()) AND YEAR(ArchivedViolations.ViolationDate) = YEAR(CURDATE())";
            break;
        case 'yearly':
            $dateCondition = "AND YEAR(ArchivedViolations.ViolationDate) = YEAR(CURDATE())";
            break;
        case 'all':
        default:
            $dateCondition = '';  // no filter on date
            break;
    }

    // Query to find top program with most archived violations
    $sql = "
        SELECT 
            Program.ProgramName, 
            Program.ProgramCode, 
            COUNT(*) AS ViolationCount
        FROM ArchivedViolations
        JOIN StudentArchive ON ArchivedViolations.StudentID = StudentArchive.StudentID
        JOIN Students ON StudentArchive.StudentID = Students.StudentID
        JOIN Program ON Students.ProgramID = Program.ProgramID
        WHERE ArchivedViolations.Violated = 1
        $dateCondition
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
                'message' => 'Top program with most archived violations fetched.',
                'period' => $period,
                'data' => $topProgram
            ]);
        } else {
            echo json_encode([
                'status' => 'failed',
                'period' => $period,
                'message' => 'No archived violations found.'
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
