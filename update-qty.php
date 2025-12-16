<?php
session_start();

// 1. Lấy ID và Action từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$message = 'Đã cập nhật giỏ hàng.';

// 2. Kiểm tra tính hợp lệ và giỏ hàng
if ($id > 0 && isset($_SESSION['cart'][$id])) {
    
    if ($action == 'plus') {
        // --- Xử lý Tăng Số Lượng ---
        $_SESSION['cart'][$id]['quantity'] += 1;
        
    } elseif ($action == 'minus') {
        // --- Xử lý Giảm Số Lượng ---
        
        // Giảm số lượng, đảm bảo không nhỏ hơn 1
        if ($_SESSION['cart'][$id]['quantity'] > 1) {
            $_SESSION['cart'][$id]['quantity'] -= 1;
        } else {
            // Nếu số lượng bằng 1 và bấm giảm -> Xóa sản phẩm
            $name = $_SESSION['cart'][$id]['name'];
            unset($_SESSION['cart'][$id]);
            $message = "Đã xóa sản phẩm **" . htmlspecialchars($name) . "** khỏi giỏ hàng!";
        }
    }
    
    // 3. Lưu thông báo và chuyển hướng
    $_SESSION['message'] = $message;
    
} else {
    // Xử lý khi ID không hợp lệ hoặc sản phẩm không có trong giỏ
    $_SESSION['message'] = 'Lỗi: Không thể tìm thấy sản phẩm để cập nhật.';
}

// 4. Chuyển hướng về trang giỏ hàng
header("Location: view-cart.php");
exit();
?>