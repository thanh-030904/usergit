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

// Truy vấn lấy danh sách sản phẩm
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sản Phẩm</title>
</head>
<body>
    <h1>Quản Lý Sản Phẩm</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Sản Phẩm</th>
                <th>Giá</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?>
                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn Có Muốn Xóa Sản Phẩm Không?')">Xóa</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="4">Không có sản phẩm nào.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
