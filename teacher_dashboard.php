<?php
    require 'config.php';
    
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    $teacher_id = $_SESSION['user_id'];
    $teacher_name = $_SESSION['full_name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <h2> <?php echo $teacher_name; ?> </h2>
        </div>
        <ul>
        <?php
            // Get the current page from the URL parameter '?page'
            $page = isset($_GET['page']) ? $_GET['page'] : 'manage-courses'; // Default to 'manage-courses'

            // Helper function to add 'active' class if it's the current page
            function isActive($current_page, $page) {
                return $current_page === $page ? 'class="active"' : '';
            }
            ?>

            <li><a href="?page=manage-courses" <?= isActive($page, 'manage-courses'); ?>><i class="fas fa-book"></i> Quản lý lớp học</a></li>
            <li><a href="?page=create-session" <?= isActive($page, 'create-session'); ?>><i class="fas fa-calendar-alt"></i> Tạo buổi học</a></li>
            <li><a href="?page=reports" <?= isActive($page, 'reports'); ?>><i class="fas fa-chart-bar"></i> Báo cáo điểm danh</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <img src="asset/img/logoQuyNhon.png" alt="Banner" style="width: 100%; height: auto;">
        </header>

        <!-- Additional sections (e.g. Manage Courses, Create Sessions, Reports) go here -->
        <?php
            // Use the query parameter 'page' to determine what content to load
            $page = isset($_GET['page']) ? $_GET['page'] : 'manage-courses';

            // Include the requested page
            switch ($page) {
                case 'manage-courses':
                    include 'manage-courses.php';
                    break;
                case 'create-session':
                    include 'create-session.php';
                    break;
                case 'reports':
                    include 'reports.php';
                    break;
            }
        ?>

    </div>
</body>
</html>
