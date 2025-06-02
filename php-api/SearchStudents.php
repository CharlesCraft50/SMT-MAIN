<?php
require('connect.php');

$search = $_GET['search'] ?? '';

try {
    $sql = 'SELECT 
                Students.StudentID, 
                Students.StudentName, 
                Program.ProgramName, 
                Program.ProgramCode, 
                Students.YearLevel
            FROM Students 
            JOIN Program ON Students.ProgramID = Program.ProgramID
            WHERE 
                Students.StudentID LIKE :search 
                OR Students.StudentName LIKE :search 
                OR Program.ProgramName LIKE :search 
                OR Program.ProgramCode LIKE :search 
            LIMIT 10';

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':search', '%' . $search . '%');
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['students' => $students]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
