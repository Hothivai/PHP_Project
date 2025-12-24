<?php
session_start();
require_once('../model/connect.php'); 

// 1. KIỂM TRA ĐĂNG NHẬP VÀ ORDER ID
if (!isset($_SESSION['id-user']) || !isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: order_history.php');
    exit();
}

$user_id = $_SESSION['id-user']; 
$order_id = (int)$_GET['id'];

// 2. TRUY VẤN THÔNG TIN CHUNG CỦA ĐƠN HÀNG
// LƯU Ý: Đơn hàng phải thuộc về user đang đăng nhập
$sql_order = "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'";
$result_order = mysqli_query($conn, $sql_order);
$order_info = mysqli_fetch_assoc($result_order);

if (!$order_info) {
    // Đơn hàng không tồn tại hoặc không thuộc về user này
    echo "<script>alert('Đơn hàng không tồn tại hoặc không thuộc về bạn!'); window.location.href='order_history.php';</script>";
    exit();
}

// 3. TRUY VẤN CHI TIẾT SẢN PHẨM TRONG ĐƠN HÀNG (ĐÃ SỬA)
$sql_details = "
    SELECT 
        od.quantity, 
        p.price,                      
        p.name as product_name, 
        p.image 
    FROM 
        product_order od               
    JOIN 
        products p ON od.product_id = p.id 
    WHERE 
        od.order_id = '$order_id'
";
$result_details = mysqli_query($conn, $sql_details);

// Hàm hiển thị trạng thái (Tái sử dụng)
function get_status_text($status_code) {
    switch ($status_code) {
        case 0: return '<span class="label label-warning">Chờ xác nhận</span>';
        case 1: return '<span class="label label-info">Đang xử lý</span>';
        case 2: return '<span class="label label-primary">Đang giao hàng</span>';
        case 3: return '<span class="label label-success">Đã hoàn thành</span>';
        case 4: return '<span class="label label-danger">Đã hủy</span>';
        default: return '<span class="label label-default">Không rõ</span>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Đơn Hàng #<?php echo $order_id; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .profile-sidebar { background-color: #f8f8f8; padding: 15px; border-radius: 5px; }
        .profile-sidebar ul { list-style: none; padding: 0; }
        .profile-sidebar ul li a { display: block; padding: 10px 0; border-bottom: 1px solid #eee; color: #333; }
        .profile-sidebar ul li a:hover { color: #d9534f; text-decoration: none; }
        .profile-content { padding: 0 15px; }
        .product-img-small { width: 50px; height: 50px; object-fit: cover; margin-right: 10px; border: 1px solid #eee;}
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="title" style="float: left;">
                <a href="../index.php" title="MyLiShop"> 
                    <img src="../images/logo.png" width="230px;" height="auto;"> 
                </a>
            </div>
            
            <div style="float: right; padding-top: 50px;">
                <a href="../index.php" class="btn btn-primary" style="font-size: 16px;">
                    <i class="fa fa-home"></i> Quay lại Trang Chủ
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </header>
    <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        <div class="row">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <h4><i class="fa fa-user-circle"></i> Tài Khoản Của Tôi</h4>
                    <ul>
                        <li><a href="profile.php"><i class="fa fa-address-card"></i> Thông tin tài khoản</a></li>
                        <li><a href="change_password.php"><i class="fa fa-key"></i> Đổi mật khẩu</a></li>
                        <li><a href="order_history.php" style="font-weight: bold;"><i class="fa fa-list-alt"></i> Phòng của tôi</a></li>
                        <li><a href="logout.php" style="color: #d9534f;"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="profile-content">
                    <h3>Chi Tiết Đặt Phòng #<?php echo $order_id; ?></h3>
                    
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">Thông Tin Chung</h4>
                        </div>
                        <div class="panel-body">
                            <p><strong>Ngày đặt phòng:</strong> <?php echo date('d/m/Y H:i:s', strtotime($order_info['date_order'])); ?></p>
                            <p><strong>Tình trạng:</strong> <?php echo get_status_text($order_info['status']); ?></p>
                            <p><strong>Yêu cầu đặc biệt / Ghi chú:</strong> <?php echo htmlspecialchars($order_info['address'] ?? 'Chưa cập nhật'); ?></p> 
                            <p><strong>Tổng thanh toán:</strong> <span style="font-size: 1.2em; color: #d9534f;"><?php echo number_format($order_info['total'], 0, ',', '.'); ?> đ</span></p>
                        </div>
                    </div>

                    <h4>Dịch Vụ Đã Đặt</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Loại phòng / Gói dịch vụ</th>
                                <th>Đơn Giá</th>
                                <th>Số lượng phòng</th>
                                <th>Tổng chi phí dịch vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $tong_tien_hang = 0;
                            while ($detail = mysqli_fetch_assoc($result_details)): 
                                // ĐÃ SỬA: Dùng $detail['price'] thay vì $detail['price_at_order']
                                $sub_total = $detail['price'] * $detail['quantity']; 
                                $tong_tien_hang += $sub_total;
                            ?>
                            <tr>
                                <td>
                                    <img src="../<?php echo htmlspecialchars($detail['image']); ?>" class="product-img-small" alt="<?php echo htmlspecialchars($detail['product_name']); ?>">
                                    <?php echo htmlspecialchars($detail['product_name']); ?>
                                </td>
                                <td><?php echo number_format($detail['price'], 0, ',', '.'); ?> đ</td>
                                <td><?php echo $detail['quantity']; ?></td>
                                <td><?php echo number_format($sub_total, 0, ',', '.'); ?> đ</td>
                            </tr>
                            <?php endwhile; ?>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Tổng thanh toán:</strong></td>
                                <td><strong><?php echo number_format($tong_tien_hang, 0, ',', '.'); ?> đ</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="order_history.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Quay lại Danh sách đặt phòng</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>