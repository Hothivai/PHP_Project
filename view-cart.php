<?php
session_start();

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Kiểm tra giỏ hàng rỗng
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: index.php");
        exit();
    }
    
    $selected_total = 0;
    $selected_count = 0;
    $selected_ids = []; 
    
    // 2. TÍNH TỔNG CHO TẤT CẢ SẢN PHẨM TRONG GIỎ (vì đã xóa checkbox)
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $selected_total += $item['price'] * $item['quantity'];
        $selected_count++;
        $selected_ids[] = $product_id; 
    }
    
    // 3. Nếu tổng tiền > 0 (luôn đúng nếu giỏ không rỗng) thì chuyển hướng
    if ($selected_total > 0) {
        // Lưu vào session để chuyển sang trang thanh toán
        $_SESSION['selected_total'] = $selected_total;
        $_SESSION['selected_count'] = $selected_count;
        $_SESSION['selected_ids'] = $selected_ids; 
        
        // Chuyển đến trang thanh toán
        header("Location: checkout.php");
        exit();
    } else {
        // Trường hợp lỗi hy hữu (total = 0 dù giỏ không rỗng), chuyển về giỏ hàng
        echo "<script>alert('Giỏ hàng của bạn đang rỗng hoặc có lỗi tính toán!'); window.location.href='view-cart.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .cart-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Giỏ hàng của bạn</h2>
    <hr>

    <?php if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0): ?>
        <h4 class="text-center text-danger">Giỏ hàng trống!</h4>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-primary">Mua hàng</a> 
        </div>
    <?php else: ?>
    
    <form method="post" action="view-cart.php">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $total_all = 0;
                foreach ($_SESSION['cart'] as $id => $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_all += $subtotal;
            ?>
                <tr class="text-center">
                    <td><img src="<?php echo $item['image']; ?>" class="cart-img"></td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price']); ?> đ</td>
                    <td>
                        <a href="update-qty.php?id=<?php echo $id; ?>&action=minus" 
                           class="btn btn-sm btn-secondary">-</a>
                        
                        <span class="mx-2"><?php echo $item['quantity']; ?></span>
                        
                        <a href="update-qty.php?id=<?php echo $id; ?>&action=plus" 
                           class="btn btn-sm btn-secondary">+</a>
                    </td>
                    <td><?php echo number_format($subtotal); ?> đ</td>
                    <td>
                        <a href="remove.php?id=<?php echo $id; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Xóa sản phẩm?')">
                            Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-6">
                <a href="index.php" class="btn btn-primary">Tiếp tục mua hàng</a>
                <a href="clear-cart.php" class="btn btn-warning" 
                   onclick="return confirm('Xóa tất cả sản phẩm?')">Xóa tất cả</a>
            </div>
            <div class="col-md-6 text-right">
                <h4>Tổng tiền trong giỏ: <strong class="text-danger"><?php echo number_format($total_all); ?> đ</strong></h4>
                <button type="submit" class="btn btn-success btn-lg">Tiến hành Thanh toán</button>
            </div>
        </div>
    </form>

    <?php endif; ?>
</div>

</body>
</html>