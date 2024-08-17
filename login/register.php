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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra sự tồn tại của các khóa trong mảng $_POST
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $re_password = $_POST['re_password'] ?? '';
    $sdt = $_POST['sdt'] ?? '';

    // Kiểm tra mật khẩu và nhập lại mật khẩu có khớp không
    if ($password === $re_password) {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Chuẩn bị truy vấn SQL để chèn dữ liệu vào cơ sở dữ liệu
        $sql = "INSERT INTO users (username, password, sdt) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $username, $hashed_password, $sdt);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Đăng ký thành công!";
            $_SESSION['message_type'] = "success";
            header('Location: login.php'); // Chuyển hướng sau khi đăng ký thành công
            exit();
        } else {
            $_SESSION['message'] = "Đăng ký thất bại. Vui lòng thử lại.";
            $_SESSION['message_type'] = "danger";
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Mật khẩu không khớp. Vui lòng kiểm tra lại.";
        $_SESSION['message_type'] = "warning";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #1f293a;
        }
        .container {
            position: relative;
            width: 295px;
            height: 256px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container span {
            position: absolute;
            left: 0;
            width: 32px;
            height: 6px;
            background: #2c4766;
            border-radius: 8px;
            transform-origin: 150px;
            transform: scale(2.2) rotate(calc(var(--i) * (360deg / 50)));
            animation: animateBlink 3s linear infinite;
            animation-delay: calc(var(--i) * (3s / 50));
        }
        @keyframes animateBlink {
            0% {
                background: #0ef;
            }
            25% {
                background: #2c4766;
            }
        }
        .signup-box {
            position: absolute;
            width: 400px;
        }
        .signup-box form {
            width: 100%;
            padding: 0 20px;
        }
        h2 {
            font-size: 2em;
            color: #0ef;
            text-align: center;
        }
        .input-box {
            position: relative;
            margin: 20px 0;
        }
        .input-box input {
            width: 100%;
            height: 50px;
            background: transparent;
            border: 2px solid #2c4766;
            outline: none;
            border-radius: 40px;
            font-size: 1em;
            color: #fff;
            padding: 0 20px;
            transition: .5s ease;
        }
        .input-box input:focus,
        .input-box input:valid {
            border-color: #0ef;
        }
        .input-box label {
            position: absolute;
            top: 50%;
            left: 20px;
            transform: translateY(-50%);
            font-size: 1em;
            color: #fff;
            pointer-events: none;
            transition: .5s ease;
        }
        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -10px;
            font-size: .85em;
            background: #1f293a;
            padding: 0 6px;
            color: #0ef;
        }
        .btn {
            width: 100%;
            height: 45px;
            background: #0ef;
            border: none;
            outline: none;
            border-radius: 40px;
            cursor: pointer;
            font-size: 1em;
            color: #1f293a;
            font-weight: 600;
        }
        .login-link {
            margin: 20px 0 10px;
            text-align: center;
        }
        .login-link a {
            font-size: 1em;
            color: #0ef;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-box">
            <h2>Signup</h2>
            <form action="register.php" method="post">
                <div class="input-box">
                    <input type="email" name="username" required>
                    <label>Gmail</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <label>Mật Khẩu</label>
                </div>
                <div class="input-box">
                    <input type="password" name="re_password" required>
                    <label>Nhập Lại Mật Khẩu</label>
                </div>
                <div class="input-box">
                    <input type="text" name="sdt" required>
                    <label>Số Điện Thoại</label>
                </div>
                <button type="submit" class="btn" name="register">Signup</button>
                <div class="login-link">
                    <a href="login.php">Login</a>
                </div>
            </form>
        </div>
        <span style="--i:0;"></span>
        <span style="--i:1;"></span>
        <span style="--i:2;"></span>
        <span style="--i:3;"></span>
        <span style="--i:4;"></span>
        <span style="--i:5;"></span>
        <span style="--i:6;"></span>
        <span style="--i:7;"></span>
        <span style="--i:8;"></span>
        <span style="--i:9;"></span>
        <span style="--i:10;"></span>
        <span style="--i:11;"></span>
        <span style="--i:12;"></span>
        <span style="--i:13;"></span>
        <span style="--i:14;"></span>
        <span style="--i:15;"></span>
        <span style="--i:16;"></span>
        <span style="--i:17;"></span>
        <span style="--i:18;"></span>
        <span style="--i:19;"></span>
        <span style="--i:20;"></span>
        <span style="--i:21;"></span>
        <span style="--i:22;"></span>
        <span style="--i:23;"></span>
        <span style="--i:24;"></span>
        <span style="--i:25;"></span>
        <span style="--i:26;"></span>
        <span style="--i:27;"></span>
        <span style="--i:28;"></span>
        <span style="--i:29;"></span>
        <span style="--i:30;"></span>
        <span style="--i:31;"></span>
        <span style="--i:32;"></span>
        <span style="--i:33;"></span>
        <span style="--i:34;"></span>
        <span style="--i:35;"></span>
        <span style="--i:36;"></span>
        <span style="--i:37;"></span>
        <span style="--i:38;"></span>
        <span style="--i:39;"></span>
        <span style="--i:40;"></span>
        <span style="--i:41;"></span>
        <span style="--i:42;"></span>
        <span style="--i:43;"></span>
        <span style="--i:44;"></span>
        <span style="--i:45;"></span>
        <span style="--i:46;"></span>
        <span style="--i:47;"></span>
        <span style="--i:48;"></span>
        <span style="--i:49;"></span>
    </div>
</body>
</html>
