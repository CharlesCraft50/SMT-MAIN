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

    // Check if today is an exception day
    function isExceptionDay($pdo) {
        $today = new DateTime();
        $todayStr = $today->format('Y-m-d');
        $weekday = $today->format('l');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ExceptionDays WHERE StartDate <= :today AND EndDate >= :today");
        $stmt->execute(['today' => $todayStr]);
        $dateException = $stmt->fetchColumn() > 0;

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ExceptionDays WHERE Weekday = :weekday");
        $stmt->execute(['weekday' => $weekday]);
        $weekdayException = $stmt->fetchColumn() > 0;

        return $dateException || $weekdayException;
    }

    // Check attendance and update timeout only
    function checkAndMaybeTimeOut($pdo, $studentID) {
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
                return ['status' => 'attended_and_timed_out'];
            }

            if (!empty($record['TimeIn'])) {
                $timeIn = strtotime($record['TimeIn']);
                $now = time();
                $minutesElapsed = ($now - $timeIn) / 60;

                if ($minutesElapsed >= 1) {
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

                    return ['status' => 'timeout_updated', 'timeOut' => $timeOutStr];
                }

                return ['status' => 'attended_recently'];
            }
        }

        return ['status' => 'not_attended'];
    }

    // Insert auto time-in for exception day
    function insertAutoTimeInIfNeeded($pdo, $studentID) {
        $now = new DateTime();
        $todayStr = $now->format('Y-m-d');
        $timeInStr = $now->format('Y-m-d H:i:s');

        // Insert new DailyRecord with auto time-in
        $stmt = $pdo->prepare("
            INSERT INTO DailyRecords (StudentID, ViolationDate, TimeIn, ViolationStatus)
            VALUES (:StudentID, :ViolationDate, :TimeIn, 'Auto Time-In')
        ");
        $stmt->execute([
            'StudentID' => $studentID,
            'ViolationDate' => $todayStr,
            'TimeIn' => $timeInStr
        ]);
    }

    // Main flow
    $exceptionDay = isExceptionDay($conn);
    $attendanceResult = checkAndMaybeTimeOut($conn, $studentID);

    // If exceptionDay and not yet attended, auto time-in
    if ($exceptionDay && $attendanceResult['status'] === 'not_attended') {
        insertAutoTimeInIfNeeded($conn, $studentID);
        $attendanceResult['status'] = 'auto_time_in';
    }

    // Compose response
    $response = [
        'status' => 'success',
        'message' => 'Attendance check complete.',
        'exceptionDay' => $exceptionDay,
        'attendanceStatus' => $attendanceResult['status']
    ];

    if (isset($attendanceResult['timeOut'])) {
        $response['timeOut'] = $attendanceResult['timeOut'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
?>
