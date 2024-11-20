<?php
// update_student.php
require 'config.php';
header("Content-Type: application/json");

try {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['id'], $data['name'], $data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit();
    }

    // Update the student information
    $stmt = $pdo->prepare("UPDATE users SET full_name = :name, email = :email WHERE id = :id");
    $stmt->execute([
        'id' => $data['id'],
        'name' => $data['name'],
        'email' => $data['email']
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
