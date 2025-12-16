<?php
session_start();

// Xóa biến session 'cart'
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}

// Xóa các biến session tạm thời của checkout
if (isset($_SESSION['selected_total'])) {
    unset($_SESSION['selected_total']);
}
if (isset($_SESSION['selected_count'])) {
    unset($_SESSION['selected_count']);
}
if (isset($_SESSION['selected_ids'])) {
    unset($_SESSION['selected_ids']);
}

// Quay về trang giỏ hàng
header("Location: view-cart.php");
exit();
?>