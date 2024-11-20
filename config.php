<?php
// Database configuration
$host = 'localhost';
$db = 'quan_ly_diem_danh';
$user = 'root';
$pass = '';

try {
    // Create PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Set error mode to exception for error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If there’s an error, stop the script and display the message
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}
?>
