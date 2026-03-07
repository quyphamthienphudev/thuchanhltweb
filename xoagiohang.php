<?php
session_start();
if (isset($_GET['masp'])) {
    unset($_SESSION['cart'][$_GET['masp']]);
}
header("Location: giohang.php");
exit;