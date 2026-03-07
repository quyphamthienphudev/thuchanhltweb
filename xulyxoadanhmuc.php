<?php
session_start();
//KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
    die("Bạn không có quyền truy cập.");
}
if (isset($_GET['madm'])) {
    require_once 'dbconnection.php'; 
    try {
        $madm = $_GET['madm'];
        if (empty($madm)) {
             header("Location: quanlydanhmuc.php");
             exit;
        }
        $sql = "DELETE FROM DANHMUC WHERE MaDM = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$madm]);
        header("Location: quanlydanhmuc.php?success=delete");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == '23000' && $e->errorInfo[1] == 1451) {
             header("Location: quanlydanhmuc.php?error=hasproduct&madm=" . urlencode($madm));
        } else {
             header("Location: quanlydanhmuc.php?error=" . urlencode($e->getMessage()));
        }
        exit;
    }
} else {
    header("Location: quanlydanhmuc.php");
    exit;
}
?>