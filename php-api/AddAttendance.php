<?php
    session_start();

    if (!isset($_SESSION['student'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'No student session found.']);
        exit();
    }

    require('connect.php');

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'])) {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'level' => 'danger', 'message' => 'Invalid request method or missing file.']);
        exit();
    }

    $student = $_SESSION['student'];
    $studentID = $student['StudentID'];
    $studentName = str_replace(' ', '_', $student['StudentName']);
    $uniformStatus = filter_input(INPUT_POST, 'uniformStatus', FILTER_VALIDATE_INT);
    $idViolation = !empty($_SESSION['student']['idViolation']) 
        ? ($_SESSION['student']['idViolation'] ? 'WithoutID' : null) 
        : null;
    $hasIdViolation = false;

    if ($uniformStatus === null || $uniformStatus === false) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing uniform status.']);
        exit();
    }

    $file = $_FILES['file'];
    $violationDate = date('Y-m-d');
    $fileName = "{$studentID}_{$violationDate}.jpg";
    $baseFolder = __DIR__ . '/content';
    $folder = $baseFolder . ($uniformStatus === 1 ? '/uniform/' : '/not_wearing_uniform/') . $studentName;
    $dbFilePath = "content" . ($uniformStatus === 1 ? '/uniform/' : '/not_wearing_uniform/') . $studentName . '/' . $fileName;

    // Create directory if not exists
    if (!is_dir($folder) && !mkdir($folder, 0777, true)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => "Failed to create folder: $folder"]);
        exit();
    }

    $filePath = "$folder/$fileName";
    if (file_exists($filePath)) unlink($filePath);

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'level' => 'danger', 'message' => 'Failed to upload image.']);
        exit();
    }

    try {
        // Check for existing record today
        $stmt = $conn->prepare("SELECT RecordID, Attendance, TimeIn, TimeOut FROM DailyRecords 
                                WHERE StudentID = :StudentID AND ViolationDate = :ViolationDate LIMIT 1");
        $stmt->execute([':StudentID' => $studentID, ':ViolationDate' => $violationDate]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            if ($record['Attendance'] == 1 && empty($record['TimeOut'])) {
                $minutesElapsed = (time() - strtotime($record['TimeIn'])) / 60;

                if ($minutesElapsed >= 1) {
                    $timeOut = date('Y-m-d H:i:s');
                    $update = $conn->prepare("UPDATE DailyRecords 
                                            SET TimeOut = :TimeOut, ViolationStatus = 'Timed Out' 
                                            WHERE RecordID = :RecordID");
                    $update->execute([':TimeOut' => $timeOut, ':RecordID' => $record['RecordID']]);

                    echo json_encode([
                        'status' => 'timeout',
                        'level' => 'warning',
                        'message' => '⏰ Timed Out!',
                        'timeOut' => $timeOut
                    ]);
                    exit();
                }
            }

            echo json_encode([
                'status' => 'duplicate',
                'message' => '🚫 Attended already!',
                'imageSaved' => true,
                'filePath' => $dbFilePath
            ]);
            exit();
        }

        // Insert new violation record
        $violationType = $uniformStatus === 1 ? '' : 'WithoutUniform';
        $data = [
            ':StudentID' => $studentID,
            ':ViolationType' => $violationType,
            ':ViolationDate' => $violationDate,
            ':Attendance' => 1,
            ':TimeIn' => date('Y-m-d H:i:s'),
            ':Violated' => $uniformStatus === 1 ? 0 : 1,
            ':Notes' => '',
            ':ViolationPicture' => $dbFilePath,
            ':ViolationStatus' => 'Pending'
        ];

        $insert = $conn->prepare("INSERT INTO DailyRecords (
            StudentID, ViolationType, ViolationDate, Attendance, TimeIn,
            Violated, Notes, ViolationPicture, ViolationStatus
        ) VALUES (
            :StudentID, :ViolationType, :ViolationDate, :Attendance, :TimeIn,
            :Violated, :Notes, :ViolationPicture, :ViolationStatus
        )");


        if ($insert->execute($data)) {
            // Main record inserted successfully
            $hasIdViolation = false;

            if ($idViolation) {
                $idViolationData = [
                    ':StudentID' => $studentID,
                    ':ViolationType' => 'WithoutID',
                    ':ViolationDate' => $violationDate,
                    ':Attendance' => 1,
                    ':TimeIn' => date('Y-m-d H:i:s'),
                    ':Violated' => 1,
                    ':Notes' => '',
                    ':ViolationPicture' => $dbFilePath,
                    ':ViolationStatus' => 'Pending'
                ];

                $insertID = $conn->prepare("INSERT INTO DailyRecords (
                    StudentID, ViolationType, ViolationDate, Attendance, TimeIn,
                    Violated, Notes, ViolationPicture, ViolationStatus
                ) VALUES (
                    :StudentID, :ViolationType, :ViolationDate, :Attendance, :TimeIn,
                    :Violated, :Notes, :ViolationPicture, :ViolationStatus
                )");

                if ($insertID->execute($idViolationData)) {
                    $hasIdViolation = true;
                }
            }

            $violationMsg = $uniformStatus === 1
                ? ($hasIdViolation ? '✅ Wearing Uniform but 🚫 No ID' : '✅ Wearing Uniform!')
                : '🚫 Not Wearing Uniform' . ($hasIdViolation ? ' and No ID' : '');

            echo json_encode([
                'status' => 'success',
                'level' => ($uniformStatus === 1 && !$hasIdViolation) ? 'success' : 'danger',
                'message' => $violationMsg,
                'recordID' => $conn->lastInsertId()
            ]);
        } else {
            echo json_encode(['status' => 'error', 'level' => 'danger', 'message' => 'Failed to insert uniform record.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'level' => 'danger', 'message' => 'Database error: ' . $e->getMessage()]);
    }
?>
