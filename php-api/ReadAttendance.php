<?php
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $studentID = $_GET['studentID']; // Expecting a 'studentID' parameter in the GET request

  try {
    // Prepare the SELECT statement
    $sql = "SELECT * FROM attendancerecord WHERE studentID = :studentID";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':studentID' => $studentID]);

    // Fetch all matching records
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($records) {
      // Send the records as JSON
      echo json_encode(['status' => 'success', 'data' => $records]);
    } else {
      // No records found
      echo json_encode(['status' => 'error', 'message' => 'No records found for the provided student ID.']);
    }
  } catch (PDOException $e) {
    // Return an error message if the query fails
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
  }
}
?>
