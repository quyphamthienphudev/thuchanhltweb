<?php
    session_start();
    //KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
    if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
         header("Location: trangchu.php");
         exit;
    }
    require_once 'dbconnection.php'; 
    //LẤY DỮ LIỆU DANH MỤC
    $danh_muc_list = [];
    $error_message = '';
    try {
        $stmt = $pdo->query("SELECT MaDM, TenDM, MoTaDM FROM DANHMUC ORDER BY MaDM ASC");
        $danh_muc_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Không có dữ liệu danh mục";
    }
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header class="main-header">
        <div class="container">
            <a href="trangchu.php" class="logo">Cửa hàng bán linh kiện máy tính</a>
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
        <aside class="sidebar">
            <h3>Danh mục sản phẩm</h3>
            <ul class="category-list">
                <?php if (!empty($danh_muc_list)): ?>
                <?php foreach ($danh_muc_list as $dm): ?>
                <li>
                    <a href="trangchu.php?madm=<?php echo htmlspecialchars($dm['MaDM']); ?>">
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
            <div class="admin-content">
                <div class="admin-header">
                    <h2>Quản lý danh mục</h2>
                    <?php
                        if (isset($_GET['error'])) {
                            if ($_GET['error'] == 'duplicate') {
                                echo "Mã danh mục đã tồn tại";
                            }
                            elseif ($_GET['error'] == 'empty') {
                                echo "Vui lòng nhập đầy đủ thông tin";
                            }
                            elseif ($_GET['error'] == 'system') {
                                echo "Lỗi hệ thống";
                            }
                        }
                    ?>
                    <button id="show-add-modal-btn" class="btn-primary">
                        Thêm danh mục mới
                    </button>
                </div>
                <?php if ($error_message): ?>
                <p style="color: red;">
                    <?php echo $error_message; ?>
                </p>
                <?php endif; ?>
                <?php 
                    if (isset($_GET['error']) && $_GET['error'] == 'hasproduct' && isset($_GET['madm'])): 
                        $madm = htmlspecialchars($_GET['madm']);
                ?>
                <p style="color:red;">
                    Danh mục <b>
                        <?php echo "$madm"; ?>
                    </b> đang có sản phẩm, không thể xoá.
                </p>
                <?php endif; ?>
                <?php if (isset($_GET['success']) && $_GET['success'] == 'delete'): ?>
                <p style="color:green; font-weight:bold;">
                    Xoá danh mục thành công.
                </p>
                <?php endif; ?>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Mã danh mục</th>
                            <th>Tên danh mục</th>
                            <th style="width: 40%;">Mô tả</th>
                            <th style="width: 20%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($danh_muc_list) && !$error_message): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">Chưa có danh mục nào.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($danh_muc_list as $dm): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($dm['MaDM']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($dm['TenDM']); ?>
                            </td>
                            <td>
                                <?php echo nl2br(htmlspecialchars($dm['MoTaDM'])); ?>
                            </td>
                            <td>
                                <a href="#" class="btn-edit" data-madm="<?php echo htmlspecialchars($dm['MaDM']); ?>"
                                    data-tendm="<?php echo htmlspecialchars($dm['TenDM']); ?>"
                                    data-motadm="<?php echo htmlspecialchars($dm['MoTaDM']); ?>">
                                    Sửa
                                </a>
                                <a href="xulyxoadanhmuc.php?madm=<?php echo $dm['MaDM']; ?>" class="btn-delete"
                                    onclick="return confirm('Bạn có chắc chắn muốn xoá danh mục này?');">
                                    Xoá
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
            <h2>Thêm danh mục mới</h2>
            <form id="add-category-form" action="xulythemdanhmuc.php" method="POST">
                <div class="form-group">
                    <label for="add-category-id">Mã danh mục (*):</label>
                    <input type="text" id="add-category-id" name="category_id" required maxlength="9"
                        placeholder="Nhập mã danh mục vào đây">
                </div>
                <div class="form-group">
                    <label for="add-category-name">Tên danh mục (*):</label>
                    <input type="text" id="add-category-name" name="category_name" required maxlength="29"
                        placeholder="Nhập tên danh mục vào đây">
                </div>
                <div class="form-group">
                    <label for="add-category-desc">Mô tả:</label>
                    <textarea id="add-category-desc" name="category_desc" rows="4" required maxlength="49"
                        placeholder="Nhập mô tả vào đây"></textarea>
                </div>
                <button type="submit" class="btn-submit">Lưu danh mục</button>
            </form>
        </div>
    </div>
    <div id="edit-modal" class="modal-overlay">
        <div class="modal-content">
            <span id="close-edit-modal-btn" class="modal-close">&times;</span>
            <h2>Cập nhật danh mục</h2>
            <form id="edit-category-form" action="xulysuadanhmuc.php" method="POST">
                <input type="hidden" id="edit-category-id" name="category_id">
                <div class="form-group">
                    <label for="edit-category-name">Tên danh mục (*):</label>
                    <input type="text" id="edit-category-name" name="category_name" required maxlength="29">
                </div>
                <div class="form-group">
                    <label for="edit-category-desc">Mô tả:</label>
                    <textarea id="edit-category-desc" name="category_desc" rows="4" required maxlength="49"></textarea>
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
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.961648495922!2d106.67768389999999!3d10.737439299999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752fad3fb62a95%3A0xa9576c84a879d1fe!2zMTgwIENhbyBM4buXLCBQaMaw4budbmcgNCwgUXXhuq1uIDgsIFRow6BuaCBwaOG7kSBI4buTIENow60gTWluaCA3MDAwMA!5e0!3m2!1svi!2s!4v1765250417765!5m2!1svi!2s"
                    width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <?php include_once "footer/footer.php"; ?>
        </div>
        <p class="copyright">&copy; 2025 Cửa hàng bán linh kiện máy tính. All rights reserved.</p>
    </footer>
    <script src="./js/scriptquanlydanhmuc.js"></script>
</body>

</html>