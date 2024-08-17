<?php
session_start();

// Kiểm tra xem người dùng có phải là admin không
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: /user/index.php'); // Chuyển hướng nếu không phải admin
    exit();
}

// Kết nối cơ sở dữ liệu
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'thanh';
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = "";

    // Xử lý upload ảnh
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra loại file ảnh
        $valid_extensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $valid_extensions)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = basename($_FILES['image']['name']); // Lưu tên file vào cơ sở dữ liệu
            } else {
                echo "Có lỗi xảy ra khi tải lên file.";
            }
        } else {
            echo "Chỉ cho phép các định dạng ảnh: jpg, jpeg, png, gif.";
        }
    } else {
        echo "Có lỗi xảy ra khi tải lên file.";
    }

    if (!empty($image)) {
        $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssis', $name, $description, $price, $image);
        $stmt->execute();
        echo "Sản phẩm đã được thêm!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Hàng Bán</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Sản Phẩm</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                    <li><a href="add_product.php">Đăng Hàng Bán</a></li>
                <?php } ?>
                <li><a href="cart.php">Giỏ Hàng</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Đăng Hàng Bán</h1>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
            <form method="post" enctype="multipart/form-data">
                <label for="name">Tên Sản Phẩm:</label>
                <input type="text" id="name" name="name" required>
                <label for="description">Mô Tả:</label>
                <textarea id="description" name="description" required></textarea>
                <label for="price">Giá:</label>
                <input type="number" id="price" name="price" required>
                <label for="image">Hình Ảnh:</label>
                <input type="file" id="image" name="image" required>
                <button type="submit">Thêm Sản Phẩm</button>
            </form>
        <?php } else { ?>
            <p>Bạn không có quyền để thêm sản phẩm.</p>
        <?php } ?>
    </main>
    <script src="js/scripts.js"></script>
</body>
</html>
