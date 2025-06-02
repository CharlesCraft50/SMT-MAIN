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
        // Updated query to include ProgramCategory
        $sql = 'SELECT ProgramID, ProgramName, ProgramCode, ProgramCategory, Department FROM Program ORDER BY ProgramCategory, Department, ProgramName';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $programs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedPrograms = [];
        foreach ($programs as $program) {
            $category = $program['ProgramCategory'] ?: 'Uncategorized'; // Use 'Uncategorized' if ProgramCategory is empty
            if (!isset($groupedPrograms[$category])) {
                $groupedPrograms[$category] = [];
            }
            $groupedPrograms[$category][] = [
                'ProgramID' => $program['ProgramID'],
                'ProgramName' => $program['ProgramName'],
                'ProgramCode' => $program['ProgramCode'],
                'Department' => $program['Department']
            ];
        }

        if (!empty($groupedPrograms)) {
            $response = [
                'status' => 'success',
                'message' => 'Programs fetched successfully.',
                'data' => $groupedPrograms
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No programs found.'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
