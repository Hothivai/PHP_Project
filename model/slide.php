<?php
    require_once('connect.php');
    error_reporting(2);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slideshow</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .carousel-inner img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        .carousel-control {
            background: none !important;
        }
        .carousel-indicators {
            bottom: 10px;
        }
        .container {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="col-sm-12 col-md-12">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php
                    $sql_slides = "SELECT image FROM slides WHERE status=1";
                    $result_slides = mysqli_query($conn, $sql_slides);
                    
                    if ($result_slides && mysqli_num_rows($result_slides) > 0) {
                        $count = mysqli_num_rows($result_slides);
                        for ($i = 0; $i < $count; $i++) {
                            $active = ($i == 0) ? 'class="active"' : '';
                            echo '<li data-target="#myCarousel" data-slide-to="' . $i . '" ' . $active . '></li>';
                        }
                    }
                    ?>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php
                    $sql_slides = "SELECT image FROM slides WHERE status=1";
                    $result_slides = mysqli_query($conn, $sql_slides);

                    if ($result_slides && mysqli_num_rows($result_slides) > 0) {
                        $i = 0;
                        while ($kq = mysqli_fetch_assoc($result_slides)) {
                            $i++;
                            $activeClass = ($i == 1) ? 'active' : '';
                    ?>
                    <div class="item <?php echo $activeClass; ?>">
                        <img src="<?php echo $kq['image']; ?>" alt="Slideshow <?php echo $i; ?>">
                    </div>
                    <?php 
                        }
                    } else {
                        // Hiển thị ảnh mặc định nếu không có ảnh trong database
                    ?>
                    <div class="item active">
                        <img src="https://via.placeholder.com/1200x400/007bff/ffffff?text=No+Slide+Image" alt="Default slideshow">
                    </div>
                    <?php } ?>
                </div>

                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function() {
        // Khởi tạo carousel
        $('#myCarousel').carousel({
            interval: 3000,
            pause: "hover",
            wrap: true
        });
    });
    </script>
</body>
</html>