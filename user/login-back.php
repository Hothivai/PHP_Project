<?php
    session_start();
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once('../model/connect.php'); 

    if (isset($_POST['submit']))
    {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        
        $user_data = false;
        $role = 0; // Khởi tạo vai trò mặc định là user thường

        // -----------------------------------------------------
        // BƯỚC 1: KIỂM TRA TÀI KHOẢN ADMIN TRONG BẢNG 'admin' (Plain Text - Rất kém bảo mật!)
        // -----------------------------------------------------
        $sql_admin = "SELECT id, username FROM admin WHERE username = '$username' AND password = '$password'";
        $res_admin = mysqli_query($conn, $sql_admin);
        
        if (mysqli_num_rows($res_admin) > 0) {
            $user_data = mysqli_fetch_assoc($res_admin);
            $role = 1; // Thiết lập vai trò Admin
        }

        // -----------------------------------------------------
        // BƯỚC 2: KIỂM TRA TÀI KHOẢN NGƯỜI DÙNG/STAFF TRONG BẢNG 'users' (MD5)
        // LƯU Ý: CHỈ KIỂM TRA NẾU CHƯA ĐĂNG NHẬP BẰNG ADMIN
        // -----------------------------------------------------
        if (!$user_data) {
            $sql_users = "SELECT id, username, role FROM users WHERE username = '$username' AND password = md5('$password')";
            $res_users = mysqli_query($conn, $sql_users);

            if (mysqli_num_rows($res_users) > 0) {
                $user_data = mysqli_fetch_assoc($res_users);
                // Lấy vai trò từ database (0 hoặc 1, 2,...)
                $role = $user_data['role']; 
            }
        }


        // -----------------------------------------------------
        // BƯỚC 3: XỬ LÝ KẾT QUẢ ĐĂNG NHẬP
        // -----------------------------------------------------
        if ($user_data) 
        {
            // Khởi tạo Session
            $_SESSION['username'] = $user_data['username']; 
            $_SESSION['id-user'] = $user_data['id'];
            $_SESSION['user_role'] = $role; // Gán role đã xác định ở B1 hoặc B2

            // Kiểm tra Vai trò (Role)
            if ($role > 0) // Kiểm tra nếu là Admin/Staff (role > 0)
            {
                // ADMIN/STAFF LOGIN: Chuyển hướng đến trang Admin/product-list.php
                header("location:../admin/product-list.php"); 
                exit();
            } 
            else 
            {
                // USER THƯỜNG LOGIN: Chuyển hướng đến trang chủ
                header("location:../index.php?ls=success");
                exit();
            }

        } else {
            // Đăng nhập thất bại
            $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không hợp lệ!';
            
            header("location:../user/login.php?error=wrong");
            exit();
        }
    }
?>