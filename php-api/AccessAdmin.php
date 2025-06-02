<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = json_decode(file_get_contents('php://input'), true);
	$response = array();
	$isAdmin = isset($data['isAdmin']) ? $data['isAdmin'] : false;
	$password = isset($data['password']) ? $data['password'] : '';
	$passwordTwo = isset($data['passwordTwo']) ? $data['passwordTwo'] : '';

	if($password === "admin123") {
		$_SESSION['isAdmin'] = true;
		$response = ['status' => 'success', 'message' => 'Successfully admin!'];
	} else if($passwordTwo == "admin321") {
		$_SESSION['isAdmin'] = true;
		$response = ['status' => 'success', 'message' => 'Successfully admin!'];
	} else {
		$response = ['status' => 'failed', 'message' => 'Failed to be admin!'];
	}
	
	header('Content-Type: application/json');
	echo json_encode($response);
}
?>