<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="main-header">
        <div class="container">
            <a href="index.php" class="logo">Cửa hàng bán linh kiện máy tính</a>
            <?php
                include_once 'menu/menu.php'; 
            ?>
            <a href="dangnhap.php" class="login-btn">Đăng nhập</a>
        </div>
    </header>
    <div class="container">
        <div class="form-wrapper">
            <h2>Đăng nhập tài khoản</h2>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p style="color: red; background: #ffe0e0; padding: 10px; border-radius: 5px;">' 
                     . htmlspecialchars($_SESSION['error']) 
                     . '</p>';
                unset($_SESSION['error']);
            }
            ?>
            <form id="login-form" action="xulydangnhap.php" method="POST">
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Đăng nhập</button>
            </form>
        </div>
    </div>
</body>

</html>