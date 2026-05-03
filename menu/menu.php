<nav class="main-nav">
    <ul>
        <li><a href="trangchu.php">Trang chủ</a></li>
        <li><a href="https://maps.app.goo.gl/xQG4D8i63qpx1gjW7" target="_blank">Bản đồ</a></li>
        <li><a href="https://www.pcworld.com/" target="_blank">Tin tức công nghệ</a></li>
        <?php
            $soLuong = 0;
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $soLuong += $item['soluong'] ?? 0;
                }
            }
            ?>
        <a href="giohang.php" class="cart-link">
            🛒 Giỏ hàng (<?php echo $soLuong; ?>)
        </a>
    </ul>
</nav>