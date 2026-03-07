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
        $sql = "UPDATE DANHMUC SET TenDM = ?, MoTaDM = ? WHERE MaDM = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tendm, $mota, $madm]);
        header("Location: quanlydanhmuc.php?success=edit");
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