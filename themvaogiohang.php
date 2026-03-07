<?php
session_start();
if (!isset($_POST['masp'])) {
    header("Location: index.php");
    exit;
}
$masp = $_POST['masp'];
$tensp = $_POST['tensp'];
$giaban = (int)$_POST['giaban'];
$hinhanh = $_POST['hinhanh'];
$soluong = max(1, (int)$_POST['soluong']);
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
// Nếu đã có sản phẩm → cộng số lượng
if (isset($_SESSION['cart'][$masp])) {
    $_SESSION['cart'][$masp]['soluong'] += $soluong;
} else {
    $_SESSION['cart'][$masp] = [
        'masp' => $masp,
        'tensp' => $tensp,
        'giaban' => $giaban,
        'hinhanh' => $hinhanh,
        'soluong' => $soluong
    ];
}
header("Location: giohang.php");
exit;
