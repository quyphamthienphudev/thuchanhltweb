<?php
session_start();
require_once 'dbconnection.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $manv = $_POST['username'];
    $matkhau_nhapvao = $_POST['password'];
    if (empty($manv) || empty($matkhau_nhapvao)) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
        header("Location: dangnhap.php");
        exit;
    }
    //KIỂM TRA TÊN ĐĂNG NHẬP VÀ MẬT KHẨU LƯU TRÙNG KHỚP CHỮ HOA CHỮ THƯỜNG MỚI CHO ĐĂNG NHẬP
    try {
        $sql = "SELECT MaNV, HoTenNV, MatKhau, VaiTro FROM NHANVIEN WHERE BINARY MaNV = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$manv]);
        $nhanvien = $stmt->fetch();
        if ($nhanvien && $matkhau_nhapvao === $nhanvien['MatKhau']) {
            session_regenerate_id(true);
            $_SESSION['loggedin'] = true;
            $_SESSION['manv'] = $nhanvien['MaNV'];
            $_SESSION['hoten'] = $nhanvien['HoTenNV'];
            $_SESSION['vaitro'] = $nhanvien['VaiTro'];
            header("Location: trangchu.php");
            exit;
        } else {
            $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng";
            header("Location: dangnhap.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng";
        header("Location: dangnhap.php");
        exit;
    }
} else {
    header("Location: dangnhap.php");
    exit;
}
?>
