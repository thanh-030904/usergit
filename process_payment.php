<?php
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

// Kiểm tra nếu giỏ hàng trống
if (empty($_SESSION['cart'])) {
    die("Giỏ hàng trống.");
}

// Lấy thông tin thanh toán
$name = $_POST['name'];
$address = $_POST['address'];
$payment_method = $_POST['payment_method'];
$cart = $_SESSION['cart'];

// Lưu đơn hàng vào cơ sở dữ liệu
$sql = "INSERT INTO orders (customer_name, address, payment_method, total_price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$total_price = 0;
foreach ($cart as $product_id => $quantity) {
    $sql_product = "SELECT price FROM products WHERE id = ?";
    $stmt_product = $conn->prepare($sql_product);
    $stmt_product->bind_param('i', $product_id);
    $stmt_product->execute();
    $stmt_product->bind_result($price);
    $stmt_product->fetch();
    $total_price += $price * $quantity;
}

$stmt->bind_param('sssd', $name, $address, $payment_method, $total_price);
$stmt->execute();
$order_id = $stmt->insert_id;

// Lưu chi tiết đơn hàng
foreach ($cart as $product_id => $quantity) {
    $sql_details = "INSERT INTO order_details (order_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt_details = $conn->prepare($sql_details);
    $stmt_details->bind_param('iii', $order_id, $product_id, $quantity);
    $stmt_details->execute();
}

// Xóa giỏ hàng sau khi thanh toán thành công
unset($_SESSION['cart']);

echo "Thanh toán thành công! Đơn hàng của bạn đã được ghi nhận.";
?>
