<?php
// view_course.php

// Hardcoded course data for testing
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : 1;
$course = [
    'id' => $course_id,
    'name' => 'Introduction to Programming'
];

// Hardcoded student data
$students = [
    ['id' => 1, 'name' => 'John Doe'],
    ['id' => 2, 'name' => 'Jane Smith'],
    ['id' => 3, 'name' => 'Alice Johnson']
];

// Hardcoded session data
$sessions = [
    ['id' => 1, 'session_date' => '2024-09-20'],
    ['id' => 2, 'session_date' => '2024-09-21'],
    ['id' => 3, 'session_date' => '2024-09-22']
];

// Hardcoded function to return attendance status
function getAttendanceStatus($student_id, $session_id) {
    // Example attendance status based on student_id and session_id
    $attendance_data = [
        1 => ['Present', 'Absent', 'Present'], // Attendance for student 1
        2 => ['Late', 'Present', 'Present'],   // Attendance for student 2
        3 => ['Absent', 'Late', 'Present']     // Attendance for student 3
    ];

    return $attendance_data[$student_id][$session_id - 1]; // Return status based on index
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course - <?php echo $course['name']; ?></title>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
</head>
<body>

<!-- Add the view-course class here to target the specific page -->
<div class="view-course">

    <div class="course-details">
        <h2><?php echo $course['name']; ?> - Attendance</h2>

        <!-- Export to Excel Button -->
        <form action="export_to_excel.php" method="post">
            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
            <button type="submit" class="export-btn">Export to Excel</button>
        </form>

        <!-- Attendance Table -->
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <?php foreach ($sessions as $session) { ?>
                        <th><?php echo $session['session_date']; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) { ?>
                    <tr>
                        <td><?php echo $student['name']; ?></td>
                        <?php foreach ($sessions as $session) { 
                            $status = getAttendanceStatus($student['id'], $session['id']);
                        ?>
                            <td><?php echo $status; ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
