<?php
    session_start();
    //KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
    if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
        header("Location: index.php");
        exit;
    }
    require_once 'dbconnection.php';
    //HIỂN THỊ DANH SÁCH ĐƠN ĐẶT HÀNG
    $orders = [];
    $error_message = '';
    try {
        $stmt = $pdo->query("
        SELECT 
            ddh.MaDH, kh.HoTenKH, kh.SoDienThoai, kh.DiaChiGH,
            sp.TenSP, ctdh.SoLuong, ctdh.DonGia, ctdh.ThanhTien,
            ddh.PhuongThucTT, ddh.NgayDat, ddh.TrangThaiDH
        FROM DONDATHANG ddh
        JOIN KHACHHANG kh ON ddh.MaKH = kh.MaKH
        JOIN CHITIETDONDATHANG ctdh ON ddh.MaDH = ctdh.MaDH
        JOIN SANPHAM sp ON ctdh.MaSP = sp.MaSP
        ORDER BY ddh.NgayDat DESC
        ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
    $error_message = "Không thể tải dữ liệu đơn hàng.";
    }
    //ĐẾM SỐ LƯỢNG ĐƠN ĐẶT HÀNG
    //$total_orders = 0;
    //try {
        //$countStmt = $pdo->query("SELECT COUNT(ddh.MaDH) AS total FROM DONDATHANG ddh 
            //JOIN KHACHHANG kh ON ddh.MaKH = kh.MaKH 
            //JOIN CHITIETDONDATHANG ctdh ON ddh.MaDH = ctdh.MaDH 
            //JOIN SANPHAM sp ON ctdh.MaSP = sp.MaSP");
        //$row = $countStmt->fetch(PDO::FETCH_ASSOC);
        //$total_orders = $row['total'];
    //} catch (PDOException $e) {
        //$total_orders = 0;
//}
//<strong>Tổng số đơn hàng:</strong> <?= $total_orders 
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn đặt hàng</title>
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
                <a href="#" class="user-name">Xin chào,
                    <?php echo $hoten; ?>
                </a>
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
        <main class="main-content">
            <div class="admin-content">
                <div class="admin-header">
                    <h2>Quản lý đơn đặt hàng</h2>
                </div>
                <div class="table-container">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Tên khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ giao hàng</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Tổng tiền</th>
                                <th>Phương thức thanh toán</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>
                                    <?= $order['MaDH'] ?>
                                </td>
                                <td>
                                    <?= $order['HoTenKH'] ?>
                                </td>
                                <td>
                                    <?= $order['SoDienThoai'] ?>
                                </td>
                                <td>
                                    <?= $order['DiaChiGH'] ?>
                                </td>
                                <td>
                                    <?= $order['TenSP'] ?>
                                </td>
                                <td>
                                    <?= $order['SoLuong'] ?>
                                </td>
                                <td>
                                    <?= number_format($order['DonGia']) ?> đ
                                </td>
                                <td>
                                    <?= number_format($order['ThanhTien']) ?> đ
                                </td>
                                <td>
                                    <?= $order['PhuongThucTT'] ?>
                                </td>
                                <td>
                                    <?= date("d/m/Y", strtotime($order['NgayDat'])) ?>
                                </td>
                                <td>
                                    <?= $order['TrangThaiDH'] ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.961648495922!2d106.67768389999999!3d10.737439299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752fad3fb62a95%3A0xa9576c84a879d1fe!2zMTgwIENhbyBM4buXLCBQaMaw4budbmcgNCwgUXXhuq1uIDgsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCA3MDAwMA!5e0!3m2!1svi!2s!4v1765250417765!5m2!1svi!2s"
                    width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <?php include_once "footer/footer.php"; ?>
        </div>
        <p class="copyright">&copy; 2025 Cửa hàng bán linh kiện máy tính. All rights reserved.</p>
    </footer>
    <script src="./js/scriptquanlysanpham.js"></script>
</body>

</html>