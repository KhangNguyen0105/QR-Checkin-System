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
            <h2>Teacher Panel</h2>
        </div>
        <ul>
            <!-- <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li> -->
            <li><a href="#" class="active"><i class="fas fa-book"></i> Manage Courses</a></li>
            <li><a href="#"><i class="fas fa-calendar-alt"></i> Create Session</a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i> Attendance Reports</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Welcome, Teacher</h1>
        </header>

        <!-- Additional sections (e.g. Manage Courses, Create Sessions, Reports) go here -->
        <?php
            // Use the query parameter 'page' to determine what content to load
            $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

            // Include the requested page
            switch ($page) {
                case 'manage-courses':
                    include 'manage-courses.html';
                    break;
                case 'create-session':
                    include 'create-session.php';
                    break;
                case 'reports':
                    include 'reports.php';
                    break;
                default:
                    include 'dashboard.php';
            }
        ?>

    </div>
</body>
</html>
