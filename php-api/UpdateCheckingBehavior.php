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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the JSON input data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate the input fields
        $turnOn = $data['turnOn'];

        if (!isset($data['turnOn'])) {
            $response = [
                'status' => 'error',
                'code' => 400,
                'message' => 'turnOn is required.'
            ];
        } else {
            try {
                $turnOn = $data['turnOn'] ? 1 : 0;
                
                $stmt = $conn->prepare("UPDATE CheckingBehavior SET turnOn = :turnOn WHERE id = 1");
                $stmt->bindParam(':turnOn', $turnOn, PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $response = [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Checking Behavior updated successfully.'
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'code' => 200, // No DB error, but nothing changed
                        'message' => 'Checking Behavior updated Failed'
                    ];
                }
            } catch (PDOException $e) {
                $response = [
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'Database error: ' . $e->getMessage()
                ];
            }
        }
    } else {
        $response = [
            'status' => 'error',
            'code' => 405,
            'message' => 'Invalid request method. Only POST method is allowed.'
        ];
    }

    // Output response as JSON
    echo json_encode($response);
?>
