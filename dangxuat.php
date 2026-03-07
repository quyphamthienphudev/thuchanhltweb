<?php
// Bắt đầu hoặc khôi phục phiên làm việc hiện tại
// BẮT BUỘC phải có để thao tác với $_SESSION
session_start();

// Xóa toàn bộ biến session đang tồn tại
// Ví dụ: $_SESSION['loggedin'], $_SESSION['hoten'], $_SESSION['vaitro'], ...
session_unset();

// Hủy hoàn toàn phiên làm việc (session) trên server
// Sau dòng này: session ID không còn hợp lệ
session_destroy();

// Chuyển hướng người dùng về trang chủ sau khi đăng xuất
// Tránh việc người dùng quay lại trang cũ bằng nút Back
header("Location: index.php");

// Dừng toàn bộ quá trình xử lý script
// Bắt buộc để tránh code phía dưới chạy thêm
exit;
?>
