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

// Thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $cart_item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = $cart_item;
        }

        echo "Sản phẩm đã được thêm vào giỏ hàng!";
    } else {
        echo "Sản phẩm không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Menu Bán Hàng</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Sản Phẩm</a></li>
                <li><a href="add_product.php">Đăng Hàng Bán</a></li>
                <li><a href="cart.php">Giỏ Hàng</a></li>
                <li><a href="login/logout.php">Đăng Xuất</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Danh Sách Sản Phẩm</h1>
        <div class="product-list">
            <?php
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) { ?>
                <section class="products">
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <div class="product-item">
                            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="150">
                            <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p>Giá: <?php echo number_format($row['price'], 2); ?> VNĐ</p>
                            <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn-view">Xem Chi Tiết</a>
                            <form action="index.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="add_to_cart" class="btn-add-to-cart">Thêm Vào Giỏ</button>
                            </form>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">Xóa</a>
                        </div>
                    <?php } ?>
                </section>
            <?php } else { ?>
                <p>Không có sản phẩm nào.</p>
            <?php } ?>
        </div>
    </main>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
