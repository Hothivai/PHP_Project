<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
require_once('../model/connect.php'); 

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['id-user'])) {
    // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id-user']; 

// 2. TRUY VẤN THÔNG TIN NGƯỜI DÙNG
// Lưu ý: Đảm bảo các cột này (fullname, email, address, phone) tồn tại trong bảng users
$sql = "SELECT fullname, username, email, address, phone, created FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user_info = mysqli_fetch_assoc($result);

// Khởi tạo biến thông báo (nếu có cập nhật)
$message = '';
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $message = '<div class="alert alert-success">Cập nhật thông tin thành công!</div>';
}
if (isset($_GET['pass']) && $_GET['pass'] == 'success') {
    $message = '<div class="alert alert-success">Đổi mật khẩu thành công!</div>';
}


// XỬ LÝ CẬP NHẬT THÔNG TIN CÁ NHÂN 
if (isset($_POST['update_profile'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Thực hiện cập nhật
    $update_sql = "UPDATE users SET fullname = '$fullname', email = '$email', phone = '$phone', address = '$address' WHERE id = '$user_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        // Cập nhật session username nếu tên được thay đổi (tùy chọn)
        if ($user_info['username'] !== $fullname) {
             $_SESSION['username'] = $fullname;
        }
        header('Location: profile.php?status=success');
        exit();
    } else {
        $message = '<div class="alert alert-danger">Lỗi cập nhật: ' . mysqli_error($conn) . '</div>';
    }
}

// Lấy lại thông tin mới nhất sau khi xử lý (hoặc từ đầu)
$sql = "SELECT fullname, username, email, address, phone, created FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user_info = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thông Tin Tài Khoản - <?php echo $user_info['username']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* CSS cho trang Profile */
        .profile-sidebar { background-color: #f8f8f8; padding: 15px; border-radius: 5px; }
        .profile-sidebar ul { list-style: none; padding: 0; }
        .profile-sidebar ul li a { display: block; padding: 10px 0; border-bottom: 1px solid #eee; color: #333; }
        .profile-sidebar ul li a:hover { color: #d9534f; text-decoration: none; }
        .profile-content { padding: 0 15px; }

        /* CSS bổ sung để định vị Header Tối Giản */
        header .title { margin-bottom: 0; }
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
                        <li><a href="profile.php" style="font-weight: bold;"><i class="fa fa-address-card"></i> Thông tin tài khoản</a></li>
                        <li><a href="change_password.php"><i class="fa fa-key"></i> Đổi mật khẩu</a></li>
                        <li><a href="order_history.php"><i class="fa fa-list-alt"></i> Lịch sử đặt phòng</a></li>
                        <li><a href="logout.php" style="color: #d9534f;"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="profile-content">
                    <h3>Cập Nhật Thông Tin Cá Nhân</h3>
                    <?php echo $message; ?>
                    <form method="POST" action="profile.php">
                        <div class="form-group">
                            <label>Tên đăng nhập :</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user_info['username']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="fullname">Họ và Tên:</label>
                            <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($user_info['fullname']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_info['email']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại:</label>
                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user_info['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ:</label>
                            <textarea class="form-control" name="address"><?php echo htmlspecialchars($user_info['address']); ?></textarea>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary"><i class="fa fa-save"></i> Lưu Thay Đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>