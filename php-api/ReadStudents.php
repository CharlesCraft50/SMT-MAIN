<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = [];

    // Sanitize input filters
    $searchStudentID = isset($_GET['StudentID']) ? filter_var($_GET['StudentID'], FILTER_SANITIZE_STRING) : '';
    $searchStudentName = isset($_GET['StudentName']) ? filter_var($_GET['StudentName'], FILTER_SANITIZE_STRING) : '';
    $searchYearLevel = isset($_GET['YearLevel']) ? filter_var($_GET['YearLevel'], FILTER_SANITIZE_STRING) : '';
    $searchProgramID = isset($_GET['ProgramID']) ? filter_var($_GET['ProgramID'], FILTER_SANITIZE_STRING) : '';

    $whereClauses = [];
    $params = [];

    if ($searchStudentID) {
        $whereClauses[] = "Students.StudentID LIKE :StudentID";
        $params[':StudentID'] = "%" . $searchStudentID . "%";
    }

    if ($searchStudentName) {
        $whereClauses[] = "Students.StudentName LIKE :StudentName";
        $params[':StudentName'] = "%" . $searchStudentName . "%";
    }

    if ($searchYearLevel) {
        $whereClauses[] = "Students.YearLevel LIKE :YearLevel";
        $params[':YearLevel'] = "%" . $searchYearLevel . "%";
    }

    if ($searchProgramID) {
        $whereClauses[] = "Students.ProgramID = :ProgramID";
        $params[':ProgramID'] = $searchProgramID;
    }

    // Build SQL query
    $sql = "
        SELECT 
            Students.StudentID, 
            Students.StudentName, 
            Students.YearLevel, 
            Students.ProgramID, 
            Students.RFID,
            CASE 
                WHEN Students.RFID IS NOT NULL THEN 'Registered' 
                ELSE 'Not Registered' 
            END AS RFIDStatus,
            Program.ProgramName, 
            Program.ProgramCode,
            COALESCE(COUNT(DailyRecords.RecordID), 0) AS TotalViolations,
            SUM(CASE WHEN DailyRecords.ViolationType LIKE '%WithoutUniform%' THEN 1 ELSE 0 END) AS WithoutUniformCount,
            SUM(CASE WHEN DailyRecords.ViolationType LIKE '%WithoutID%' THEN 1 ELSE 0 END) AS WithoutIDCount
        FROM Students
        LEFT JOIN DailyRecords ON Students.StudentID = DailyRecords.StudentID AND DailyRecords.Violated = TRUE
        LEFT JOIN Program ON Students.ProgramID = Program.ProgramID
    ";

    if (count($whereClauses) > 0) {
        $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
    }

    $sql .= ' GROUP BY Students.StudentID';

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($students) {
            $response = [
                'status' => 'success',
                'message' => 'Data fetched successfully.',
                'data' => $students
            ];
        } else {
            $response = [
                'status' => 'failed',
                'message' => 'No records found.'
            ];
        }
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ];
        http_response_code(500);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
