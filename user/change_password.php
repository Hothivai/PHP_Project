<?php
session_start();
// Loại bỏ require_once('../model/header.php') ở đây
require_once('../model/connect.php'); 

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['id-user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id-user']; 
$error = '';
$success = '';

if (isset($_POST['change_password'])) {
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // 2. XÁC THỰC MẬT KHẨU MỚI
    if ($new_password !== $confirm_password) {
        $error = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
    } elseif (strlen($new_password) < 6) {
        $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } else {
        // 3. XÁC THỰC MẬT KHẨU CŨ (Sử dụng MD5 như trong login-back.php)
        $old_password_md5 = md5($old_password);
        
        $sql_check = "SELECT password FROM users WHERE id = '$user_id'";
        $result_check = mysqli_query($conn, $sql_check);
        $user_data = mysqli_fetch_assoc($result_check);
        
        if ($user_data && $user_data['password'] === $old_password_md5) {
            // Mật khẩu cũ khớp -> Tiến hành cập nhật
            $new_password_md5 = md5($new_password);
            
            $sql_update = "UPDATE users SET password = '$new_password_md5' WHERE id = '$user_id'";
            
            if (mysqli_query($conn, $sql_update)) {
                // Đổi mật khẩu thành công và chuyển hướng để tránh gửi lại form
                header('Location: profile.php?pass=success');
                exit();
            } else {
                $error = 'Lỗi trong quá trình cập nhật mật khẩu: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Mật khẩu cũ không chính xác.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Đổi Mật Khẩu</title>
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
                    <img src="../images/logo.png" width="260px;" height="180px;"> 
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
                        <li><a href="change_password.php" style="font-weight: bold;"><i class="fa fa-key"></i> Đổi mật khẩu</a></li>
                        <li><a href="order_history.php"><i class="fa fa-list-alt"></i> Đơn hàng của tôi</a></li>
                        <li><a href="logout.php" style="color: #d9534f;"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="profile-content">
                    <h3>Đổi Mật Khẩu</h3>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="change_password.php">
                        <div class="form-group">
                            <label for="old_password">Mật khẩu cũ:</label>
                            <input type="password" class="form-control" name="old_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Mật khẩu mới:</label>
                            <input type="password" class="form-control" name="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-warning"><i class="fa fa-refresh"></i> Đổi Mật Khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>