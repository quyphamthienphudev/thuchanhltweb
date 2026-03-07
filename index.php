<?php
session_start();
require_once 'dbconnection.php'; 
$danh_muc_list = [];
$san_pham_list = [];
$error_message = '';
try {
    $stmt = $pdo->query("SELECT MaDM, TenDM FROM DANHMUC ORDER BY TenDM ASC");
    $danh_muc_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_GET['madm']) && !empty($_GET['madm'])) {
        $madm = $_GET['madm'];
        $stmt_sp = $pdo->prepare("
            SELECT MaSP, TenSP, GiaBan, HinhAnh 
            FROM SANPHAM 
            WHERE TRIM(MaDM) = TRIM(:madm)
            ORDER BY MaSP DESC
        ");
        $stmt_sp->execute(['madm' => $madm]);
        $san_pham_list = $stmt_sp->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt_sp = $pdo->query("
            SELECT MaSP, TenSP, GiaBan, HinhAnh 
            FROM SANPHAM 
            ORDER BY MaSP DESC
        ");
        $san_pham_list = $stmt_sp->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error_message = "Không thể lấy dữ liệu.";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
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
<div class="container main-layout">
    <aside class="sidebar">
        <h3>Danh mục sản phẩm</h3>
        <ul class="category-list">
            <?php if (!empty($danh_muc_list)): ?>
                <?php foreach ($danh_muc_list as $dm): ?>
                    <li>
                        <a href="index.php?madm=<?php echo htmlspecialchars($dm['MaDM']); ?>">
                            <?php echo htmlspecialchars($dm['TenDM']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Không có danh mục</li>
            <?php endif; ?>
        </ul>
    </aside>
    <main class="main-content">
        <h2>Danh sách sản phẩm</h2>
        <div class="product-grid">
            <?php if (empty($san_pham_list)): ?>
                <p>Chưa có sản phẩm nào.</p>
            <?php else: ?>
                <?php foreach ($san_pham_list as $sp): ?>
                    <article class="product-card">
                        <a href="sanpham.php?masp=<?php echo htmlspecialchars($sp['MaSP']); ?>">
                            <img src="uploads/<?php echo htmlspecialchars($sp['HinhAnh']); ?>">
                            <h4><?php echo htmlspecialchars($sp['TenSP']); ?></h4>
                            <p class="price"><?php echo number_format($sp['GiaBan'], 0, ',', '.'); ?> đ</p>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
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
