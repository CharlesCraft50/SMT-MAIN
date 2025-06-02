<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access.'
    ]);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $studentID = isset($data['studentID']) ? trim($data['studentID']) : '';
    $studentName = isset($data['studentName']) ? trim($data['studentName']) : '';
    $year = isset($data['year']) ? trim($data['year']) : '';
    $programID = isset($data['programID']) ? trim($data['programID']) : '';
    $rfid = isset($data['rfid']) && trim($data['rfid']) !== '' ? trim($data['rfid']) : null;

    if (empty($studentID) || empty($studentName) || empty($year) || empty($programID)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'All required fields must be filled.'
        ]);
        exit();
    }

    try {
        // Check if StudentID already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Students WHERE StudentID = :studentID");
        $stmt->bindParam(':studentID', $studentID);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Student ID already exists.'
            ]);
            exit();
        }

        // Validate ProgramID
        $stmt = $conn->prepare("SELECT ProgramCode FROM Program WHERE ProgramID = :programID");
        $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
        $stmt->execute();

        if (!$stmt->fetchColumn()) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid Program ID.'
            ]);
            exit();
        }

        // Insert Student
        $stmt = $conn->prepare("
            INSERT INTO Students (StudentID, StudentName, YearLevel, ProgramID, RFID)
            VALUES (:studentID, :studentName, :year, :programID, :rfid)
        ");
        $stmt->bindParam(':studentID', $studentID);
        $stmt->bindParam(':studentName', $studentName);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':programID', $programID, PDO::PARAM_INT);
        $stmt->bindParam(':rfid', $rfid);

        $stmt->execute();

        echo json_encode([
            'status' => 'success',
            'message' => 'Student added successfully.'
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>