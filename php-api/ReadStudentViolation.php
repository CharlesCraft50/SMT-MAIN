<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $response = array();

    // Sanitize the student ID from the GET request
    $studentID = isset($_GET['StudentID']) ? filter_var($_GET['StudentID'], FILTER_SANITIZE_NUMBER_INT) : '';

    if (!$studentID) {
        $response = [
            'status' => 'error',
            'message' => 'Student ID is required.'
        ];
        echo json_encode($response);
        exit();
    }

    // Updated SQL with LEFT JOIN on DailyRecords
    $sql = 'SELECT 
                s.StudentID, 
                s.StudentName, 
                s.YearLevel, 
                s.ProgramID,
                p.ProgramName, 
                p.ProgramCode,
                d.RecordID,
                d.ViolationType, 
                d.ViolationDate, 
                d.Attendance,
                d.Violated,
                d.Notes, 
                d.ViolationPicture, 
                d.ViolationStatus
            FROM Students s
            LEFT JOIN DailyRecords d ON s.StudentID = d.StudentID
            LEFT JOIN Program p ON s.ProgramID = p.ProgramID
            WHERE s.StudentID = :StudentID
            ORDER BY d.ViolationDate DESC';

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':StudentID', $studentID, PDO::PARAM_INT);
        $stmt->execute();

        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($records) > 0) {
            $studentInfo = $records[0]; // Always safe now

            // Check if there are any actual violations
            $hasViolations = array_filter($records, fn($r) => !empty($r['RecordID']));

            if ($hasViolations) {
                $violations = [];

                foreach ($hasViolations as $record) {
                    $violations[] = [
                        'RecordID' => $record['RecordID'],
                        'ViolationType' => $record['ViolationType'],
                        'ViolationDate' => $record['ViolationDate'],
                        'Notes' => $record['Notes'],
                        'ViolationPicture' => $record['ViolationPicture'],
                        'ViolationStatus' => $record['ViolationStatus'],
                        'Violated' => $record['Violated']
                    ];
                }

                $response = [
                    'status' => 'success',
                    'message' => 'Data fetched successfully.',
                    'student' => [
                        'StudentID' => $studentInfo['StudentID'],
                        'StudentName' => $studentInfo['StudentName'],
                        'YearLevel' => $studentInfo['YearLevel'],
                        'ProgramID' => $studentInfo['ProgramID'],
                        'ProgramName' => $studentInfo['ProgramName'],
                        'ProgramCode' => $studentInfo['ProgramCode']
                    ],
                    'violations' => $violations
                ];
            } else {
                // No violations, but student exists
                $response = [
                    'status' => 'empty',
                    'message' => 'No violations found for this student.',
                    'student' => [
                        'StudentID' => $studentInfo['StudentID'],
                        'StudentName' => $studentInfo['StudentName'],
                        'YearLevel' => $studentInfo['YearLevel'],
                        'ProgramID' => $studentInfo['ProgramID'],
                        'ProgramName' => $studentInfo['ProgramName'],
                        'ProgramCode' => $studentInfo['ProgramCode']
                    ],
                    'violations' => []
                ];
            }
        } else {
            // Student not found
            $response = [
                'status' => 'error',
                'message' => 'No student found with that ID.'
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
