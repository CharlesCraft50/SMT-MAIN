<?php
session_start();
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = [];

    // Sanitize the StudentID input
    $studentID = isset($_GET['StudentID']) ? filter_var($_GET['StudentID'], FILTER_SANITIZE_STRING) : '';

    if (!$studentID) {
        $response = [
            'status' => 'error',
            'message' => 'Student ID is required.'
        ];
        echo json_encode($response);
        exit();
    }

    // SQL query using your actual table names and structure
    $sql = "SELECT 
                s.StudentID,
                s.StudentName,
                s.YearLevel AS Year,
                s.ProgramID,
                p.ProgramName,
                p.ProgramCode,
                dr.RecordID AS ViolationID,
                dr.ViolationType,
                dr.ViolationDate,
                dr.Notes,
                dr.ViolationStatus
            FROM Students s
            LEFT JOIN DailyRecords dr ON s.StudentID = dr.StudentID AND dr.Violated = 1
            LEFT JOIN Program p ON s.ProgramID = p.ProgramID
            WHERE s.StudentID = :StudentID
            ORDER BY dr.ViolationDate DESC";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':StudentID', $studentID, PDO::PARAM_STR);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($results && count($results) > 0) {
            // Extract student info from first row
            $studentInfo = [
                'StudentID' => $results[0]['StudentID'],
                'StudentName' => $results[0]['StudentName'],
                'Year' => $results[0]['Year'],
                'ProgramID' => $results[0]['ProgramID'],
                'ProgramName' => $results[0]['ProgramName'],
                'ProgramCode' => $results[0]['ProgramCode']
            ];

            $violations = [];

            foreach ($results as $row) {
                if ($row['ViolationID']) {
                    $violations[] = [
                        'ViolationID' => $row['ViolationID'],
                        'ViolationType' => $row['ViolationType'],
                        'ViolationDate' => $row['ViolationDate'],
                        'Notes' => $row['Notes'],
                        'ViolationStatus' => $row['ViolationStatus']
                    ];
                }
            }

            $response = [
                'status' => 'success',
                'message' => 'Student and violation data fetched.',
                'student' => $studentInfo,
                'violations' => $violations
            ];
        } else {
            // Student may exist but no violations
            // Re-query student without join to get basic info
            $stmt = $conn->prepare("SELECT s.StudentID, s.StudentName, s.YearLevel AS Year, s.ProgramID, p.ProgramName, p.ProgramCode
                                    FROM Students s
                                    LEFT JOIN Program p ON s.ProgramID = p.ProgramID
                                    WHERE s.StudentID = :StudentID");
            $stmt->bindParam(':StudentID', $studentID, PDO::PARAM_STR);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student) {
                $response = [
                    'status' => 'success',
                    'message' => 'Student found but no violations.',
                    'student' => $student,
                    'violations' => []
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Student not found.'
                ];
            }
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
