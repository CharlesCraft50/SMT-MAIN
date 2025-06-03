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

        if (!$typesParam) {
            echo json_encode(['status' => 'error', 'message' => 'No violation types specified.']);
            exit();
        }

        $violationTypes = array_map('trim', explode(',', $typesParam));
        $responseData = [];

        foreach ($violationTypes as $violationType) {
            // Build date filter for archive
            $dateFilter = '';
            $params = [':violationType' => $violationType];

            // For archive, period filtering is by ArchiveYear and ArchiveMonth
            // Daily period won't be supported on archive (you could ignore or return empty)
            if ($period === 'monthly') {
                $dateFilter = 'AND ArchiveYear = YEAR(CURDATE()) AND ArchiveMonth = MONTH(CURDATE())';
            } elseif ($period === 'yearly') {
                $dateFilter = 'AND ArchiveYear = YEAR(CURDATE())';
            } elseif ($period === 'daily') {
                // Daily period not supported on archive; return empty or skip filtering
                // Let's skip data in this case (empty result)
                $responseData[$violationType] = [];
                continue;
            }

            $sql = "
                SELECT 
                    p.ProgramCode,
                    p.ProgramName,
                    p.ProgramCategory,
                    p.Department,
                    COALESCE(SUM(sa.TotalViolations), 0) AS TotalViolations
                FROM Program p
                LEFT JOIN Students s ON p.ProgramID = s.ProgramID
                LEFT JOIN StudentArchive sa ON s.StudentID = sa.StudentID
                LEFT JOIN ArchivedViolations av ON av.StudentID = sa.StudentID
                    AND av.ViolationType = :violationType
                WHERE 1=1
                $dateFilter
                GROUP BY p.ProgramCode, p.ProgramName, p.ProgramCategory, p.Department
                ORDER BY p.ProgramCategory, p.Department, p.ProgramName
            ";

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
                'message' => 'Programs and violations fetched successfully (archived).',
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
