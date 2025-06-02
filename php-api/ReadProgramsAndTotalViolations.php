<?php
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

require('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $typesParam = isset($_GET['type']) ? $_GET['type'] : null;
        $period = isset($_GET['period']) ? $_GET['period'] : null;

        // If no type specified, return error or set defaults
        if (!$typesParam) {
            echo json_encode(['status' => 'error', 'message' => 'No violation types specified.']);
            exit();
        }

        // Support multiple types separated by comma
        $violationTypes = array_map('trim', explode(',', $typesParam));

        $responseData = [];

        foreach ($violationTypes as $violationType) {
            $sql = "
                SELECT 
                    p.ProgramCode,
                    p.ProgramName,
                    p.ProgramCategory,
                    p.Department,
                    COUNT(dr.RecordID) AS TotalViolations
                FROM Program p
                LEFT JOIN Students s ON p.ProgramID = s.ProgramID
                LEFT JOIN DailyRecords dr ON s.StudentID = dr.StudentID
                    AND dr.Violated = 1
                    AND dr.ViolationType = :violationType
            ";

            $filters = [];
            $params = [':violationType' => $violationType];

            if ($period === 'daily') {
                $filters[] = "DATE(dr.ViolationDate) = CURDATE()";
            } elseif ($period === 'monthly') {
                $filters[] = "MONTH(dr.ViolationDate) = MONTH(CURDATE()) AND YEAR(dr.ViolationDate) = YEAR(CURDATE())";
            } elseif ($period === 'yearly') {
                $filters[] = "YEAR(dr.ViolationDate) = YEAR(CURDATE())";
            }

            if (!empty($filters)) {
                $sql .= " AND " . implode(" AND ", $filters);
            }

            $sql .= " GROUP BY p.ProgramCode, p.ProgramName, p.ProgramCategory, p.Department
                      ORDER BY p.ProgramCategory, p.Department, p.ProgramName";

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

            $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $programsData = [];
            foreach ($programs as $program) {
                $programCode = $program['ProgramCode'];
                $programsData[$programCode] = [
                    'ProgramName' => $program['ProgramName'],
                    'ProgramCategory' => $program['ProgramCategory'],
                    'Department' => $program['Department'],
                    'TotalViolations' => (int)$program['TotalViolations']
                ];
            }

            $responseData[$violationType] = $programsData;
        }

        if (!empty($responseData)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Programs and violations fetched successfully.',
                'data' => $responseData
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No data found.'
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
