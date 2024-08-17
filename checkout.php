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
$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$cart = $_SESSION['cart'];

// Kiểm tra dữ liệu đầu vào
if (empty($name) || empty($address) || empty($payment_method)) {
    die("Vui lòng điền đầy đủ thông tin.");
}

// Tính tổng giá
$total_price = 0;

foreach ($cart as $product_id => $quantity) {
    // Truy vấn để lấy giá sản phẩm
    $sql_product = "SELECT price FROM products WHERE id = ?";
    $stmt_product = $conn->prepare($sql_product);
    $stmt_product->bind_param('i', $product_id);
    $stmt_product->execute();
    $stmt_product->bind_result($price);
    $stmt_product->fetch();
    
    // Kiểm tra nếu giá sản phẩm là null
    if ($price !== null && is_numeric($quantity)) {
        $total_price += $price * $quantity;
    } else {
        die("Giá sản phẩm không hợp lệ.");
    }
}

// Lưu đơn hàng vào cơ sở dữ liệu
$sql = "INSERT INTO orders (customer_name, address, payment_method, total_price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
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


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Trang Chủ</a></li>
                <li><a href="cart.php">Giỏ Hàng</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Thanh Toán</h1>
        <table>
            <thead>
                <tr>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $product_id => $quantity) {
                    // Truy vấn để lấy thông tin sản phẩm
                    $sql = "SELECT name, price FROM products WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $product_id);
                    $stmt->execute();
                    $stmt->bind_result($name, $price);
                    $stmt->fetch();
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($name); ?></td>
                        <td><?php echo htmlspecialchars($price); ?></td>
                        <td><?php echo htmlspecialchars($quantity); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h2>Tổng cộng: <?php echo number_format($total_price, 2); ?> VNĐ</h2>
        
        <form action="checkout.php" method="post">
    <label for="name">Họ và Tên:</label>
    <input type="text" id="name" name="name" required>
    <label for="address">Địa Chỉ:</label>
    <input type="text" id="address" name="address" required>
    <label for="payment_method">Phương Thức Thanh Toán:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="cod">Thanh toán khi nhận hàng</option>
        <option value="paypal">Thanh toán qua PayPal</option>
    </select>
    <button type="submit">Thanh Toán</button>
</form>
    </main>
    <script src="js/scripts.js"></script>
</body>
</html>
