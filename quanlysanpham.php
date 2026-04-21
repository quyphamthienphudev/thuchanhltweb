<?php
    session_start();
    //KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
    if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
        header("Location: index.php");
        exit;
    }
    require_once 'dbconnection.php';
    //LẤY DỮ LIỆU DANH MỤC VÀ DANH SÁCH SẢN PHẨM
    $san_pham_list = [];
    $danh_muc_list = [];
    $error_message = '';
    try {
        $stmt = $pdo->query("SELECT * FROM SANPHAM ORDER BY MaSP ASC");
        $san_pham_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt_dm = $pdo->query("SELECT MaDM, TenDM FROM DANHMUC ORDER BY TenDM ASC");
        $danh_muc_list = $stmt_dm->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_message = "Không có dữ liệu sản phẩm";
    }
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
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
            <div class="admin-content">
                <div class="admin-header">
                    <h2>Quản lý sản phẩm</h2>
                    <?php
                        if (isset($_GET['error'])) {
                            if ($_GET['error'] == 'duplicate') {
                                echo "Mã sản phẩm đã tồn tại";
                            }
                            elseif ($_GET['error'] == 'empty') {
                                echo "Vui lòng nhập đầy đủ thông tin";
                            }
                            elseif ($_GET['error'] == 'system') {
                                echo "Lỗi hệ thống";
                            }
                        }
                    ?>
                    <button id="show-add-modal-btn" class="btn-primary">Thêm sản phẩm mới</button>
                </div>
                <?php if ($error_message): ?>
                <p style="color: red;">{{ $error_message }}</p>
                <?php endif; ?>
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Hãng sản xuất</th>
                            <th>Giá bán</th>
                            <th>Số lượng tồn</th>
                            <th>Mã danh mục</th>
                            <th>Hình ảnh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($san_pham_list)): ?>
                        <tr>
                            <td colspan="8" style="text-align:center;">Chưa có sản phẩm nào.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($san_pham_list as $sp): ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($sp['MaSP']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($sp['TenSP']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($sp['HangSX']); ?>
                            </td>
                            <td>
                                <?php echo number_format($sp['GiaBan'], 0, ',', '.'); ?> đ
                            </td>
                            <td>
                                <?php echo htmlspecialchars($sp['SoLuongTon']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($sp['MaDM']); ?>
                            </td>
                            <td><img src="uploads/<?php echo htmlspecialchars($sp['HinhAnh']); ?>" style="width:60px;">
                            </td>
                            <td>
                                <a href="#" class="btn-edit" data-masp="<?php echo htmlspecialchars($sp['MaSP']); ?>"
                                    data-tensp="<?php echo htmlspecialchars($sp['TenSP']); ?>"
                                    data-hangsx="<?php echo htmlspecialchars($sp['HangSX']); ?>"
                                    data-giaban="<?php echo htmlspecialchars($sp['GiaBan']); ?>"
                                    data-soluong="<?php echo htmlspecialchars($sp['SoLuongTon']); ?>"
                                    data-madm="<?php echo htmlspecialchars($sp['MaDM']); ?>"
                                    data-mota="<?php echo htmlspecialchars($sp['MoTa']); ?>"
                                    data-hinhanh="<?php echo htmlspecialchars($sp['HinhAnh']); ?>">Sửa</a>
                                <a href="xulyxoasanpham.php?masp=<?php echo $sp['MaSP']; ?>" class="btn-delete" ">Xoá</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <div id=" add-modal" class="modal-overlay">
                                    <div class="modal-content" style="max-height:90vh; overflow-y:auto;">
                                        <span id="close-modal-btn" class="modal-close">&times;</span>
                                        <h2>Thêm sản phẩm mới</h2>
                                        <form id="add-product-form" action="xulythemsanpham.php" method="POST"
                                            enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Mã sản phẩm (*):</label>
                                                <input type="text" name="masp" required maxlength="9"
                                                    placeholder="Nhập mã sản phẩm vào đây">
                                            </div>
                                            <div class="form-group">
                                                <label>Tên sản phẩm (*):</label>
                                                <input type="text" name="tensp" required maxlength="69"
                                                    placeholder="Nhập tên sản phẩm vào đây">
                                            </div>
                                            <div class="form-group">
                                                <label>Hãng sản xuất (*):</label>
                                                <input type="text" name="hangsx" required maxlength="29"
                                                    placeholder="Nhập hãng sản xuất vào đây">
                                            </div>
                                            <div class="form-group">
                                                <label>Giá bán (*):</label>
                                                <input type="number" name="giaban" required min="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Số lượng tồn (*):</label>
                                                <input type="number" name="soluong" required min="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Danh mục (*):</label>
                                                <select name="madm" required>
                                                    <?php foreach ($danh_muc_list as $dm): ?>
                                                    <option value="<?php echo $dm['MaDM']; ?>">
                                                        <?php echo $dm['TenDM']; ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả:</label>
                                                <textarea name="mota" rows="3" required maxlength="254"
                                                    placeholder="Nhập mô tả vào đây"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Hình ảnh:</label>
                                                <input type="file" name="hinhanh" accept="image/*" required>
                                            </div>
                                            <button type="submit" class="btn-submit">Lưu sản phẩm</button>
                                        </form>
                                    </div>
            </div>
            <div id="edit-modal" class="modal-overlay">
                <div class="modal-content" style="max-height:90vh; overflow-y:auto;">
                    <span id="close-edit-modal-btn" class="modal-close">&times;</span>
                    <h2>Cập nhật sản phẩm</h2>
                    <form id="edit-product-form" action="xulysuasanpham.php" method="POST"
                        enctype="multipart/form-data">
                        <input type="hidden" name="masp" id="edit-masp">
                        <div class="form-group">
                            <label>Tên sản phẩm (*):</label>
                            <input type="text" name="tensp" id="edit-tensp" maxlength="69" required>
                        </div>
                        <div class="form-group">
                            <label>Hãng sản xuất (*):</label>
                            <input type="text" name="hangsx" id="edit-hangsx" maxlength="29" required>
                        </div>
                        <div class="form-group">
                            <label>Giá bán (*):</label>
                            <input type="number" name="giaban" id="edit-giaban" required min="1">
                        </div>
                        <div class="form-group">
                            <label>Số lượng tồn (*):</label>
                            <input type="number" name="soluong" id="edit-soluong" required min="1">
                        </div>
                        <div class="form-group">
                            <label>Danh mục (*):</label>
                            <select name="madm" id="edit-madm" required>
                                <?php foreach ($danh_muc_list as $dm): ?>
                                <option value="<?php echo $dm['MaDM']; ?>">
                                    <?php echo $dm['TenDM']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mô tả:</label>
                            <textarea name="mota" id="edit-mota" rows="3" required maxlength="254"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Hình ảnh mới:</label>
                            <input type="file" name="hinhanh" id="edit-hinhanh" accept="image/*">
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
            <script src="./js/scriptquanlysanpham.js"></script>
</body>

</html>