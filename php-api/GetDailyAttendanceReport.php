<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'connect.php';

try {
    $today = date('Y-m-d');

    $query = "
        SELECT 
            p.ProgramName AS program,
            COUNT(DISTINCT dr.StudentID) AS attendance_count
        FROM DailyRecords dr
        INNER JOIN Students s ON dr.StudentID = s.StudentID
        INNER JOIN Program p ON s.ProgramID = p.ProgramID
        WHERE dr.Attendance = 1 
          AND dr.ViolationDate = :today
        GROUP BY p.ProgramName
        ORDER BY p.ProgramName ASC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':today', $today);
    $stmt->execute();

    $attendance_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($attendance_data as &$row) {
        $row['attendance_count'] = (int)$row['attendance_count'];
    }

    echo json_encode($attendance_data);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Failed to fetch attendance data: ' . $e->getMessage()
    ]);
}
?>
