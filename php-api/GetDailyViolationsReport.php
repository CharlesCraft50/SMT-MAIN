<?php
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
            COUNT(dr.RecordID) AS violation_count
        FROM DailyRecords dr
        INNER JOIN Students s ON dr.StudentID = s.StudentID
        INNER JOIN Program p ON s.ProgramID = p.ProgramID
        WHERE dr.Violated = 1 
          AND dr.ViolationDate = :today
        GROUP BY p.ProgramName
        ORDER BY p.ProgramName ASC
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':today', $today);
    $stmt->execute();

    $violations_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Cast violation_count as int
    foreach ($violations_data as &$row) {
        $row['violation_count'] = (int)$row['violation_count'];
    }

    echo json_encode($violations_data);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => 'Failed to fetch violations data: ' . $e->getMessage()
    ]);
}
?>
