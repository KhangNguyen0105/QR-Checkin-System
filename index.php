<?php
session_start();
require 'config.php'; // File to handle database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];

        // Redirect based on role
        if ($user['role'] === 'teacher') {
            header("Location: teacher_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }
        exit;
    } else {
        // Invalid login
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            width: 280px; /* Adjusted width */
            text-align: center;
        }
        .login-container img {
            width: 200px;
            margin-bottom: 1rem;
        }
        .login-container h2 {
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            color: #333;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group input {
            /* width: 100%; */
            /* width: calc(100% - 2px); Adjusted to fit within container */
            padding: 0.8rem;
            margin-top: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .login-btn {
            width: 100%;
            padding: 0.8rem;
            background-color: #007bff;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="login-container">
    <img src="asset/img/qnu-logo.png" alt="Logo"> <!-- Replace 'path_to_logo.png' with the actual path to your logo -->
    <h2>Điểm danh</h2>
    <form action="#" method="POST"> <!-- Replace 'login_process.php' with the actual login processing file -->
        <div class="form-group">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Mật khẩu" required>
        </div>
        <button type="submit" class="login-btn">Đăng nhập</button>
    </form>
</div>

</body>
</html>
