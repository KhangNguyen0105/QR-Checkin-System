<?php
// Start the session and include the database configuration
session_start();
require 'config.php';

// Check if the user is logged in and has access to view this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");
    exit();
}

// Get course ID from the GET request
$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : null;
if (!$course_id) {
    echo "Course ID is required.";
    exit();
}

try {
    // Fetch course details
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id = :course_id");
    $stmt->execute(['course_id' => $course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$course) {
        echo "Course not found.";
        exit();
    }

    // Fetch enrolled students with email
    $stmt = $pdo->prepare("
        SELECT u.id, u.full_name AS name, u.email
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

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course - <?php echo htmlspecialchars($course['course_name']); ?></title>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">

    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        /* Course Details */
        .view-course {
            max-width: 1200px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .course-details h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Button Row Styles */
        .button-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .back-btn,
        .import-btn,
        .export-btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover,
        .import-btn:hover,
        .export-btn:hover {
            background-color: #0056b3;
        }

        button[type="submit"] {
            cursor: pointer;
        }

        input[type="file"] {
            padding: 5px;
            border-radius: 5px;
            margin-right: 10px;
        }

        /* Table Container with Scroll */
        .table-container {
            width: 100%;
            overflow-x: auto; /* Thêm thanh cuộn ngang nếu bảng quá rộng */
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Tạo hiệu ứng nổi */
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* Đảm bảo bảng có kích thước tối thiểu để không bị co quá nhỏ */
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            white-space: nowrap; /* Không xuống hàng, nếu không vừa sẽ có thanh cuộn */
        }

        /* Cố định chiều rộng cho các cột */
        table th:nth-child(1),
        table td:nth-child(1) {
            width: 200px; /* Cột tên sinh viên */
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            width: 250px; /* Cột email */
        }

        table th:nth-child(n+3) {
            width: 150px; /* Cột điểm danh */
        }

        /* Table Header Styles */
        table th {
            background-color: #343a40;
            color: #fff;
            font-weight: bold;
        }

        /* Alternate Row Colors */
        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Action Button Styles */
        .edit-btn,
        .delete-btn {
            padding: 6px 10px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #28a745;
        }

        .edit-btn:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            table {
                min-width: 600px; /* Giảm kích thước tối thiểu trên thiết bị nhỏ hơn */
            }
        }


        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .modal input {
            width: calc(100% - 20px);
            margin: 10px 0;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .modal input:focus {
            border-color: #007bff;
        }

        .modal button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        /* Save Button */
        .modal button:first-of-type {
            background-color: #28a745;
            color: #fff;
            margin-right: 10px;
        }

        .modal button:first-of-type:hover {
            background-color: #218838;
        }

        /* Cancel Button */
        .modal button:last-of-type {
            background-color: #6c757d;
            color: #fff;
        }

        .modal button:last-of-type:hover {
            background-color: #5a6268;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="view-course">
    <div class="course-details">
        <h2><?php echo htmlspecialchars($course['course_name']); ?> - Chi tiết điểm danh</h2>
        
        <!-- Button Row -->
        <div class="button-row">
            <a href="teacher_dashboard.php?page=manage-courses" class="back-btn">Quay lại</a>
            
            <!-- Import Form -->
            <form id="import-form" action="import_students.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['id']); ?>">
                <input type="file" name="excel_file" accept=".xls,.xlsx" required>
                <button type="submit" class="import-btn">Nhập danh sách sinh viên</button>
            </form>

            <!-- Export Button -->
            <form action="export_to_excel.php" method="post">
                <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['id']); ?>">
                <button type="submit" class="export-btn">Tải xuống danh sách sinh viên</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <?php foreach ($sessions as $session) { ?>
                            <th><?php echo htmlspecialchars($session['session_date'] . ' ' . $session['session_begin']); ?></th>
                        <?php } ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <?php foreach ($sessions as $session) { 
                                $status = isset($attendanceData[$student['id']][$session['id']]) 
                                        ? $attendanceData[$student['id']][$session['id']] 
                                        : 'Vắng';
                            ?>
                                <td><?php echo htmlspecialchars($status); ?></td>
                            <?php } ?>
                            <td>
                                <button class="edit-btn" onclick="openModal(<?php echo htmlspecialchars(json_encode($student)); ?>)">Chỉnh sửa</button>
                                <button class="delete-btn" onclick="deleteStudent(<?php echo $student['id']; ?>)">Xoá</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>





<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Student</h3>
        <input type="hidden" id="edit-student-id">
        <input type="text" id="edit-student-name" placeholder="Student Name">
        <input type="email" id="edit-student-email" placeholder="Email">
        <button onclick="saveChanges()">Save Changes</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<script>
    function openModal(student) {
        document.getElementById("edit-student-id").value = student.id;
        document.getElementById("edit-student-name").value = student.name;
        document.getElementById("edit-student-email").value = student.email;
        document.getElementById("editModal").style.display = "flex";
    }

    function closeModal() {
        document.getElementById("editModal").style.display = "none";
    }

    function saveChanges() {
        const studentId = document.getElementById("edit-student-id").value;
        const studentName = document.getElementById("edit-student-name").value;
        const studentEmail = document.getElementById("edit-student-email").value;

        fetch("update_student.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: studentId, name: studentName, email: studentEmail })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Student updated successfully");
                location.reload(); // Refresh the page to show updated data
            } else {
                alert("Update failed: " + (data.message || "Unknown error"));
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred. Please try again.");
        });
    }

    function deleteStudent(studentId) {
        if (confirm("Bạn có chắc chắn muốn xóa học sinh này?")) {
            fetch("delete_student.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: studentId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Student deleted successfully");
                    location.reload();
                } else {
                    alert("Delete failed");
                }
            });
        }
    }
</script>

</body>
</html>
