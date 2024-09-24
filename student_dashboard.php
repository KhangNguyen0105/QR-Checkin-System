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
            <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="#"><i class="fas fa-qrcode"></i> Scan QR Code</a></li>
            <li><a href="#"><i class="fas fa-list"></i> Attendance History</a></li>
            <li><a href="#"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Welcome, Student</h1>
        </header>

        <!-- Scan QR Code Section -->
        <section class="qr-scan">
            <h2>Scan QR Code for Attendance</h2>
            <div class="qr-scanner">
                <video id="preview" width="300" height="300"></video>
                <p>Scan the QR code to check-in for today's session.</p>
            </div>
        </section>

        <!-- Attendance History Section -->
        <section class="attendance-history">
            <h2>Your Attendance History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Status</th>
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
