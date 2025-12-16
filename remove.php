<?php
session_start();

// Nếu không có id → quay lại giỏ hàng
if (!isset($_GET['id'])) {
    header("Location: view-cart.php");
    exit();
}

$id = intval($_GET['id']);

// Nếu giỏ hàng tồn tại và có sản phẩm cần xóa
if (isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

// Nếu giỏ hàng rỗng sau khi xóa → xóa luôn biến cart
if (empty($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Quay về trang giỏ hàng
header("Location: view-cart.php");
exit();
?>

