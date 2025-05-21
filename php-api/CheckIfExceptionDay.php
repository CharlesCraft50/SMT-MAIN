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
    $idViolation = !empty($_SESSION['student']['idViolation']) 
            ? ($_SESSION['student']['idViolation'] ? 'WithoutID' : null) 
            : null;

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

        if ($record) {
            if (!empty($record['TimeOut'])) {
                return 'attended_and_timed_out';
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
                        'timeOut' => $timeOutStr
                    ];
                }

                return 'attended_recently';
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
                ];
            }
        }

        return 'not_attended';
    }

    // Run logic
    $exceptionDay = isExceptionDay($conn);
    $attendanceResult = checkAndUpdateAttendance($conn, $studentID, $exceptionDay, $idViolation);

    // Compose response
    $response = [
        'status' => 'success',
        'message' => 'Day check complete.',
        'exceptionDay' => $exceptionDay,
        'idViolation' => $idViolation
    ];

    if (is_array($attendanceResult)) {
        $response['attendanceStatus'] = $attendanceResult['status'];
        if (isset($attendanceResult['timeOut'])) {
            $response['timeOut'] = $attendanceResult['timeOut'];
        }
        if (isset($attendanceResult['timeIn'])) {
            $response['timeIn'] = $attendanceResult['timeIn'];
        }
    } else {
        $response['attendanceStatus'] = $attendanceResult;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
?>
