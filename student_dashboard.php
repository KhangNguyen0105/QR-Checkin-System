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
            <h2>Student Panel</h2>
        </div>
        <ul>
            <li><a href="#" class="active"><i class="fas fa-home"></i> Tổng quan</a></li>
            <li><a href="#"><i class="fas fa-qrcode"></i> Quét mã QR</a></li>
            <li><a href="#"><i class="fas fa-list"></i> Lịch sử điểm danh</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
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
            <div class="qr-scanner">
                <video id="preview" width="300" height="300"></video>
            </div>
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
                    <tr>
                        <td>Math 101</td>
                        <td>2024-09-21</td>
                        <td>Present</td>
                    </tr>
                    <tr>
                        <td>Physics 101</td>
                        <td>2024-09-20</td>
                        <td>Late</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>

    <!-- JavaScript for QR Code Scanning -->
    <script src="zxing.min.js"></script>
    <script>
        const codeReader = new ZXing.BrowserQRCodeReader();
        codeReader.decodeFromVideoDevice(null, 'preview', (result, err) => {
            if (result) {
                alert('Check-in successful for session ID: ' + result.text);
                // Handle the check-in logic, such as sending the data to the server
            }
        });
    </script>
</body>
</html>
