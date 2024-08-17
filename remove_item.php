<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Kết nối cơ sở dữ liệu
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'thanh';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý yêu cầu xóa sản phẩm
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                // Reindex mảng giỏ hàng sau khi xóa
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                // Đặt thông báo vào session
                $_SESSION['message'] = "Sản phẩm đã được xóa khỏi giỏ hàng!";
                $_SESSION['message_type'] = "success";
                break;
            }
        }
    } else {
        $_SESSION['message'] = "Giỏ hàng của bạn đang trống.";
        $_SESSION['message_type'] = "error";
    }

    // Điều hướng trở lại trang giỏ hàng
    header('Location: cart.php');
    exit;
} else {
    // Điều hướng nếu không có yêu cầu xóa
    header('Location: index.php');
    exit;
}
?>
