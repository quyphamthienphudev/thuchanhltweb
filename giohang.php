<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$tongTien = 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="main-header">
<div class="container">
    <a href="index.php" class="logo">Cửa hàng bán linh kiện máy tính</a>
    <?php include_once 'menu/menu.php'; ?>
    <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            $hoten = htmlspecialchars($_SESSION['hoten']);
            $vaitro = $_SESSION['vaitro'];
            ?>
            <div class="user-menu">
                <a href="#" class="user-name">Xin chào, <?php echo $hoten; ?></a>
                <ul class="dropdown-menu">
                    <?php if ($vaitro === 'NhanVien'): ?>
                        <li><a href="quanlydanhmuc.php">Quản lý danh mục</a></li>
                        <li><a href="quanlysanpham.php">Quản lý sản phẩm</a></li>
                        <li><a href="quanlydondathang.php">Quản lý đơn đặt hàng</a></li>
                    <?php endif; ?>
                    <?php if ($vaitro === 'Admin'): ?>
                        <li><a href="quanlytaikhoan.php">Quản lý tài khoản</a></li>
                    <?php endif; ?>
                    <li><a href="dangxuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php
        } 
        else {
            echo '<a href="dangnhap.php" class="login-btn">Đăng nhập</a>';
        }
    ?>
</div>
</header>
<div class="container admin-content">
<h2>Giỏ hàng của bạn</h2>
<?php if (isset($_GET['success'])): ?>
    <div class="alert-success">
        ✅ Đặt hàng thành công! Cảm ơn bạn đã mua hàng.
    </div>
<?php endif; ?>
<?php if (empty($cart)): ?>
    <p>Giỏ hàng trống.</p>
<?php else: ?>
<table class="order-table">
<thead>
<tr>
    <th>Hình ảnh</th>
    <th>Tên sản phẩm</th>
    <th>Giá</th>
    <th>Số lượng</th>
    <th>Thành tiền</th>
    <th>Xóa</th>
</tr>
</thead>
<tbody>
<?php foreach ($cart as $item): 
    $thanhTien = $item['giaban'] * $item['soluong'];
    $tongTien += $thanhTien;
?>
<tr>
<td><img src="uploads/<?php echo $item['hinhanh']; ?>"></td>
<td><?php echo $item['tensp']; ?></td>
<td><?php echo number_format($item['giaban']); ?> đ</td>
<td><?php echo $item['soluong']; ?></td>
<td><?php echo number_format($thanhTien); ?> đ</td>
<td>
    <a class="btn-delete" href="xoagiohang.php?masp=<?php echo $item['masp']; ?>">X</a>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<h3 style="margin-top:15px">Tổng tiền: <?php echo number_format($tongTien); ?> đ</h3>
<a href="index.php" class="btn-secondary">← Mua tiếp</a>
<a href="dathang.php" class="btn-primary">Đặt hàng</a>
<?php endif; ?>
</div>
<footer class="main-footer">
    <div class="container footer-content">
        <div class="footer-section contact-info">
            <h4>Thông tin liên hệ</h4>
            <p>Địa chỉ: 180 Cao Lỗ, phường Chánh Hưng, TPHCM</p>
            <p>Email: pcstore@student.stu.edu.vn</p>
            <p>Điện thoại: 0123.456.789</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.961648495922!2d106.67768389999999!3d10.737439299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752fad3fb62a95%3A0xa9576c84a879d1fe!2zMTgwIENhbyBM4buXLCBQaMaw4budbmcgNCwgUXXhuq1uIDgsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCA3MDAwMA!5e0!3m2!1svi!2s!4v1765250417765!5m2!1svi!2s" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <?php include_once "footer/footer.php"; ?>
    </div>
    <p class="copyright">&copy; 2025 Cửa hàng bán linh kiện máy tính. All rights reserved.</p>
</footer>
</body>
</html>