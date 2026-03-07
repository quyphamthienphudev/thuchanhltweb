<?php
session_start();
//KIỂM TRA VAI TRÒ KHI ĐĂNG NHẬP
if (!isset($_SESSION['loggedin']) || $_SESSION['vaitro'] !== 'NhanVien') {
    die("Bạn không có quyền truy cập.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'dbconnection.php'; 
    try {
        $madm = $_POST['category_id'];
        $tendm = $_POST['category_name'];
        $mota = $_POST['category_desc'];
        if (empty($madm) || empty($tendm)) {
            header("Location: quanlydanhmuc.php");
            exit;
        }
        //Kiểm tra trùng mã danh mục
        $check = $pdo->prepare("SELECT MaDM FROM DANHMUC WHERE MaDM = ?");
        $check->execute([$madm]);
        if ($check->rowCount() > 0) {
            header("Location: quanlydanhmuc.php?error=duplicate");
            exit;
        }
        $sql = "INSERT INTO DANHMUC (MaDM, TenDM, MoTaDM) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$madm, $tendm, $mota]);
        header("Location: quanlydanhmuc.php?success=add");
        exit;
    } catch (PDOException $e) {
        header("Location: quanlydanhmuc.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>