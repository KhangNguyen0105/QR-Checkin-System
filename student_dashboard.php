<?php
    require 'config.php';
    
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    $student_id = $_SESSION['user_id'];
    $student_name = $_SESSION['full_name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="asset/css/student-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">  
            <h2> <?php echo $student_name; ?> </h2>
        </div>
        <ul>
            <li><a href="#" class="active"><i class="fas fa-home"></i> Tổng quan</a></li>
            <li><a href="#"><i class="fas fa-qrcode"></i> Quét mã QR</a></li>
            <li><a href="#"><i class="fas fa-list"></i> Lịch sử điểm danh</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <img src="asset/img/logoQuyNhon.png" alt="Banner" style="width: 100%; height: auto;">
        </header>

        <!-- Scan QR Code Section -->
        <section class="qr-scan">
            <h2>Quét mã QR để điểm danh</h2>
            <div class="qr-reader">
                <div id="reader" style="width: 300px"></div>
            </div>
            <div id="scanResult" style="margin-top: 20px; text-align: center;"></div>
        </section>

        <!-- Attendance History Section -->
        <section class="attendance-history">
            <h2>Lịch sử điểm danh</h2>
            <table>
                <thead>
                    <tr>
                        <th>Lớp</th>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch attendance records for the student
                    $sql = 'SELECT c.course_name, a.check_in_time, a.status
                            FROM attendance a
                            JOIN sessions s ON a.session_id = s.id
                            JOIN courses c ON s.course_id = c.id
                            WHERE a.student_id = :student_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['student_id' => $student_id]);
                    $attendance_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($attendance_records)) { 
                        echo '<tr><td colspan="3">Chưa có lịch sử điểm danh.</td></tr>';
                    } else {
                        // Display the records
                        foreach ($attendance_records as $record) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($record['course_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($record['check_in_time']) . '</td>';
                            echo '<td>' . htmlspecialchars($record['status']) . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
    
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html5QrCode = new Html5Qrcode("reader");
            let isScanning = false;
            let hasScanned = false;

            const config = { 
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            // Hàm xử lý khi quét thành công
            function onScanSuccess(decodedText, decodedResult) {
                let qrData;
                try { 
                    qrData = JSON.parse(decodedText); 
                } catch (e) { 
                    document.getElementById('scanResult').innerHTML = `<div style="color: red;">Lỗi: Mã QR không hợp lệ. Vui lòng thử lại.</div>`; addRescanButton(); return; 
                } 
                
                if (!qrData.session_id) { 
                    document.getElementById('scanResult').innerHTML = `<div style="color: red;">Lỗi: Mã QR không đúng định dạng. Vui lòng thử lại.</div>`; 
                    addRescanButton(); 
                    return; 
                }

                if (!hasScanned) { // Chỉ xử lý nếu chưa quét
                    hasScanned = true;
                    
                    // Dừng quét
                    if (html5QrCode.isScanning) {
                        html5QrCode.stop().then(() => {
                            isScanning = false;
                            console.log('Đã dừng quét');
                            
                            // Hiển thị kết quả
                            document.getElementById('scanResult').innerHTML = 
                                `<div style="color: green;">Thành công</div>`;
                            
                            // Gửi kết quả về server
                            checkAttendance(qrData.session_id);
                            
                            // Thêm nút quét lại
                            addRescanButton();
                        }).catch((err) => {
                            console.error('Lỗi khi dừng quét:', err);
                        });
                    }
                }
            }

            // Hàm thêm nút quét lại
            function addRescanButton() {
                const rescanButton = document.createElement('button');
                rescanButton.textContent = 'Quét lại';
                rescanButton.style.cssText = `
                    margin-top: 10px;
                    padding: 8px 16px;
                    background-color: #007bff;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                `;
                rescanButton.onmouseover = function() {
                    this.style.backgroundColor = '#0056b3';
                };
                rescanButton.onmouseout = function() {
                    this.style.backgroundColor = '#007bff';
                };
                rescanButton.onclick = startScanning;
                document.getElementById('scanResult').appendChild(rescanButton);
            }

            // Hàm bắt đầu quét
            function startScanning() {
                if (!isScanning) {
                    isScanning = true;
                    hasScanned = false;
                    document.getElementById('scanResult').innerHTML = '';
                    
                    html5QrCode.start(
                        { facingMode: "environment" },
                        config,
                        onScanSuccess,
                        (error) => {
                            // Chỉ log lỗi, không hiển thị
                            console.warn(`Lỗi quét: ${error}`);
                        }
                    ).catch((err) => {
                        isScanning = false;
                        document.getElementById('scanResult').innerHTML = 
                            `<div style="color: red;">Lỗi: ${err}. Vui lòng kiểm tra quyền truy cập camera và thử lại.</div>`;
                        addRescanButton();
                    });
                }
            }

            // Bắt đầu quét khi trang tải xong
            startScanning();

            // Dọn dẹp khi rời trang
            window.addEventListener('beforeunload', () => {
                if (html5QrCode.isScanning) {
                    html5QrCode.stop().catch(err => console.error(err));
                }
            });
        });
            

        function checkAttendance(sessionId) {
            // Gửi yêu cầu tới server để xử lý điểm danh
            fetch('check-attendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ session_id: sessionId })
            })
            .then(response => {
                // Kiểm tra xem phản hồi từ server có hợp lệ hay không
                if (!response.ok) {
                    throw new Error(`Server error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const scanResultElement = document.getElementById('scanResult');

                // Hiển thị thông báo dựa trên kết quả từ server
                if (data.success) {
                    scanResultElement.innerHTML = 
                        `<div style="color: green;">${data.message}</div>`;
                } else {
                    scanResultElement.innerHTML = 
                        `<div style="color: red;">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                document.getElementById('scanResult').innerHTML = 
                    `<div style="color: red;">Không thể kết nối tới server. Vui lòng thử lại.</div>`;
            });
        }

    </script>

</body>
</html>
