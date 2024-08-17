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

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Nếu không phải admin, chuyển hướng đến trang lỗi hoặc thông báo
    $_SESSION['message'] = "Bạn không có quyền xóa sản phẩm.";
    $_SESSION['message_type'] = "danger";
    header('Location: /user/index.php');
    exit();
}

// Xử lý xóa sản phẩm
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Xóa sản phẩm khỏi cơ sở dữ liệu
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Sản phẩm đã được xóa thành công!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Có lỗi xảy ra khi xóa sản phẩm.";
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "ID sản phẩm không hợp lệ.";
    $_SESSION['message_type'] = "danger";
}

$conn->close();

// Chuyển hướng về trang quản lý sản phẩm
header('Location: /user/manage_products.php'); // Đảm bảo đường dẫn đúng
exit();
?>
