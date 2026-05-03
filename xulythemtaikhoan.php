<?php
session_start();
//KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'Admin') {
    die("Bạn không có quyền truy cập.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'dbconnection.php'; 
    try {
        $manv = $_POST['account_id'];
        $hoten = $_POST['account_name'];
        $matkhau = $_POST['account_password'];
        $vaitro = $_POST['account_role'];
        if (empty($manv) || empty($hoten) || empty($matkhau) || empty($vaitro)) {
            header("Location: quanlytaikhoan.php");
            exit;
        }
        //KIỂM TRA TRÙNG MÃ NHÂN VIÊN
        $check = $pdo->prepare("SELECT MaNV FROM NHANVIEN WHERE MaNV = ?");
        $check->execute([$manv]);
        if ($check->rowCount() > 0) {
            header("Location: quanlytaikhoan.php?error=duplicate");
            exit;
        }
        $sql = "INSERT INTO NHANVIEN (MaNV, HoTenNV, MatKhau, VaiTro) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$manv, $hoten, $matkhau, $vaitro]);
        header("Location: quanlytaikhoan.php");
        exit;
    } catch (PDOException $e) {
        header("Location: quanlytaikhoan.php");
        exit;
    }
} else {
    header("Location: trangchu.php");
    exit;
}
?>