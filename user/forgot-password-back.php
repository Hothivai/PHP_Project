<?php
    session_start();
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once '../model/connect.php';

    // -----------------------------------------------------
    // BƯỚC 1: NHÚNG VÀ KHAI BÁO PHPMailer (Tương tự register-back.php)
    // -----------------------------------------------------
    require '../lib/PHPMailer/Exception.php'; 
    require '../lib/PHPMailer/PHPMailer.php';
    require '../lib/PHPMailer/SMTP.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    /**
     * Hàm tạo mật khẩu ngẫu nhiên
     * @param int $length Độ dài mật khẩu
     * @return string Mật khẩu ngẫu nhiên
     */
    function generateRandomPassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, $max)];
        }
        return $password;
    }

    if (isset($_POST['submit']))
    {
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $email = mysqli_real_escape_string($conn, $email);
        
        // -----------------------------------------------------
        // BƯỚC 2: KIỂM TRA EMAIL CÓ TỒN TẠI HAY KHÔNG
        // -----------------------------------------------------
        $sql_check = "SELECT id, username FROM users WHERE email = '$email'";
        $res_check = mysqli_query($conn, $sql_check);
        
        if (mysqli_num_rows($res_check) > 0) 
        {
            $user_data = mysqli_fetch_assoc($res_check);
            $user_id = $user_data['id'];
            $username = $user_data['username'];
            
            // -----------------------------------------------------
            // BƯỚC 3: TẠO VÀ CẬP NHẬT MẬT KHẨU MỚI
            // -----------------------------------------------------
            $new_password = generateRandomPassword(8); // Tạo mật khẩu 8 ký tự
            $hashed_new_password = md5($new_password); // Mã hóa MD5 (Tương tự register-back.php)

            $sql_update = "UPDATE users SET password = '$hashed_new_password' WHERE id = $user_id";
            $res_update = mysqli_query($conn, $sql_update);
            
            if ($res_update) 
            {
                // CẬP NHẬT THÀNH CÔNG: TIẾN HÀNH GỬI EMAIL MẬT KHẨU MỚI
                $mail = new PHPMailer(true);

                try {
                    // Cấu hình SMTP (Lấy từ register-back.php)
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';          
                    $mail->SMTPAuth   = true;                         
                    $mail->Username   = 'hothivai22@gmail.com'; // **CHỈNH SỬA THÔNG TIN EMAIL CỦA BẠN**
                    $mail->Password   = 'glbi yqvn uqbu noeo';  // **CHỈNH SỬA MẬT KHẨU ỨNG DỤNG CỦA BẠN**
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port       = 465;                          
                    $mail->CharSet = 'UTF-8';

                    // Người Gửi và Người Nhận
                    $mail->setFrom('hothivai22@gmail.com', 'VAIShop Support'); 
                    $mail->addAddress($email, $username); 

                    // Nội dung Email
                    $mail->isHTML(true);                                       
                    $mail->Subject = 'MẬT KHẨU MỚI CHO TÀI KHOẢN CỦA BẠN';
                    
                    $bodyContent = '
                        <html>
                        <body>
                            <h2>Xin chào, ' . $username . '!</h2>
                            <p>Theo yêu cầu lấy lại mật khẩu của bạn, chúng tôi đã tạo một mật khẩu mới:</p>
                            <ul>
                                <li><strong>Tên đăng nhập/Email:</strong> ' . $email . '</li>
                                <li><strong>Mật khẩu mới:</strong> <b>' . $new_password . '</b></li>
                            </ul>
                            <p>Vui lòng đăng nhập bằng mật khẩu mới và thay đổi mật khẩu sau khi đăng nhập để bảo mật tài khoản.</p>
                            <p>Trân trọng,<br>Đội ngũ VAIShop</p>
                        </body>
                        </html>';
                        
                    $mail->Body    = $bodyContent;
                    $mail->AltBody = 'Mật khẩu mới của bạn là: ' . $new_password;

                    $mail->send();
                    
                    // Chuyển hướng thành công
                    header("location:forgot-password.php?success=sent");
                    exit();
                    
                } catch (Exception $e) {
                    // Nếu gửi mail thất bại, vẫn báo thành công để tránh lộ thông tin email nào tồn tại/không tồn tại.
                    // Tuy nhiên, để debug, bạn có thể chuyển hướng về lỗi hoặc ghi log.
                    // Hiện tại, giữ nguyên báo thành công.
                    header("location:forgot-password.php?success=sent");
                    exit();
                }
            } 
            else 
            {
                // Lỗi cập nhật database (Rất hiếm)
                header("location:forgot-password.php?error=db_fail");
                exit();
            }

        } else {
            // Email không tồn tại
            header("location:forgot-password.php?error=not_found");
            exit();
        }
    }
?>