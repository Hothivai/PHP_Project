<?php
session_start();

// Xử lý khi form được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        header("Location: index.php");
        exit();
    }
    
    $selected_total = 0;
    $selected_count = 0;
    $selected_ids = []; 
    
    foreach ($_SESSION['cart'] as $product_id => $item) {
        // Cố định số lượng là 1 vì mỗi phòng là duy nhất
        $selected_total += $item['price'] * 1;
        $selected_count++;
        $selected_ids[] = $product_id; 
    }
    
    if ($selected_total > 0) {
        $_SESSION['selected_total'] = $selected_total;
        $_SESSION['selected_count'] = $selected_count;
        $_SESSION['selected_ids'] = $selected_ids; 
        header("Location: checkout.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn đặt phòng - VAI Luxury Hotel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
    <h2 class="text-center">DANH SÁCH PHÒNG ĐANG ĐẶT</h2>
    <hr>

    <?php if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0): ?>
        <h4 class="text-center text-danger">Bạn chưa chọn phòng nào!</h4>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary">Quay lại chọn phòng</a> 
        </div>
    <?php else: ?>
    
    <form method="post" action="view-cart.php">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>Hình ảnh</th>
                    <th>Tên phòng</th>
                    <th>Giá mỗi đêm</th>
                    <th>Thành tiền</th>
                    <th>Gỡ bỏ</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $total_all = 0;
                foreach ($_SESSION['cart'] as $id => $item):
                    // Số lượng mặc định là 1 cho khách sạn
                    $subtotal = $item['price'] * 1; 
                    $total_all += $subtotal;
            ?>
                <tr class="text-center">
                    <td><img src="<?php echo $item['image']; ?>" width="100"></td>
                    <td><strong><?php echo $item['name']; ?></strong></td>
                    <td><?php echo number_format($item['price']); ?> đ</td>
                    
                    <td><strong><?php echo number_format($subtotal); ?> đ</strong></td>
                    <td>
                        <a href="remove.php?id=<?php echo $id; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Xóa lựa chọn này?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-6">
                <a href="index.php" class="btn btn-default">Tiếp tục chọn phòng</a>
            </div>
            <div class="col-md-6 text-right">
                <h4>Tổng tiền thanh toán: <strong class="text-danger"><?php echo number_format($total_all); ?> đ</strong></h4>
                <br>
                <button type="submit" class="btn btn-success btn-lg">TIẾN HÀNH ĐẶT PHÒNG</button>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

</body>
</html>