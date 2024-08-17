<?php
// product_detail.php

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'thanh');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn dữ liệu sản phẩm
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Lấy dữ liệu sản phẩm
    $product = $result->fetch_assoc();
} else {
    echo "Sản phẩm không tồn tại.";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* styles.css */
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

header {
    background: #333;
    color: #fff;
    padding: 10px 0;
    text-align: center;
}

main {
    padding: 20px;
}

.product-detail {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
}

.product-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.product-description {
    margin-top: 10px;
}

footer {
    text-align: center;
    padding: 10px;
    background: #f4f4f4;
}

footer a {
    color: #333;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

</style>
<body>
    <header>
        <h1>Chi Tiết Sản Phẩm</h1>
    </header>

    <main>
        <div class="product-detail">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <?php if (!empty($product['image'])): ?>
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
            <?php endif; ?>
            <p><strong>Mô Tả:</strong></p>
            <div class="product-description">
                <?php echo htmlspecialchars($product['description']); ?>
            </div>
            <p><strong>Giá Tiền:</strong> <?php echo htmlspecialchars($product['price']); ?> VNĐ</p>
        </div>
    </main>

    <footer>
        <button><a href="index.php">Quay lại trang chính</a></button>
    </footer>
</body>
</html>
