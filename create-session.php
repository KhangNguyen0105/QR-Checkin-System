<head>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
</head>


<div class="create-session">
    <h2>Tạo buổi học mới</h2>

    <!-- Form to Create New Session -->
<div class="session-form">
    <h3>Chi tiết buổi học</h3>
    <form action="create_session.php" method="POST">
        <label for="course-select">Chọn lớp học:</label>
        <select id="course-select" name="course_id" required>
            <option value="" disabled selected>Chọn một lớp học</option>
            <!-- Dynamically populate courses -->
            <!-- Example options -->
            <option value="1">Math 101</option>
            <option value="2">Physics 101</option>
        </select>

        <label for="session-date">Chọn ngày:</label>
        <input type="date" id="session-date" name="session_date" required>

        <label for="session-begin">Thời gian bắt đầu:</label>
        <input type="time" id="session-begin" name="session_begin" required>

        <label for="session-end">Thời gian kết thúc:</label>
        <input type="time" id="session-end" name="session_end" required>

        <button type="submit">Tạo buổi học & Tạo mã QR</button>
    </form>
</div>

</div>
