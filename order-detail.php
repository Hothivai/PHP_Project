<?php
session_start();
// Không cần kết nối CSDL vì chỉ là trang thông báo

// Lấy ID đơn hàng từ URL để hiển thị
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : '???';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt phòng Thành công!</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .success-container {
            margin-top: 100px;
            text-align: center;
        }
        .success-box {
            background-color: #dff0d8; /* Màu nền xanh nhạt */
            border: 1px solid #d6e9c6; /* Màu viền xanh đậm */
            color: #3c763d; /* Màu chữ xanh đậm */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .success-icon {
            font-size: 80px;
            color: #5cb85c; /* Màu xanh lá cây đậm */
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="success-container">
        <div class="success-box">
            <span class="glyphicon glyphicon-ok-circle success-icon" aria-hidden="true"></span>
            
            <h1>ĐẶT PHÒNG THÀNH CÔNG!</h1>
            <p style="font-size: 1.2em;">
                Yêu cầu đặt phòng của bạn đã được xác nhận. Chúng tôi rất mong được đón tiếp bạn.
            </p>
            <p style="font-size: 1.5em; font-weight: bold;">
                Mã đặt phòng: <span class="text-primary">#<?php echo htmlspecialchars($order_id); ?></span>
            </p>
            
            <hr>
            
            <a href="index.php" class="btn btn-success btn-lg">
                <span class="glyphicon glyphicon-home"></span> Quay lại Trang chủ
            </a>
        </div>
    </div>
</div>

</body>
</html>