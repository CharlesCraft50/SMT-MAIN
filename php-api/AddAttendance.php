<?php
session_start();

// Ensure the user is a logged-in student
if (!isset($_SESSION['student'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'No student session found.']);
    exit();
}

require('connect.php');

// Initialize response
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $studentID = $_SESSION['student']['StudentID'];
    $studentName = $_SESSION['student']['StudentName'];
    $uniformStatus = filter_input(INPUT_POST, 'uniformStatus', FILTER_VALIDATE_INT);

    if ($uniformStatus === null || $uniformStatus === false) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing uniform status.']);
        exit();
    }

    $file = $_FILES['file'];
    $violationDate = date('Y-m-d');

    // Unique file name
    $fileName = $studentID . '_' . $violationDate . '.jpg';
    $studentFolderName = str_replace(' ', '_', $studentName);

    // Set base and target folder paths
    $baseFolder = __DIR__ . '/content';
    if ($uniformStatus === 1) {
        $folder = $baseFolder . '/uniform/' . $studentFolderName;
        $dbFilePath = 'content/uniform/' . $studentFolderName . '/' . $fileName;
    } else {
        $folder = $baseFolder . '/not_wearing_uniform/' . $studentFolderName;
        $dbFilePath = 'content/not_wearing_uniform/' . $studentFolderName . '/' . $fileName;
    }

    // Ensure directory exists
    if (!file_exists($folder)) {
        if (!mkdir($folder, 0777, true) && !is_dir($folder)) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to create folder: ' . $folder]);
            exit();
        }
    }

    $filePath = $folder . '/' . $fileName;

    if (file_exists($filePath)) {
        unlink($filePath); // delete the old photo
    }

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        try {
            // Check if today's record already exists
            $checkSql = "SELECT COUNT(*) FROM DailyRecords WHERE StudentID = :StudentID AND ViolationDate = :ViolationDate";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':StudentID', $studentID, PDO::PARAM_INT);
            $checkStmt->bindParam(':ViolationDate', $violationDate, PDO::PARAM_STR);
            $checkStmt->execute();
            $recordExists = $checkStmt->fetchColumn();

            if ($recordExists) {
                // Return duplicate status but still allow image saving
                $response = [
                    'status' => 'duplicate',
                    'message' => '🚫 Attended already!',
                    'imageSaved' => true,
                    'filePath' => $dbFilePath
                ];
            } else {
                // Prepare violation info
                $violationType = $uniformStatus === 1 ? '' : 'WithoutUniform';
                $violateAttendance = 1;
                $violateViolated = ($uniformStatus === 1) ? 0 : 1;
                $violationStatus = 'Pending';
                $notes = '';

                // Insert record
                $sql = "INSERT INTO DailyRecords (
                            StudentID, ViolationType, ViolationDate, Attendance, 
                            Violated, Notes, ViolationPicture, ViolationStatus
                        ) VALUES (
                            :StudentID, :ViolationType, :ViolationDate, :Attendance, 
                            :Violated, :Notes, :ViolationPicture, :ViolationStatus
                        )";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':StudentID', $studentID, PDO::PARAM_INT);
                $stmt->bindParam(':ViolationType', $violationType, PDO::PARAM_STR);
                $stmt->bindParam(':ViolationDate', $violationDate, PDO::PARAM_STR);
                $stmt->bindParam(':Attendance', $violateAttendance, PDO::PARAM_INT);
                $stmt->bindParam(':Violated', $violateViolated, PDO::PARAM_INT);
                $stmt->bindParam(':Notes', $notes, PDO::PARAM_STR);
                $stmt->bindParam(':ViolationPicture', $dbFilePath, PDO::PARAM_STR);
                $stmt->bindParam(':ViolationStatus', $violationStatus, PDO::PARAM_STR);

                if ($stmt->execute()) {
                  $response = [
                        'status' => 'success',
                        'level' => 'success',
                        'message' => 'Photo saved and violation recorded.',
                        'recordID' => $conn->lastInsertId()
                    ];

                  if($uniformStatus == 1) {
                      $response['message'] = '✅ Wearing Uniform!';
                      $response['level'] = 'success';
                  } else {
                      $response['message'] = '🚫 Not Wearing Uniform';
                      $response['level'] = 'danger';
                  }
                    
                } else {
                    $response = ['status' => 'error', 'level' => 'danger', 'message' => 'Failed to insert record.'];
                }
            }
        } catch (PDOException $e) {
            http_response_code(500);
            $response = ['status' => 'error', 'level' => 'danger', 'message' => 'Database error: ' . $e->getMessage()];
        }
    } else {
        http_response_code(500);
        $response = ['status' => 'error', 'level' => 'danger', 'message' => 'Failed to upload image.'];
    }
} else {
    http_response_code(405);
    $response = ['status' => 'error', 'level' => 'danger', 'message' => 'Invalid request method or missing file.'];
}

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
