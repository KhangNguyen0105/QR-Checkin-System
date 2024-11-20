<?php
    require 'config.php';

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $course_name = $_POST['course_name'];
        $course_description = $_POST['course_description'];
        $teacher_id = $_SESSION['user_id'];

        // Prepare the SQL statement
        $sql = "INSERT INTO courses (course_name, descriptions, teacher_id, created_at) VALUES (:course_name, :course_description, :teacher_id, NOW())";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'course_name' => $course_name,
                'course_description' => $course_description,
                'teacher_id' => $teacher_id
            ]);

            // Redirect to the course management page after successful creation
            echo 
                "<script>
                    alert('Lớp học đã được tạo thành công!');
                    window.location.href = 'teacher_dashboard.php';
                </script>";
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Initialize an empty array for courses
    $courses = [];
    // Fetch existing courses for the current teacher
    try {
        $teacher_id = $_SESSION['user_id'];
        $sql = "SELECT id, course_name FROM courses WHERE teacher_id = :teacher_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['teacher_id' => $teacher_id]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching courses: " . $e->getMessage();
    }
?>



<head>  
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
</head>

<div class="manage-courses">
    <h2>Quản lý lớp học</h2>
    
    <!-- Form to Add New Course -->
    <div class="add-course">
        <h3>Tạo lớp học mới</h3>
        <form action="#" method="POST">
            <label for="course-name">Tên lớp:</label>
            <input type="text" id="course-name" name="course_name" required>

            <label for="course-description">Mô tả:</label>
            <textarea id="course-description" name="course_description" rows="4"></textarea>

            <button type="submit">Tạo lớp học mới</button>
        </form>
    </div>

    <!-- List of Existing Courses -->
    <div class="existing-courses">
        <h3>Lớp học của bạn</h3>
        <table class="course-table">
            <thead>
                <tr>
                    <th>Mã lớp</th>
                    <th>Tên lớp</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['id']); ?></td>
                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                        <td>
                            <a href="view_course.php?course_id=<?php echo htmlspecialchars($course['id']); ?>" class="action-btn view-btn">Xem danh sách học sinh</a>
                            <a href="#" class="action-btn edit-btn" data-course-id="<?php echo htmlspecialchars($course['id']); ?>" data-course-name="<?php echo htmlspecialchars($course['course_name']); ?>" data-course-desc="Mô tả cho lớp học này.">Chỉnh sửa thông tin lớp học</a>
                            <a href="delete_course.php?course_id=<?php echo htmlspecialchars($course['id']); ?>" class="action-btn delete-btn" onclick="return confirm('Bạn chắc chắn muốn xoá lớp học này?');">Xoá lớp học</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <!-- Edit Course Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Chỉnh sửa lớp học</h2>
            <form id="editCourseForm" action="edit_course.php" method="post">
                <input type="hidden" name="course_id" id="course_id">

                <label for="course_name">Tên lớp học</label>
                <input type="text" name="course_name" id="course_name" required>

                <label for="course_desc">Mô tả</label>
                <textarea name="course_desc" id="course_desc" rows="4" required></textarea>

                <button type="submit">Lưu</button>
            </form>
        </div>
    </div>

    </div>
</div>

<script>

    // Get the modal and close button
    var modal = document.getElementById("editModal");
    var closeBtn = document.getElementsByClassName("close")[0];

    // Get the edit buttons
    var editButtons = document.querySelectorAll(".edit-btn");

    // Loop through all edit buttons and add click event
    editButtons.forEach(function(button) {
        button.addEventListener("click", function() {
            var courseId = this.getAttribute("data-course-id");
            var courseName = this.getAttribute("data-course-name");
            var courseDesc = this.getAttribute("data-course-desc");

            // Populate the form with course data
            document.getElementById("course_id").value = courseId;
            document.getElementById("course_name").value = courseName;
            document.getElementById("course_desc").value = courseDesc;

            // Open the modal
            modal.style.display = "block";
        });
    });

    // Close the modal when clicking the close button
    closeBtn.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when clicking outside of the modal content
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

</script>