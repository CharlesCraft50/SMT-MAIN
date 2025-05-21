<?php
session_start();
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $input = isset($data['studentID']) ? trim($data['studentID']) : ''; // This input can be either StudentID or RFID
    $idViolation = false;
    
    try {
        // Try finding student by StudentID first
        $checkStudentSQL = "SELECT * FROM Students WHERE StudentID = :input OR RFID = :input";
        $stmt = $conn->prepare($checkStudentSQL);
        $stmt->execute([':input' => $input]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $studentID = $student['StudentID'];
            $attendanceDate = date('Y-m-d');
            

            if($input == $studentID) {
                $idViolation = true;
            }

            // Check if attendance already exists
            $checkAttendanceSQL = "SELECT * FROM DailyRecords WHERE StudentID = :studentID AND DATE(ViolationDate) = :attendanceDate";
            $stmt = $conn->prepare($checkAttendanceSQL);
            $stmt->execute([':studentID' => $studentID, ':attendanceDate' => $attendanceDate]);
            $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

            // if ($attendance) {
            //     $_SESSION['student'] = $student;
            //     echo json_encode(['status' => 'success', 'message' => 'Already attended!', 'student' => $student]);
            //     exit();
            // } else {
            //     // Insert attendance
            //     $attendanceDateTime = date('Y-m-d H:i:s');
            //     $insertAttendanceSQL = "INSERT INTO DailyRecords (ViolationDate, StudentID, Attendance, Violated, ViolationStatus) 
            //                             VALUES (:attendanceDate, :studentID, :attendance, :violated, :violationStatus)";
            //     $stmt = $conn->prepare($insertAttendanceSQL);
            //     $stmt->execute([
            //         ':attendanceDate' => $attendanceDateTime,
            //         ':studentID' => $studentID,
            //         ':attendance' => 1,
            //         ':violated' => 0,
            //         ':violationStatus' => 'Pending'
            //     ]);
                $student['idViolation'] = $idViolation;

                $_SESSION['student'] = $student;
                echo json_encode(['status' => 'success', 'message' => 'Attendance recorded', 'student' => $student]);
            // }
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Student or RFID not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
