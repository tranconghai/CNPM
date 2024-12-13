<?php
    session_start();
    session_unset();
    session_destroy();
    // Sử dụng JavaScript để hiển thị thông báo và sau đó chuyển hướng người dùng
    echo "<script>alert('Bạn đã đăng xuất thành công!'); window.location.href = 'index.php';</script>";
    exit();
?>
