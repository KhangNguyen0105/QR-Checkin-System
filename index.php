
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <!-- <link rel="stylesheet" href="css/login.css"> Thêm đường dẫn tới file CSS -->
     <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

.error-message {
    color: red;
    margin-bottom: 15px;
}

.signup-link {
    margin-top: 15px;
}

.signup-link a {
    color: #007BFF;
    text-decoration: none;
}

.signup-link a:hover {
    text-decoration: underline;
}

.back-button {
    margin-top: 10px;
    display: block;
    color: #007BFF;
    text-decoration: none;
}

.back-button:hover {
    text-decoration: underline;
}
.back-to-home {
    margin-top: 20px;
}

.back-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.back-button:hover {
    background-color: #0056b3;
    text-decoration: none;
}

     </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng nhập</h2>
        <form action="login.php" method="POST" id="loginForm">
            <div class="form-group">
                <label for="username">Tên Đăng Nhập</label>
                <input type="text" id="username" name="taikhoan" required>
            </div>

            <div class="form-group">
                <label for="Password">Mật khẩu</label>
                <input type="password" id="Password" name="matkhau" required>
            </div>

            <div class="form-group">
                <label for="role">Đăng nhập với tư cách:</label>
                <select name="role" id="role" required>
                    <option value="student">Sinh viên</option>
                    <option value="teacher">Giảng viên</option>
                </select>
            </div>

            <?php if (!empty($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>

            <button type="submit" name="login">Đăng nhập</button>
        </form>

        <div class="signup-link">
            <a href="./register.php">Bạn chưa có tài khoản?</a>
        </div>

        <!-- Nút Quay lại trang chủ -->
        <div class="back-to-home">
            <a href="./index.php" class="back-button">Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>
