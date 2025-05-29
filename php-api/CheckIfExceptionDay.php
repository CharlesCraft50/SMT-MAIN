<?php
    session_start();
    require('connect.php');

    // Check student session
    if (!isset($_SESSION['student'])) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'No student session found.']);
        exit();
    }

    $studentID = $_SESSION['student']['StudentID'];
    $studentName = str_replace(' ', '_', $_SESSION['student']['StudentName']) . '-' . $_SESSION['student']['StudentID'];
    $idViolation = !empty($_SESSION['student']['idViolation']) 
            ? ($_SESSION['student']['idViolation'] ? 'WithoutID' : null) 
            : null;
    $isManualMode = false;

    function isExceptionDay($pdo) {
        $today = new DateTime();
        $todayStr = $today->format('Y-m-d');
        $weekday = $today->format('l');

        // Check date-based exceptions
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ExceptionDays WHERE StartDate <= :today AND EndDate >= :today");
        $stmt->execute(['today' => $todayStr]);
        $dateException = $stmt->fetchColumn() > 0;

        // Check recurring weekday-based exceptions
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ExceptionDays WHERE Weekday = :weekday");
        $stmt->execute(['weekday' => $weekday]);
        $weekdayException = $stmt->fetchColumn() > 0;

        return $dateException || $weekdayException;
    }

    function checkAndUpdateAttendance($pdo, $studentID, $isException, $idViolation) {
        $todayStr = (new DateTime())->format('Y-m-d');

        $stmt = $pdo->prepare("
            SELECT RecordID, TimeIn, TimeOut
            FROM DailyRecords 
            WHERE StudentID = :StudentID AND ViolationDate = :today 
            LIMIT 1
        ");
        $stmt->execute(['StudentID' => $studentID, 'today' => $todayStr]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        // ✅ Check CheckingBehavior first
        $checkingBehavior = $pdo->query("SELECT turnOn FROM CheckingBehavior WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
        $isManualMode = !$checkingBehavior || $checkingBehavior['turnOn'] == 0;

        if ($record) {
            if (!empty($record['TimeOut'])) {
                return ['status' => 'attended_and_timed_out', 'isManualMode' => $isManualMode, 'hasRecord' => true];
            }

            if (!empty($record['TimeIn'])) {
                $timeIn = strtotime($record['TimeIn']);
                $now = time();
                $minutesElapsed = ($now - $timeIn) / 60;

                if ($minutesElapsed >= 1) {
                    // Update TimeOut and set status to "Timed Out"
                    $timeOutStr = date('Y-m-d H:i:s');
                    $updateStmt = $pdo->prepare("
                        UPDATE DailyRecords 
                        SET TimeOut = :TimeOut, ViolationStatus = 'Timed Out' 
                        WHERE RecordID = :RecordID
                    ");
                    $updateStmt->execute([
                        'TimeOut' => $timeOutStr,
                        'RecordID' => $record['RecordID']
                    ]);

                    return [
                        'status' => 'timeout_updated',
                        'timeOut' => $timeOutStr,
                        'isManualMode' => $isManualMode,
                        'hasRecord' => true
                    ];
                }

                return ['status' => 'attended_recently', 'isManualMode' => $isManualMode, 'hasRecord' => true];
            }
        } else {
            // No record found — time in now if exception day
            if ($isException) {
                $timeInStr = date('Y-m-d H:i:s');
                $insertStmt = $pdo->prepare("
                    INSERT INTO DailyRecords (StudentID, ViolationDate, Attendance, TimeIn, ViolationType, ViolationStatus)
                    VALUES (:StudentID, :ViolationDate, '1', :TimeIn, :ViolationType, 'Pending')
                ");
                $insertStmt->execute([
                    'StudentID' => $studentID,
                    'ViolationDate' => date('Y-m-d'),
                    'TimeIn' => $timeInStr,
                    'ViolationType' => $idViolation
                ]);

                return [
                    'status' => 'auto_time_in',
                    'timeIn' => $timeInStr,
                    'isManualMode' => $isManualMode,
                    'hasRecord' => true
                ];
            }
        }

        return ['status' => 'not_attended', 'isManualMode' => $isManualMode, 'hasRecord' => false];
    }

    // Run logic
    $exceptionDay = isExceptionDay($conn);
    $attendanceResult = checkAndUpdateAttendance($conn, $studentID, $exceptionDay, $idViolation);

    // Extract isManualMode from result
    $isManualMode = $attendanceResult['isManualMode'];
    $hasExistingRecord = $attendanceResult['hasRecord'];

    // ✅ Only handle manual upload if:
    // 1. It's manual mode
    // 2. It's not an exception day
    // 3. Student doesn't already have a record for today
    if ($isManualMode && !$exceptionDay && !$hasExistingRecord && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $violationDate = date('Y-m-d');
        $fileName = "{$studentID}_{$violationDate}.jpg";
        $baseFolder = __DIR__ . '/content';
        
        $folder = $baseFolder . '/manual_uploads/' . $studentName;
        $dbFilePath = "content/manual_uploads/{$studentName}/{$fileName}";

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

        // ✅ Insert new record with ViolationType included
        $insert = $conn->prepare("INSERT INTO DailyRecords (
            StudentID, ViolationDate, Attendance, TimeIn, Notes, ViolationPicture, Violated, ViolationType, ViolationStatus
        ) VALUES (
            :StudentID, :ViolationDate, :Attendance, :TimeIn, :Notes, :ViolationPicture, :Violated, :ViolationType, :ViolationStatus
        )");

        $violatedWithoutId = $idViolation == 'WithoutID' ? 1 : 0;

        $insert->execute([
            ':StudentID' => $studentID,
            ':ViolationDate' => $violationDate,
            ':Attendance' => 1,
            ':TimeIn' => date('Y-m-d H:i:s'),
            ':Notes' => '',
            ':ViolationPicture' => $dbFilePath,
            ':ViolationType' => $idViolation, // ✅ Include ViolationType
            ':Violated' => $violatedWithoutId,
            ':ViolationStatus' => 'Pending'
        ]);

        // Update response to indicate manual upload was processed
        $attendanceResult['status'] = $violatedWithoutId == 1 ? 'manual_upload_completed_no_id' : 'manual_upload_completed';
        $attendanceResult['timeIn'] = date('Y-m-d H:i:s');
    }

    // Compose response
    $response = [
        'status' => 'success',
        'message' => 'Day check complete.',
        'exceptionDay' => $exceptionDay,
        'idViolation' => $idViolation,
        'isManualMode' => $isManualMode
    ];

    $response['attendanceStatus'] = $attendanceResult['status'];
    if (isset($attendanceResult['timeOut'])) {
        $response['timeOut'] = $attendanceResult['timeOut'];
    }
    if (isset($attendanceResult['timeIn'])) {
        $response['timeIn'] = $attendanceResult['timeIn'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
?>