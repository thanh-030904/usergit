<?php
session_start();

// Kết nối tới cơ sở dữ liệu
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'thanh';
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Mã hóa mật khẩu
$password = password_hash('123456789', PASSWORD_DEFAULT);

echo "Mật khẩu mã hóa: " . $password;
?>
