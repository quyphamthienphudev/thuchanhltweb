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
        $vaitro = $_POST['account_role'];
        $matkhau_moi = $_POST['account_password'];
        if (empty($manv) || empty($hoten) || empty($vaitro)) {
            header("Location: quanlytaikhoan.php?error=empty");
            exit;
        }
        if (!empty($matkhau_moi)) {
            $sql = "UPDATE NHANVIEN SET HoTenNV = ?, VaiTro = ?, MatKhau = ? WHERE MaNV = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hoten, $vaitro, $matkhau_moi, $manv]);
        } else {
            $sql = "UPDATE NHANVIEN SET HoTenNV = ?, VaiTro = ? WHERE MaNV = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hoten, $vaitro, $manv]);
        }
        header("Location: quanlytaikhoan.php?success=edit");
        exit;
    } catch (PDOException $e) {
        header("Location: quanlytaikhoan.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>