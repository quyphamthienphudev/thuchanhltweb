<?php
session_start();
require_once 'dbconnection.php';
$masp = isset($_GET['masp']) ? $_GET['masp'] : '';
if ($masp == '') {
    die("Không tìm thấy sản phẩm!");
}
try {
    $stmt = $pdo->prepare("SELECT * FROM SANPHAM WHERE MaSP = ?");
    $stmt->execute([$masp]);
    $san_pham = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$san_pham) {
        die("Sản phẩm không tồn tại.");
    }
} catch (PDOException $e) {
    die("Lỗi dữ liệu");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($san_pham['TenSP']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="main-header">
    <div class="container">
        <a href="index.php" class="logo">Cửa hàng bán linh kiện máy tính</a>
        <?php include_once 'menu/menu.php'; ?>

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
            <div class="user-menu">
                <a href="#" class="user-name">Xin chào, <?php echo htmlspecialchars($_SESSION['hoten']); ?></a>
                <ul class="dropdown-menu">
                    <?php if ($_SESSION['vaitro'] === 'NhanVien'): ?>
                        <li><a href="quanlydanhmuc.php">Quản lý danh mục</a></li>
                        <li><a href="quanlysanpham.php">Quản lý sản phẩm</a></li>
                        <li><a href="quanlydondathang.php">Quản lý đơn đặt hàng</a></li>
                    <?php endif; ?>
                    <?php if ($_SESSION['vaitro'] === 'Admin'): ?>
                        <li><a href="quanlytaikhoan.php">Quản lý tài khoản</a></li>
                    <?php endif; ?>
                    <li><a href="dangxuat.php">Đăng xuất</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="dangnhap.php" class="login-btn">Đăng nhập</a>
        <?php endif; ?>
    </div>
</header>
<div class="container">
    <div class="product-detail-layout">
        <div class="product-images">
            <img src="uploads/<?php echo htmlspecialchars($san_pham['HinhAnh']); ?>">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($san_pham['TenSP']); ?></h1>
            <p class="price">
                <?php echo number_format($san_pham['GiaBan'], 0, ',', '.'); ?> đ
            </p>
            <p>
                Tình trạng:
                <strong>
                    <?php echo ($san_pham['SoLuongTon'] > 0) ? 'Còn hàng' : 'Hết hàng'; ?>
                </strong>
            </p>
            <!-- FORM THÊM VÀO GIỎ HÀNG -->
            <form action="themvaogiohang.php" method="POST">
                <input type="hidden" name="masp" value="<?php echo $san_pham['MaSP']; ?>">
                <input type="hidden" name="tensp" value="<?php echo htmlspecialchars($san_pham['TenSP']); ?>">
                <input type="hidden" name="giaban" value="<?php echo $san_pham['GiaBan']; ?>">
                <input type="hidden" name="hinhanh" value="<?php echo $san_pham['HinhAnh']; ?>">
                <div style="margin: 1rem 0;">
                    <label>Số lượng:</label>
                    <input type="number" name="soluong" value="1" min="1" style="width:70px">
                </div>
                <button type="submit" class="add-to-cart">
                    Thêm vào giỏ hàng
                </button>
            </form>
            <a href="giohang.php" class="btn-primary" style="margin-top:10px;display:inline-block">
                Xem giỏ hàng
            </a>
        </div>
    </div>
    <div class="product-description">
        <h3>Mô tả sản phẩm</h3>
        <p><?php echo nl2br(htmlspecialchars($san_pham['MoTa'])); ?></p>
        <h3>Thông số kỹ thuật</h3>
        <ul>
            <li>Hãng SX: <?php echo htmlspecialchars($san_pham['HangSX']); ?></li>
        </ul>
    </div>
</div>
<footer class="main-footer">
    <div class="container footer-content">
        <?php include_once "footer/footer.php"; ?>
    </div>
    <p class="copyright">
        &copy; 2025 Cửa hàng bán linh kiện máy tính. All rights reserved.
    </p>
</footer>
</body>
</html>
