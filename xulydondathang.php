<?php
session_start();
require_once 'dbconnection.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Sai phương thức gửi dữ liệu");
}
if (empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}
$ten_kh = trim($_POST['customer_name']);
$sdt    = trim($_POST['customer_phone']);
$diachi = trim($_POST['customer_address']);
$payment = $_POST['payment_method'] ?? 'TienMat';
if ($ten_kh == '' || $sdt == '' || $diachi == '') {
    die("Vui lòng nhập đầy đủ thông tin");
}
try {
    $pdo->beginTransaction();
    // 1️⃣ Tạo khách hàng
    $ma_kh = 'KH' . rand(1000,9999);
    $stmt = $pdo->prepare("
        INSERT INTO KHACHHANG (MaKH, HoTenKH, SoDienThoai, DiaChiGH)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$ma_kh, $ten_kh, $sdt, $diachi]);
    // 2️⃣ Tính tổng tiền
    $tongTien = 0;
    foreach ($_SESSION['cart'] as $item) {
        $tongTien += $item['giaban'] * $item['soluong'];
    }
    // 3️⃣ Tạo đơn hàng
    $ma_dh = 'DH' . rand(1000,9999);
    $ma_nv = 'NV02';
    $ngaydat = date('Y-m-d');
    $stmt = $pdo->prepare("
        INSERT INTO DONDATHANG 
        (MaDH, MaKH, MaNV, NgayDat, TongTien, PhuongThucTT, TrangThaiDH)
        VALUES (?, ?, ?, ?, ?, ?, 'Chờ xử lý')
    ");
    $stmt->execute([$ma_dh, $ma_kh, $ma_nv, $ngaydat, $tongTien, $payment]);
    // 4️⃣ Lưu chi tiết đơn hàng
    foreach ($_SESSION['cart'] as $item) {
        // kiểm tra tồn kho
        $stmt = $pdo->prepare("SELECT SoLuongTon FROM SANPHAM WHERE MaSP=?");
        $stmt->execute([$item['masp']]);
        $sp = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sp || $item['soluong'] > $sp['SoLuongTon']) {
            throw new Exception("Sản phẩm {$item['tensp']} không đủ tồn kho");
        }
        $ma_ct = 'CT' . rand(1000,9999);
        $stmt = $pdo->prepare("
            INSERT INTO CHITIETDONDATHANG
            (MaCTDH, MaDH, MaSP, SoLuong, DonGia, ThanhTien)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $ma_ct,
            $ma_dh,
            $item['masp'],
            $item['soluong'],
            $item['giaban'],
            $item['giaban'] * $item['soluong']
        ]);
        // trừ kho
        $stmt = $pdo->prepare("
            UPDATE SANPHAM SET SoLuongTon = SoLuongTon - ?
            WHERE MaSP = ?
        ");
        $stmt->execute([$item['soluong'], $item['masp']]);
    }
    $pdo->commit();
    // 5️⃣ XÓA GIỎ HÀNG SAU KHI ĐẶT THÀNH CÔNG
    unset($_SESSION['cart']);
    header("Location: giohang.php?success=1");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Lỗi đặt hàng");
}
