<?php
    session_start();
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once '../model/connect.php';

    // -----------------------------------------------------
    // BƯỚC 1: NHÚNG VÀ KHAI BÁO PHPMailer
    // BẠN CẦN CHỈNH SỬA ĐƯỜNG DẪN NÀY NẾU KHÁC lib/PHPMailer/
    // -----------------------------------------------------
    require '../lib/PHPMailer/Exception.php'; 
    require '../lib/PHPMailer/PHPMailer.php';
    require '../lib/PHPMailer/SMTP.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['submit']))
    {
        // Lấy và làm sạch dữ liệu
        $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $address = isset($_POST['address']) ? $_POST['address'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';

        // Bảo mật cơ bản cho dữ liệu SQL
        $fullname = mysqli_real_escape_string($conn, $fullname);
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $address = mysqli_real_escape_string($conn, $address);
        
        
        // -----------------------------------------------------
        // BƯỚC 2: THỰC HIỆN INSERT VÀO DATABASE
        // -----------------------------------------------------
        $sql = "INSERT INTO users (fullname, username, password, email, phone, address, role)
                VALUES ('$fullname', '$username', md5('$password'), '$email', '$phone', '$address', 0)";
        $res = mysqli_query($conn,$sql);

        if ($res) 
        {
            // INSERT THÀNH CÔNG: TIẾN HÀNH GỬI EMAIL
            $mail = new PHPMailer(true);

            try {
                
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';          
                $mail->SMTPAuth   = true;                         
                $mail->Username   = 'hothivai22@gmail.com'; 
                $mail->Password   = 'glbi yqvn uqbu noeo';  
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;                          
                $mail->CharSet = 'UTF-8';

                // Người Gửi và Người Nhận
                $mail->setFrom('hothivai22@gmail.com', 'VAIShop Support'); // Phải khớp với Username
                $mail->addAddress($email, $username); // Gửi đến email và tên người dùng vừa đăng ký

                // Nội dung Email
                $mail->isHTML(true);                                       
                $mail->Subject = 'CHÚC MỪNG BẠN ĐĂNG KÝ TÀI KHOẢN THÀNH CÔNG!';
                
                $bodyContent = '
                    <html>
                    <body>
                        <h2>Xin chào, ' . $username . '!</h2>
                        <p>Cảm ơn bạn đã đăng ký tài khoản thành công tại VAIShop.</p>
                        <p>Bạn có thể sử dụng thông tin sau để đăng nhập:</p>
                        <ul>
                            <li><strong>Tên đăng nhập:</strong> ' . $username . '</li>
                            <li><strong>Email:</strong> ' . $email . '</li>
                        </ul>
                        <p>Trân trọng,<br>Đội ngũ VAIShop</p>
                    </body>
                    </html>';
                    
                $mail->Body    = $bodyContent;
                $mail->AltBody = 'Bạn đã đăng ký tài khoản thành công. Vui lòng đăng nhập để mua sắm.';

                $mail->send();
               
                
            } catch (Exception $e) {
                // Nếu gửi mail thất bại, hệ thống vẫn chấp nhận đăng ký thành công. 
                // Có thể ghi log lỗi $mail->ErrorInfo để debug sau này.
                // Ví dụ: file_put_contents('mail_log.txt', date('Y-m-d H:i:s') . " - Gửi mail thất bại: " . $mail->ErrorInfo . "\n", FILE_APPEND);
            }
            
            // Chuyển hướng người dùng đến trang đăng nhập
            header("location:login.php?rs=success");
            exit();
        }
        else 
        {
            // Đăng ký thất bại
            header("location:login.php?rf=fail");
            exit();
        }
    }
?>