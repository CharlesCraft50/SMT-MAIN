<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Use POST.']);
    exit();
}

try {
    $currentYear = date('Y');
    $currentMonth = date('n');

    // Copy matching records from DailyRecords to StudentArchive
    $insertSql = "
        INSERT INTO StudentArchive (
            StudentID, ViolationDate, Attendance, TimeIn, TimeOut,
            Violated, ViolationType, Notes, ViolationPicture, ViolationStatus
        )
        SELECT 
            StudentID, ViolationDate, Attendance, TimeIn, TimeOut,
            Violated, ViolationType, Notes, ViolationPicture, ViolationStatus
        FROM DailyRecords
        WHERE YEAR(ViolationDate) = :year AND MONTH(ViolationDate) = :month
    ";
    $stmtInsert = $conn->prepare($insertSql);
    $stmtInsert->execute([':year' => $currentYear, ':month' => $currentMonth]);

    // Delete those same records from DailyRecords
    $deleteSql = "
        DELETE FROM DailyRecords
        WHERE YEAR(ViolationDate) = :year AND MONTH(ViolationDate) = :month
    ";
    $stmtDelete = $conn->prepare($deleteSql);
    $stmtDelete->execute([':year' => $currentYear, ':month' => $currentMonth]);

    $archivedCount = $stmtInsert->rowCount();

    echo json_encode([
        'status' => 'success',
        'message' => "Archived and cleaned up $archivedCount records for $currentMonth/$currentYear."
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
