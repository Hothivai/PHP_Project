<?php
session_start();
require_once('../model/connect.php'); 

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['id-user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id-user']; 
$error = '';

if (isset($_POST['change_password'])) {
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // 2. XÁC THỰC DỮ LIỆU
    if ($new_password !== $confirm_password) {
        $error = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
    } elseif (strlen($new_password) < 6) {
        $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } else {
        // 3. KIỂM TRA MẬT KHẨU CŨ (MD5)
        $old_password_md5 = md5($old_password);
        $sql_check = "SELECT password FROM users WHERE id = '$user_id'";
        $result_check = mysqli_query($conn, $sql_check);
        $user_data = mysqli_fetch_assoc($result_check);
        
        if ($user_data && $user_data['password'] === $old_password_md5) {
            // 4. CẬP NHẬT
            $new_password_md5 = md5($new_password);
            $sql_update = "UPDATE users SET password = '$new_password_md5' WHERE id = '$user_id'";
            
            if (mysqli_query($conn, $sql_update)) {
                header('Location: profile.php?pass=success');
                exit();
            } else {
                $error = 'Lỗi hệ thống: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Mật khẩu cũ không chính xác.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu | VAI Luxury</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../css/style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    
    <header class="account-header">
        <div class="container">
            <div class="title">
                <a href="../index.php"> 
                         <img src="../images/logo.png" class="logo-account" alt="Logo" width="230"> 
                </a>
            </div>
            <div class="btn-home">
                <a href="../index.php" class="btn btn-primary">
                    <i class="fa fa-home"></i> Quay lại Trang Chủ
                </a>
            </div>
            <div class="clearfix"></div>
        </div>
    </header>

    <div class="container" style="margin: 40px auto;">
        <div class="row">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <h4><i class="fa fa-user-circle"></i> TÀI KHOẢN</h4>
                    <ul>
                        <ul>
                            <li>
                                <a href="profile.php">
                                    <i class="fa fa-address-card"></i> Thông tin tài khoản
                                </a>
                            </li>
                            <li>
                                <a href="change_password.php" class="menu-active">
                                    <i class="fa fa-key"></i> Đổi mật khẩu
                                </a>
                            </li>
                            <li>
                                <a href="order_history.php">
                                    <i class="fa fa-list-alt"></i> Đơn hàng đã đặt
                                </a>
                            </li>
                            <li>
                                <a href="logout.php" class="menu-logout">
                                    <i class="fa fa-sign-out"></i> Đăng xuất
                                </a>
                            </li>
                    </ul>
                        </ul>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="profile-content">
                    <h3><i class="fa fa-lock"></i> Thay Đổi Mật Khẩu</h3>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="change_password.php">
                        <div class="form-group">
                            <label>Mật khẩu hiện tại:</label>
                            <input type="password" class="form-control" name="old_password" required placeholder="Nhập mật khẩu cũ">
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới:</label>
                            <input type="password" class="form-control" name="new_password" required placeholder="Tối thiểu 6 ký tự">
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới:</label>
                            <input type="password" class="form-control" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
                        </div>
                        <hr>
                        <button type="submit" name="change_password" class="btn btn-warning btn-block">
                            <i class="fa fa-refresh"></i> XÁC NHẬN THAY ĐỔI
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>