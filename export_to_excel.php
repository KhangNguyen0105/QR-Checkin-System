<?php
session_start();
require 'config.php';

// Check if the user is logged in and has access to export this data
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

// Get course ID from the POST request
$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : null;
if (!$course_id) {
    echo "Course ID is required.";
    exit();
}

try {
    // Fetch course details
    $stmt = $pdo->prepare("SELECT course_name FROM courses WHERE id = :course_id");
    $stmt->execute(['course_id' => $course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$course) {
        echo "Course not found.";
        exit();
    }

    // Fetch enrolled students
    $stmt = $pdo->prepare("
        SELECT u.id, u.full_name AS name
        FROM users u
        INNER JOIN enrollments e ON u.id = e.student_id
        WHERE e.course_id = :course_id
    ");
    $stmt->execute(['course_id' => $course_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch sessions for the course
    $stmt = $pdo->prepare("SELECT id, session_date, session_begin FROM sessions WHERE course_id = :course_id ORDER BY session_date");
    $stmt->execute(['course_id' => $course_id]);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch attendance records
    $attendanceData = [];
    $stmt = $pdo->prepare("
        SELECT student_id, session_id, status
        FROM attendance
        WHERE session_id IN (SELECT id FROM sessions WHERE course_id = :course_id)
    ");
    $stmt->execute(['course_id' => $course_id]);
    $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organize attendance data by student and session for easier lookup
    foreach ($attendanceRecords as $record) {
        $attendanceData[$record['student_id']][$record['session_id']] = $record['status'];
    }

    // Clear output buffer to avoid any unwanted whitespace
    if (ob_get_level()) ob_end_clean();
    
    // Set headers for CSV download
    $fileName = $course['course_name'] . " - Chi tiết điểm danh.csv";
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    
    // Optional: Add UTF-8 BOM for Excel compatibility with special characters
    echo "\xEF\xBB\xBF";

    // Open PHP output stream as a file handle
    $output = fopen('php://output', 'w');

    // Write CSV headers
    $headers = ['Họ và tên'];
    foreach ($sessions as $session) {
        $headers[] = $session['session_date'] . ' ' . $session['session_begin'];
    }
    fputcsv($output, $headers);

    // Write attendance data for each student
    foreach ($students as $student) {
        $row = [$student['name']];
        foreach ($sessions as $session) {
            $status = isset($attendanceData[$student['id']][$session['id']]) 
                      ? $attendanceData[$student['id']][$session['id']]
                      : 'Vắng';
            $row[] = $status;
        }
        fputcsv($output, $row);
    }

    // Close the output stream
    fclose($output);
    exit();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
