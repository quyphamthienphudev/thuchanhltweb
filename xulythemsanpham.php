<?php
session_start();
require_once 'dbconnection.php';
//KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
    header("Location: trangchu.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $masp = trim($_POST['masp'] ?? '');
    $tensp = trim($_POST['tensp'] ?? '');
    $hangsx = trim($_POST['hangsx'] ?? '');
    $giaban = $_POST['giaban'] ?? 0;
    $soluong = $_POST['soluong'] ?? 0;
    $madm = $_POST['madm'] ?? '';
    $mota = trim($_POST['mota'] ?? '');
    //KIỂM TRA TRÙNG MÃ SẢN PHẨM
    $check_sql = "SELECT * FROM SANPHAM WHERE MaSP = :masp LIMIT 1";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([':masp' => $masp]);
    if ($check_stmt->fetch()) {
    header("Location: quanlysanpham.php?error=duplicate");
    exit;
    }
    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            die("Không tạo được thư mục upload. Vui lòng kiểm tra quyền ghi.");
        }
    }
    $hinhanh_filename = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
        if (!is_uploaded_file($_FILES['hinhanh']['tmp_name'])) {
            die("Không nhận được file upload hợp lệ.");
        }
        $originalName = $_FILES['hinhanh']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowedExt)) {
            die("Chỉ cho phép file ảnh (jpg, jpeg, png, gif, webp).");
        }
        $maxBytes = 5 * 1024 * 1024;
        if ($_FILES['hinhanh']['size'] > $maxBytes) {
            die("Kích thước file vượt quá 5MB.");
        }
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9_\-]/', '_', $base);
        $timestamp = time();
        $file_name = $timestamp . "_" . $base . "." . $ext;
        $targetPath = $uploadDir . $file_name;
        if (!move_uploaded_file($_FILES['hinhanh']['tmp_name'], $targetPath)) {
            $err = error_get_last();
            $msg = isset($err['message']) ? $err['message'] : '';
            die("Lỗi tải hình ảnh! Không thể di chuyển file. $msg");
        }
        $hinhanh_filename = $file_name;
    } else {
        die("Vui lòng chọn hình ảnh để upload.");
    }
    try {
        $sql = "INSERT INTO SANPHAM (MaSP, MaDM, TenSP, HangSX, GiaBan, SoLuongTon, MoTa, HinhAnh)
                VALUES (:masp, :madm, :tensp, :hangsx, :giaban, :soluong, :mota, :hinhanh)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':masp' => $masp,
            ':madm' => $madm,
            ':tensp' => $tensp,
            ':hangsx' => $hangsx,
            ':giaban' => $giaban,
            ':soluong' => $soluong,
            ':mota' => $mota,
            ':hinhanh' => $hinhanh_filename
        ]);
        header("Location: quanlysanpham.php");
        exit;
    } catch (PDOException $e) {
        if (!empty($hinhanh_filename) && file_exists($uploadDir . $hinhanh_filename)) {
            @unlink($uploadDir . $hinhanh_filename);
        }
        die("Lỗi thêm sản phẩm");
    }
}
?>