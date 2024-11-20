<?php
session_start();

include 'config.php';

// Check if the user is logged in and has the role of a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Check if the course_id parameter is set
if (!isset($_GET['course_id'])) {
    echo "Course ID is required.";
    exit();
}

$course_id = $_GET['course_id'];

try {
    // Start a transaction
    $pdo->beginTransaction();

    // Delete attendance records related to sessions in the course
    $deleteAttendance = "DELETE FROM attendance WHERE session_id IN (SELECT id FROM sessions WHERE course_id = :course_id)";
    $stmt = $pdo->prepare($deleteAttendance);
    $stmt->execute(['course_id' => $course_id]);

    // Delete sessions related to the course
    $deleteSessions = "DELETE FROM sessions WHERE course_id = :course_id";
    $stmt = $pdo->prepare($deleteSessions);
    $stmt->execute(['course_id' => $course_id]);

    // Delete enrollments related to the course
    $deleteEnrollments = "DELETE FROM enrollments WHERE course_id = :course_id";
    $stmt = $pdo->prepare($deleteEnrollments);
    $stmt->execute(['course_id' => $course_id]);

    // Finally, delete the course
    $deleteCourse = "DELETE FROM courses WHERE id = :course_id";
    $stmt = $pdo->prepare($deleteCourse);
    $stmt->execute(['course_id' => $course_id]);

    // Commit the transaction
    $pdo->commit();

    // Redirect to the teacher dashboard with a success message
    echo "<script>
            alert('Lớp học đã được xoá thành công!');
            window.location.href = 'teacher_dashboard.php';
          </script>";
} catch (PDOException $e) {
    // Roll back the transaction if something failed
    $pdo->rollBack();
    echo "Error deleting course: " . $e->getMessage();
}
?>
