<?php
session_start();

// Kết nối cơ sở dữ liệu
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'thanh';
$conn = new mysqli($servername, $username, $password, $dbname);
// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra quyền admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: /login/login.php');
    exit();
}

// Xử lý cập nhật thông tin người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $username = $_POST['username'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $username, $role, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: /user/admin.php');
    exit();
}

// Lấy thông tin người dùng
$user_id = intval($_GET['id']);
$sql = "SELECT id, username, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Người dùng không tồn tại.");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Người Dùng</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/user/index.php">Trang Chủ</a></li>
                <li><a href="/user/admin/admin.php">Quản Lý Người Dùng</a></li>
                <li><a href="/user/login/logout.php">Đăng Xuất</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Chỉnh Sửa Người Dùng</h1>
        <form action="edit_user.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div>
                <label for="username">Tên Đăng Nhập:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div>
                <label for="role">Vai Trò:</label>
                <select id="role" name="role">
                    <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
            </div>
            <button type="submit">Cập Nhật</button>
        </form>
    </main>
    <script src="js/scripts.js"></script>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
