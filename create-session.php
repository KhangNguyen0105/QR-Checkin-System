<?php
    require 'config.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
        header("Location: index.php");
        exit();
    }

    // Fetch the list of courses from the database
    try {
        $stmt = $pdo->prepare("SELECT id, course_name FROM courses");
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }

    // Handle form submission to create a new session
    $qr_url = null;
    $session_id = null; // Initialize session_id
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $course_id = $_POST['course_id'] ?? null;
        $session_date = $_POST['session_date'] ?? null;
        $session_begin = $_POST['session_begin'] ?? null;
        $session_end = $_POST['session_end'] ?? null;

        // Validate input data
        if ($course_id && $session_date && $session_begin && $session_end) {
            try {
                // Insert new session into the database
                $stmt = $pdo->prepare("INSERT INTO sessions (course_id, session_date, session_begin, session_end) 
                                    VALUES (:course_id, :session_date, :session_begin, :session_end)");
                $result = $stmt->execute([
                    'course_id' => $course_id,
                    'session_date' => $session_date,
                    'session_begin' => $session_begin,
                    'session_end' => $session_end
                ]);

                if ($result) {
                    // Get the last inserted session ID
                    $session_id = $pdo->lastInsertId();
                    
                    // Prepare data for the QR code
                    $qr_data = json_encode([
                        'session_id' => $session_id
                    ]);
                
                    // Create QR code using QuickChart API
                    $qr_url = "https://quickchart.io/qr?text=" . urlencode($qr_data) . "&size=300";
                
                    // Save the QR code URL to the database
                    $stmt = $pdo->prepare("UPDATE sessions SET qr_url = :qr_url WHERE id = :session_id");
                    $stmt->execute([
                        'qr_url' => $qr_url,
                        'session_id' => $session_id
                    ]);
                
                    // Đặt cờ session để hiển thị modal
                    $_SESSION['show_qr_modal'] = true;
                    $_SESSION['new_session_id'] = $session_id;
                } else {
                    echo "Error creating session.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                exit();
            }
        } else {
            echo "Vui lòng điền đầy đủ thông tin!";
        }
    }

    // Fetch the QR code URL from the database chỉ khi có cờ session
    if (isset($_SESSION['show_qr_modal']) && $_SESSION['show_qr_modal'] === true) {
        $stmt = $pdo->prepare("SELECT qr_url, id FROM sessions WHERE id = :session_id");
        $stmt->execute(['session_id' => $_SESSION['new_session_id']]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);

        // Reset session flag để không hiển thị lại modal sau khi tải lại trang
        unset($_SESSION['show_qr_modal']);
        unset($_SESSION['new_session_id']);
    } else {
        $session = null;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tạo buổi học mới</title>
    <link rel="stylesheet" href="asset/css/teacher-styles.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="create-session">
        <h2>Tạo buổi học mới</h2>

        <div class="session-form">
            <h3>Chi tiết buổi học</h3>
            <form action="#" method="POST">
                <label for="course-select">Chọn lớp học:</label>
                <select id="course-select" name="course_id" required>
                    <option value="" disabled selected>Chọn một lớp học</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>">
                            <?php echo htmlspecialchars($course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
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

    <?php if ($session && $session['qr_url']): ?>
        <div class="modal" id="qrModal" style="display: block;">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('qrModal').style.display='none'">&times;</span>
                <h3>Quét mã QR để điểm danh</h3>
                <img src="<?php echo $session['qr_url']; ?>" alt="QR Code">
            </div>
        </div>
    <?php endif; ?>

    <script>
        window.onclick = function(event) {
            var modal = document.getElementById('qrModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

</body>
</html>
