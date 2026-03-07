<?php
    session_start();
    //KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
    if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'Admin') {
         header("Location: index.php");
         exit;
    }
    require_once 'dbconnection.php';
    //LẤY DỮ LIỆU DANH SÁCH TÀI KHOẢN
    $tai_khoan_list = [];
    $error_message = '';
    try {
        $stmt = $pdo->query("SELECT MaNV, HoTenNV, VaiTro FROM NHANVIEN ORDER BY MaNV ASC");
        $tai_khoan_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Không có dữ liệu tài khoản";
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản</title>
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
        <main class="main-content">
            <div class="admin-content">
                <div class="admin-header">
                    <h2>Quản lý tài khoản</h2>
                    <?php
                        if (isset($_GET['error'])) {
                            if ($_GET['error'] == 'duplicate') {
                                echo "Mã nhân viên đã tồn tại";
                            } elseif ($_GET['error'] == 'empty') {
                                echo "Vui lòng nhập đầy đủ thông tin";
                            } elseif ($_GET['error'] == 'system') {
                                echo "Lỗi hệ thống";
                            }
                        }
                    ?>
                    <button id="show-add-modal-btn" class="btn-primary">
                        Thêm tài khoản mới
                    </button>
                </div>
                <?php if ($error_message): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <table class="order-table"> 
                    <thead>
                        <tr>
                            <th style="width: 15%;">Mã nhân viên</th>
                            <th>Họ tên nhân viên</th>
                            <th style="width: 40%;">Vai trò</th>
                            <th style="width: 20%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tai_khoan_list) && !$error_message): ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">Chưa có tài khoản nào.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tai_khoan_list as $tk): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tk['MaNV']); ?></td>
                                    <td><?php echo htmlspecialchars($tk['HoTenNV']); ?></td>
                                    <td><?php echo htmlspecialchars($tk['VaiTro']); ?></td>
                                    <td>
                                        <a href="" class="btn-edit" 
                                            data-manv="<?php echo htmlspecialchars($tk['MaNV']); ?>"   
                                            data-hoten="<?php echo htmlspecialchars($tk['HoTenNV']); ?>" 
                                            data-vaitro="<?php echo htmlspecialchars($tk['VaiTro']); ?>"> 
                                            Sửa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div> 
    <div id="add-modal" class="modal-overlay">
        <div class="modal-content">
            <span id="close-modal-btn" class="modal-close">&times;</span>
            <h2>Thêm tài khoản mới</h2>
            <form id="add-category-form" action="xulythemtaikhoan.php" method="POST">
                <div class="form-group">
                    <label for="add-account-id">Mã nhân viên (*):</label>
                    <input type="text" id="add-account-id" name="account_id" required maxlength="9" placeholder="Nhập mã nhân viên vào đây">
                </div>
                <div class="form-group">
                    <label for="add-account-name">Họ tên nhân viên (*):</label>
                    <input type="text" id="add-account-name" name="account_name" required maxlength="49" placeholder="Nhập họ tên vào đây">
                </div>
                <div class="form-group">
                    <label for="add-account-password">Mật khẩu (*):</label>
                    <input type="password" id="add-account-password" name="account_password" rows="4" required maxlength="62" placeholder="Nhập mật khẩu vào đây"></input>
                </div>
                <div class="form-group">
                    <label for="add-account-role">Vai trò (*):</label>
                    <select id="add-account-role" name="account_role" required>
                        <option value="NhanVien">Nhân viên</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Lưu tài khoản</button>
            </form>
        </div>
    </div>
    <div id="edit-modal" class="modal-overlay">
        <div class="modal-content">
            <span id="close-edit-modal-btn" class="modal-close">&times;</span> 
            <h2>Cập nhật tài khoản</h2> 
            <form id="edit-account-form" action="xulysuataikhoan.php" method="POST"> 
                <input type="hidden" id="edit-account-id" name="account_id">
                <div class="form-group">
                    <label for="edit-account-name">Họ tên nhân viên (*):</label>
                    <input type="text" id="edit-account-name" name="account_name" maxlength="9" required> 
                </div>
                <div class="form-group">
                    <label for="edit-account-password">Mật khẩu mới (*):</label>
                    <input type="password" id="edit-account-password" name="account_password" rows="4" required maxlength="62" placeholder="Nhập mật khẩu mới vào đây"></input> 
                </div>
                <div class="form-group">
                    <label for="edit-account-role">Vai trò (*):</label>
                    <select id="edit-account-role" name="account_role" required>
                        <option value="NhanVien">Nhân viên</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Cập nhật</button> 
            </form>
        </div>
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
    <script src="./js/scriptquanlytaikhoan.js"></script>
</body>
</html>