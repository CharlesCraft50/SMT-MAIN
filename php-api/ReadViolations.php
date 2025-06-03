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

    // Sanitize inputs
    $searchStudentID = isset($_GET['StudentID']) ? filter_var($_GET['StudentID'], FILTER_SANITIZE_STRING) : '';
    $searchStudentName = isset($_GET['StudentName']) ? filter_var($_GET['StudentName'], FILTER_SANITIZE_STRING) : '';
    $searchYear = isset($_GET['YearLevel']) ? filter_var($_GET['YearLevel'], FILTER_SANITIZE_STRING) : '';
    $searchProgramID = isset($_GET['ProgramID']) ? filter_var($_GET['ProgramID'], FILTER_SANITIZE_STRING) : '';
    $violationYear = isset($_GET['ViolationYear']) ? filter_var($_GET['ViolationYear'], FILTER_SANITIZE_STRING) : '';

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

    if ($searchYear) {
        $whereClauses[] = "Students.YearLevel LIKE :YearLevel";
        $params[':YearLevel'] = "%" . $searchYear . "%";
    }

    if ($searchProgramID) {
        $whereClauses[] = "Students.ProgramID LIKE :ProgramID";
        $params[':ProgramID'] = "%" . $searchProgramID . "%";
    }

    if ($violationYear) {
        $whereClauses[] = "YEAR(StudentArchive.ViolationDate) = :ViolationYear";
        $params[':ViolationYear'] = $violationYear;
    }

    // Construct SQL query
    $sql = 'SELECT
                Students.StudentID, 
                Students.StudentName, 
                Students.YearLevel, 
                Students.ProgramID, 
                Students.RFID,
                Program.ProgramName, 
                Program.ProgramCode, 
                Program.ProgramCategory,
                COUNT(CASE WHEN StudentArchive.Violated = 1 THEN 1 END) AS ViolationCount,
                SUM(CASE WHEN StudentArchive.ViolationType LIKE "%WithoutUniform%" THEN 1 ELSE 0 END) AS WithoutUniformCount,
                SUM(CASE WHEN StudentArchive.ViolationType LIKE "%WithoutID%" THEN 1 ELSE 0 END) AS WithoutIDCount,
                MAX(StudentArchive.ViolationDate) AS LatestViolationDate
            FROM Students
            LEFT JOIN StudentArchive ON Students.StudentID = StudentArchive.StudentID
            LEFT JOIN Program ON Students.ProgramID = Program.ProgramID';

    if (!empty($whereClauses)) {
        $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
    }

    $sql .= ' GROUP BY Students.StudentID
              ORDER BY LatestViolationDate DESC';

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
