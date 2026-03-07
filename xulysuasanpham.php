<?php
session_start();
require_once 'dbconnection.php';
//KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
    header("Location: index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $masp = $_POST['masp'];
    $tensp = $_POST['tensp'];
    $hangsx = $_POST['hangsx'];
    $giaban = $_POST['giaban'];
    $soluong = $_POST['soluong'];
    $madm = $_POST['madm'];
    $mota = $_POST['mota'];
    // Lấy ảnh hiện tại từ CSDL
    $stmt = $pdo->prepare("SELECT HinhAnh FROM SANPHAM WHERE MaSP = :masp");
    $stmt->execute([':masp' => $masp]);
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    // Mặc định giữ ảnh cũ
    $hinhanh = $current['HinhAnh'];
    if (!empty($_FILES['hinhanh']['name'])) {
        $file_name = time() . "_" . basename($_FILES['hinhanh']['name']);
        $target = "uploads/" . $file_name;
        if (move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target)) {
            $hinhanh = $file_name;
            if (!empty($old_image) && file_exists("uploads/" . $old_image)) {
                unlink("uploads/" . $old_image);
            }
            $hinhanh = $file_name;
        }
    }
    try {
        $sql = "UPDATE SANPHAM SET 
                TenSP = :tensp,
                HangSX = :hangsx,
                GiaBan = :giaban,
                SoLuongTon = :soluong,
                MaDM = :madm,
                MoTa = :mota,
                HinhAnh = :hinhanh
                WHERE MaSP = :masp";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':tensp' => $tensp,
            ':hangsx' => $hangsx,
            ':giaban' => $giaban,
            ':soluong' => $soluong,
            ':madm' => $madm,
            ':mota' => $mota,
            ':hinhanh' => $hinhanh,
            ':masp' => $masp
        ]);
        header("Location: quanlysanpham.php");
        exit;
    } catch (PDOException $e) {
        die("Lỗi cập nhật sản phẩm");
    }
}
?>