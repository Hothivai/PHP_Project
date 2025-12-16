<?php
session_start();
require_once('model/connect.php');

// Kiểm tra có id sản phẩm hay không
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Lấy id sản phẩm
$id = intval($_GET['id']);

// Query lấy thông tin sản phẩm
$sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

// Nếu không có sản phẩm → quay về trang chủ
if (!$product) {
    header("Location: index.php");
    exit();
}

// Nếu chưa có giỏ hàng thì tạo giỏ
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Nếu sản phẩm đã có trong giỏ → tăng số lượng
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity'] += 1;
} else {
    // Nếu chưa có → thêm sản phẩm mới
    $_SESSION['cart'][$id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'image' => $product['image'],
        'price' => $product['price'],
        'quantity' => 1
    ];
}

// Quay lại trang giỏ hàng
header("Location: view-cart.php");
exit();

?>