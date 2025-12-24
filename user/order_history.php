<?php
session_start();
require_once('../model/connect.php'); 

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['id-user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id-user']; 
$username = $_SESSION['username'];

$sql = "SELECT id, total, date_order, status FROM orders WHERE user_id = '$user_id' ORDER BY date_order DESC";
$result = mysqli_query($conn, $sql);

// Hàm hiển thị trạng thái bằng chữ
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
    <title>Phòng Của Tôi</title>
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
                        <li><a href="order_history.php" style="font-weight: bold;"><i class="fa fa-list-alt"></i> Phòng Của Tôi</a></li>
                        <li><a href="logout.php" style="color: #d9534f;"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="profile-content">
                    <h3>Lịch Sử Đặt Phòng</h3>
                    
                    <?php if (mysqli_num_rows($result) > 0): ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Mã Đặt Phòng</th>
                                <th>Ngày Đặt / Check-in</th>
                                <th>Tổng Thanh toán</th>
                                <th>Tình trạng</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo date('d/m/Y H:i:s', strtotime($order['date_order'])); ?></td>
                                <td><?php echo number_format($order['total'], 0, ',', '.'); ?> đ</td>
                                <td><?php echo get_status_text($order['status']); ?></td>
                                <td>
                                    <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-success">Xem Chi Tiết</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <div class="alert alert-info">Bạn chưa đặt phòng nào.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>