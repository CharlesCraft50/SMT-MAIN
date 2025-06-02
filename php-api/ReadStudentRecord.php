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

    // SQL query to fetch student and violations
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

        // Get total attendance (Attendance = 1)
        $attendanceStmt = $conn->prepare("SELECT COUNT(*) FROM DailyRecords WHERE StudentID = :StudentID AND Attendance = 1");
        $attendanceStmt->bindParam(':StudentID', $studentID, PDO::PARAM_STR);
        $attendanceStmt->execute();
        $totalAttendance = (int) $attendanceStmt->fetchColumn();

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
            $violationCount = 0;
            $totalPendingViolations = 0;

            foreach ($results as $row) {
                if ($row['ViolationStatus'] === 'Pending') {
                    $totalPendingViolations++;
                }

                if ($row['ViolationID']) {
                    $violationCount++;
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
                'violationCount' => $violationCount,
                'totalPendingViolations' => $totalPendingViolations,
                'totalAttendance' => $totalAttendance,
                'violations' => $violations
            ];
        } else {
            // Student may exist but no violations
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
                    'violationCount' => 0,
                    'totalPendingViolations' => 0,
                    'totalAttendance' => $totalAttendance,
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