<?php
session_start();
require_once('model/connect.php'); 

// Kiểm tra nếu không có sản phẩm được chọn hoặc không có tổng tiền
if (!isset($_SESSION['selected_total']) || $_SESSION['selected_total'] == 0 || !isset($_SESSION['selected_count']) || $_SESSION['selected_count'] == 0 || !isset($_SESSION['selected_ids'])) {
    echo "<script>alert('Vui lòng chọn sản phẩm để thanh toán!'); window.location.href='view-cart.php';</script>";
    exit();
}

$selected_total = $_SESSION['selected_total'];
$selected_ids = $_SESSION['selected_ids'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $total_order = floatval($selected_total);

    $user_id = isset($_SESSION['id-user']) ? intval($_SESSION['id-user']) : 0;
    
    $date_order = date('Y-m-d H:i:s');
    $status = 0;
    $sql_order = "INSERT INTO orders (total, date_order, status, user_id) 
                  VALUES (?, ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    
    // BIND PARAMS: d(total), s(date), i(status), i(user_id)
    $stmt_order->bind_param("dsii", $total_order, $date_order, $status, $user_id);


    if ($stmt_order->execute()) {
        $order_id = $conn->insert_id; // Lấy ID của đơn hàng vừa tạo
        $stmt_order->close();

        // 2. Lưu chi tiết sản phẩm vào bảng `product_order`
        $errors = false;
        
        $sql_detail = "INSERT INTO product_order (product_id, order_id, quantity) VALUES (?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);
        $stmt_detail->bind_param("iii", $product_id_int, $order_id_int, $quantity_int);
        $order_id_int = $order_id; 

        foreach ($selected_ids as $product_id) {
            $product_id_int = intval($product_id);
            $item = $_SESSION['cart'][$product_id];
            $quantity_int = intval($item['quantity']);

            if (!$stmt_detail->execute()) {
                $errors = true;
            }
            
            // 3. Xóa sản phẩm đã thanh toán khỏi Session Cart
            unset($_SESSION['cart'][$product_id]);
        }
        
        $stmt_detail->close();

        // 4. Xóa các biến Session tạm thời
        unset($_SESSION['selected_total']);
        unset($_SESSION['selected_count']);
        unset($_SESSION['selected_ids']);

        // 5. CHUYỂN HƯỚNG THẲNG ĐẾN TRANG CHI TIẾT ĐƠN HÀNG
        if (!$errors) {
            if (empty($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            
            // Lưu thông tin khách hàng vào Session TẠM THỜI
            $_SESSION['temp_customer_info'] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
            ];

            header("Location: order-detail.php?order_id=$order_id");
            exit();

        } else {
              echo "<script>alert('Đặt hàng thất bại do lỗi chi tiết đơn hàng. Vui lòng thử lại!'); window.location.href='view-cart.php';</script>";
        }
        exit();

    } else {
        $error_message = $conn->error;
        echo "<script>alert('Đặt hàng thất bại do lỗi hệ thống: " . $error_message . "'); window.location.href='checkout.php';</script>";
    }
}
// --- Kết thúc Xử lý POST ---
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .cart-img { width: 60px; height: 60px; object-fit: cover; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Thông tin Thanh toán</h2>
    <hr>
    
    <div class="row">
        <div class="col-md-6">
            <form method="post" action="checkout.php">
                <h3>Thông tin giao hàng</h3>
                <div class="form-group">
                    <label for="name">Họ và Tên (*)</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email (*)</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại (*)</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ giao hàng (*)</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-success btn-lg btn-block mt-4">Đặt hàng và Thanh toán</button>
            </form>
            <a href="view-cart.php" class="btn btn-default btn-block mt-2">Quay lại giỏ hàng</a>

        </div>
        
        <div class="col-md-6">
            <h3>Tóm tắt đơn hàng (<?php echo $_SESSION['selected_count']; ?> sản phẩm)</h3>
            <ul class="list-group">
                <?php 
                if (isset($_SESSION['selected_ids']) && isset($_SESSION['cart'])):
                    $current_total = 0;
                    foreach ($_SESSION['selected_ids'] as $id):
                        $item = $_SESSION['cart'][$id];
                        $subtotal = $item['price'] * $item['quantity'];
                        $current_total += $subtotal;
                ?>
                    <li class="list-group-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center;">
                            <img src="<?php echo $item['image']; ?>" class="cart-img" style="margin-right: 10px;">
                            <div>
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong> <br>
                                <small><?php echo number_format($item['price']); ?> đ x <?php echo $item['quantity']; ?></small>
                            </div>
                        </div>
                        <span style="font-weight: bold;"><?php echo number_format($subtotal); ?> đ</span>
                    </li>
                <?php
                    endforeach;
                endif;
                ?>
                <li class="list-group-item list-group-item-info" style="display: flex; justify-content: space-between; align-items: center; font-size: 1.2em;">
                    <strong>Tổng thanh toán:</strong>
                    <span style="color: red; font-weight: bold;">
                        <?php echo number_format($selected_total); ?> đ
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>