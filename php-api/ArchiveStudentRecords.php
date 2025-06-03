<?php
session_start();

// Check if the user is an admin
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
    $currentMonth = date('n'); // No leading zero

    // Get all students with daily records this month
    $query = "
        SELECT 
            dr.StudentID,
            SUM(CASE WHEN dr.Attendance = 1 THEN 1 ELSE 0 END) AS TotalAttendance,
            SUM(CASE WHEN dr.Violated = 1 THEN 1 ELSE 0 END) AS TotalViolations,
            SUM(CASE WHEN dr.Violated = 1 AND dr.ViolationStatus = 'Pending' THEN 1 ELSE 0 END) AS PendingViolations,
            SUM(CASE WHEN dr.Violated = 1 AND dr.ViolationStatus = 'Reviewed' THEN 1 ELSE 0 END) AS ReviewedViolations
        FROM DailyRecords dr
        WHERE YEAR(dr.ViolationDate) = :year AND MONTH(dr.ViolationDate) = :month
        GROUP BY dr.StudentID
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':year', $currentYear, PDO::PARAM_INT);
    $stmt->bindParam(':month', $currentMonth, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $archivedCount = 0;

    foreach ($students as $student) {
        $studentId = $student['StudentID'];

        // Check if already archived
        $checkSql = "SELECT COUNT(*) FROM StudentArchive WHERE StudentID = :StudentID AND ArchiveYear = :year AND ArchiveMonth = :month";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->execute([
            ':StudentID' => $studentId,
            ':year' => $currentYear,
            ':month' => $currentMonth
        ]);
        if ($checkStmt->fetchColumn() > 0) {
            continue; // Skip if already archived
        }

        // Insert into StudentArchive
        $insertSql = "
            INSERT INTO StudentArchive (
                StudentID, ArchiveYear, ArchiveMonth,
                TotalAttendance, TotalViolations,
                PendingViolations, ReviewedViolations
            ) VALUES (
                :StudentID, :year, :month,
                :TotalAttendance, :TotalViolations,
                :PendingViolations, :ReviewedViolations
            )
        ";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->execute([
            ':StudentID' => $studentId,
            ':year' => $currentYear,
            ':month' => $currentMonth,
            ':TotalAttendance' => $student['TotalAttendance'],
            ':TotalViolations' => $student['TotalViolations'],
            ':PendingViolations' => $student['PendingViolations'],
            ':ReviewedViolations' => $student['ReviewedViolations']
        ]);

        // Fetch and insert violations into ArchivedViolations
        $violationsStmt = $conn->prepare("
            SELECT ViolationType, ViolationDate, Violated
            FROM DailyRecords
            WHERE StudentID = :StudentID AND Violated = 1
              AND YEAR(ViolationDate) = :year AND MONTH(ViolationDate) = :month
        ");
        $violationsStmt->execute([
            ':StudentID' => $studentId,
            ':year' => $currentYear,
            ':month' => $currentMonth
        ]);

        $violations = $violationsStmt->fetchAll(PDO::FETCH_ASSOC);

        $archiveViolationStmt = $conn->prepare("
            INSERT INTO ArchivedViolations (StudentID, ViolationType, ViolationDate, Violated)
            VALUES (:StudentID, :ViolationType, :ViolationDate, :Violated)
        ");

        foreach ($violations as $violation) {
            $archiveViolationStmt->execute([
                ':StudentID' => $studentId,
                ':ViolationType' => $violation['ViolationType'],
                ':ViolationDate' => $violation['ViolationDate'],
                ':Violated' => $violation['Violated']
            ]);
        }

        $archivedCount++;

        $deleteStmt = $conn->prepare("
            DELETE FROM DailyRecords
            WHERE YEAR(ViolationDate) = :year AND MONTH(ViolationDate) = :month
        ");
        $deleteStmt->execute([
            ':year' => $currentYear,
            ':month' => $currentMonth
        ]);
    }

    echo json_encode([
        'status' => 'success',
        'message' => "Archived $archivedCount student record(s) for $currentMonth/$currentYear."
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
