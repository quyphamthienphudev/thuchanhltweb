<?php
session_start();
require_once 'dbconnection.php';
//KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
    header("Location: trangchu.php");
    exit;
}
if (isset($_GET['masp'])) {
    $masp = $_GET['masp'];
    $stmt = $pdo->prepare("SELECT HinhAnh FROM SANPHAM WHERE MaSP = :masp");
    $stmt->execute([':masp' => $masp]);
    $image = $stmt->fetchColumn();
    try {
        $stmt = $pdo->prepare("DELETE FROM SANPHAM WHERE MaSP = :masp");
        $stmt->execute([':masp' => $masp]);
        if (!empty($image) && file_exists("uploads/" . $image)) {
            unlink("uploads/" . $image);
        }
        header("Location: quanlysanpham.php");
        exit;
    } catch (PDOException $e) {
        die("Lỗi xoá sản phẩm");
    }
}
?>
