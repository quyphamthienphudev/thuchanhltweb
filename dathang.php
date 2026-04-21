<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đặt hàng</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="form-wrapper">
        <h2>Thông tin đặt hàng</h2>
        <form action="xulydondathang.php" method="POST">
            <div class="form-group">
                <label>Tên khách hàng</label>
                <input type="text" name="customer_name" maxlength="49" required>
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <input type="text" name="customer_phone" maxlength="11" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ giao hàng</label>
                <input type="text" name="customer_address" maxlength="49" required>
            </div>
            <input type="hidden" name="from_cart" value="1">
            <button class="btn-submit">Xác nhận đặt hàng</button>
        </form>
    </div>
</body>

</html>