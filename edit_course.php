<?php
require 'config.php';

session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $course_desc = $_POST['course_desc'];
    $teacher_id = $_SESSION['user_id'];

    // Prepare the SQL statement
    $sql = "UPDATE courses SET course_name = :course_name, descriptions = :course_desc WHERE id = :course_id AND teacher_id = :teacher_id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'course_name' => $course_name,
            'course_desc' => $course_desc,
            'course_id' => $course_id,
            'teacher_id' => $teacher_id
        ]);

        // Redirect to the course management page after successful update
        echo "<script>
                alert('Thông tin lớp học đã được cập nhật thành công!');
                window.location.href = 'teacher_dashboard.php?page=manage-courses';
              </script>";
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
