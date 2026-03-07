<?php
session_start();
require_once 'dbconnection.php';
//HIỂN THỊ SẢN PHẨM THEO DANH MỤC
$danh_muc = null;
$san_pham_list = [];
$error_message = '';
if (isset($_GET['madm']) && !empty($_GET['madm'])) {
    $madm = $_GET['madm'];
    try {
        $stmt_dm = $pdo->prepare("SELECT MaDM, TenDM FROM DANHMUC WHERE MaDM = :madm");
        $stmt_dm->execute(['madm' => $madm]);
        $danh_muc = $stmt_dm->fetch(PDO::FETCH_ASSOC);
        $stmt_sp = $pdo->prepare("
            SELECT MaSP, TenSP, GiaBan, HinhAnh 
            FROM SANPHAM 
            WHERE TRIM(MaDM) = TRIM(:madm)
            ORDER BY MaSP DESC
        ");
        $stmt_sp->execute(['madm' => $madm]);
        $san_pham_list = $stmt_sp->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Loi ket noi: ";
    }
} else {
    echo "Danh muc khong hop le.";
    exit;
}
?>