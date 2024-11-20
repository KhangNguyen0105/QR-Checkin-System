<?php
require 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập.']);
    exit;
}

$student_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra xem session_id có được gửi lên từ client hay không
if (isset($data['session_id'])) {
    
    $session_id = $data['session_id'];

    // Lấy thông tin buổi học từ database
    $sql = 'SELECT session_begin, session_end FROM sessions WHERE id = :session_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['session_id' => $session_id]);
    $session = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($session) {
        $check_in_time = date('Y-m-d H:i:s');
        $session_begin = $session['session_begin'];
        $session_end = $session['session_end'];

        // Xác định trạng thái điểm danh ('Có mặt', 'Đi trễ', 'Vắng')
        $status = 'Vắng';
        if ($check_in_time >= $session_begin && $check_in_time <= $session_end) {
            // Kiểm tra xem học sinh có đến đúng giờ hay trễ
            $grace_period = date('H:i:s', strtotime($session_begin) + 15 * 60); // 15 phút từ giờ bắt đầu
            if ($check_in_time <= $grace_period) {
                $status = 'Có mặt';
            } else {
                $status = 'Đi trễ';
            }
        }

        // Kiểm tra xem sinh viên đã điểm danh trong buổi này chưa (tránh trùng lặp)
        $sql = 'SELECT id FROM attendance WHERE student_id = :student_id AND session_id = :session_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['student_id' => $student_id, 'session_id' => $session_id]);
        $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRecord) {
            echo json_encode(['success' => false, 'message' => 'Bạn đã điểm danh cho buổi này rồi.']);
            exit;
        }

        // Chèn dữ liệu vào bảng attendance
        $sql = 'INSERT INTO attendance (student_id, session_id, check_in_time, status) 
                VALUES (:student_id, :session_id, :check_in_time, :status)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'student_id' => $student_id,
            'session_id' => $session_id,
            'check_in_time' => $check_in_time,
            'status' => $status
        ]);

        echo json_encode(['success' => true, 'message' => 'Điểm danh thành công.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Buổi học không tồn tại.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Không nhận được session_id.']);
}
?>
