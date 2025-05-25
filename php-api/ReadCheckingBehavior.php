<?php
    session_start();

    // Check if user is an admin
    if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
        echo json_encode([
            'status' => 'error',
            'code' => 403,
            'message' => 'Unauthorized access.'
        ]);
        exit();
    }

    require('connect.php');

    $response = [];

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $stmt = $conn->prepare("SELECT turnOn FROM CheckingBehavior WHERE id = 1 LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $response = [
                    'status' => 'success',
                    'code' => 200,
                    'turnOn' => (bool)$result['turnOn']
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Record not found.'
                ];
            }
        } catch (PDOException $e) {
            $response = [
                'status' => 'error',
                'code' => 500,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'code' => 405,
            'message' => 'Invalid request method. Only GET is allowed for this endpoint.'
        ];
    }

    echo json_encode($response);
?>
