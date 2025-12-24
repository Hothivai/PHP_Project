<?php
    // Kết nối database
    require_once('connect.php');
    error_reporting(E_ALL); // Bật báo lỗi để dễ kiểm tra

    // Lấy dữ liệu slide có status = 1 từ bảng slides
    $sql_slides = "SELECT image FROM slides WHERE status=1";
    $result_slides = mysqli_query($conn, $sql_slides);
    
    $slides = [];
    if ($result_slides && mysqli_num_rows($result_slides) > 0) {
        while ($row = mysqli_fetch_assoc($result_slides)) {
            $slides[] = $row;
        }
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V Hotel Slideshow</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container-fluid">
    <div id="vHotelCarousel" class="carousel slide" data-ride="carousel">
        
        <ol class="carousel-indicators">
            <?php if (count($slides) > 0): ?>
                <?php foreach ($slides as $index => $slide): ?>
                    <li data-target="#vHotelCarousel" data-slide-to="<?php echo $index; ?>" 
                        class="<?php echo ($index == 0) ? 'active' : ''; ?>"></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li data-target="#vHotelCarousel" data-slide-to="0" class="active"></li>
            <?php endif; ?>
        </ol>

        <div class="carousel-inner">
            <?php if (count($slides) > 0): ?>
                <?php foreach ($slides as $index => $slide): ?>
                    <div class="item <?php echo ($index == 0) ? 'active' : ''; ?>">
                        <?php 
                            // Xử lý đường dẫn ảnh: xóa ../ để đúng cấu trúc thư mục web
                            $path = str_replace('../', '', $slide['image']); 
                        ?>
                        <img src="<?php echo $path; ?>" alt="V Hotel Slide <?php echo $index + 1; ?>">
                        
                        <div class="carousel-caption">
                            <h3 style="text-shadow: 2px 2px 10px #000;">Chào mừng đến với VAI Hotel</h3>
                            <p style="text-shadow: 1px 1px 5px #000;">Trải nghiệm nghỉ dưỡng đích thực</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="item active">
                    <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1920" alt="Default Slide">
                </div>
            <?php endif; ?>
        </div>

        <a class="left carousel-control" href="#vHotelCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Trước</span>
        </a>
        <a class="right carousel-control" href="#vHotelCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Sau</span>
        </a>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#vHotelCarousel').carousel({
            interval: 4000, // Chuyển slide sau 4 giây
            pause: "hover"  // Dừng lại khi di chuột vào
        });
    });
</script>

</body>
</html>