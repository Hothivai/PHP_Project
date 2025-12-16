<?php
    // Bắt đầu session để có thể dùng trong header (nếu cần)
    session_start();
    // Kết nối CSDL
    require_once('../model/connect.php'); 

    $message = "";
    // Xử lý thông báo sau khi xử lý xong ở forgot-password-back.php
    if (isset($_GET['success'])) {
        $message = "<p style='color: green;'>Mật khẩu mới đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư!</p>";
    }
    if (isset($_GET['error']) && $_GET['error'] == 'not_found') {
        $message = "<p style='color: red;'>Email này không tồn tại trong hệ thống của chúng tôi.</p>";
    }
    if (isset($_GET['error']) && $_GET['error'] == 'fail') {
        $message = "<p style='color: red;'>Có lỗi xảy ra trong quá trình xử lý. Vui lòng thử lại.</p>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quên Mật Khẩu | MyLiShop Fashion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
    <script src='../js/wow.js'></script>
    <script type="text/javascript" src="../js/mylishop.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container-fluid header_top wow bounceIn" data-wow-delay="0.1s">
            <div class="col-sm-10 col-md-10">
                <div class="header_top_left"> <span><i class="fa fa-phone"></i></span> <span>01697 450 200 | 0926 055 983</span>&nbsp;&nbsp;&nbsp; <span><i class="fa fa-envelope-o" aria-hidden="true"></i></span> <span>admin@mylishop.com.vn</span> </div>
            </div>
            <div class="col-sm-2 col-md-2">
                <div class="header_top_right">
                    <a href="https://www.facebook.com/" target="_blank" title="facebook"><i class="fa fa-facebook"></i></a>
                    <a href="https://twitter.com/" target="_blank" title="twitter"><i class="fa fa-twitter"></i></a>
                    <a href="https://www.rss.com/" target="_blank" title="rss"><i class="fa fa-rss"></i></a>
                    <a href="https://www.youtube.com/" target="_blank" title="youtube"><i class="fa fa-youtube"></i></a>
                    <a href="https://plus.google.com/" target="_blank" title="google"><i class="fa fa-google-plus"></i></a>
                    <a href="https://linkedin.com/" target="_blank" title="linkedin"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>
            <div class="clear-fix"></div>
        </div>
        <div class="container">
            <div class="title">
                <a href="../index.php" title="MyLiShop"> <img src="../images/logo.png" width="260px;" height="180px;"> </a>
            </div>
            <div class="col-sm-12 col-md-12 account">
                <div class="row">
                    <?php
                        if(isset($_SESSION['username']))
                        {
                    ?>
                            <i class="fa fa-user fa-lg"></i>
                            <span><?php echo $_SESSION['username']?></span> &nbsp;
                            <span><i class="fa fa-sign-out"></i><a href="user/logout.php"> Đăng xuất </a></span>
                    <?php 
                        }
                        else {
                    ?>
                            <i class="fa fa-user fa-lg"></i>
                            <a href="login.php"> Đăng nhập </a> &nbsp;
                            <i class="fa fa-users fa-lg"></i>
                            <a href="register.php"> Đăng ký </a>
                    <?php
                        }
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>

            <nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> 
                    </div>
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="../index.php">Trang Chủ</a>
                            </li>
                            <li><a href="../introduceshop.php">Dịch Vụ</a>
                            </li>
                            <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sản Phẩm <b class="fa fa-caret-down"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="../fashionboy.php"><i class="fa fa-caret-right"></i> Thời Trang Nam</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="../fashiongirl.php"><i class="fa fa-caret-right"></i> Thời Trang Nữ</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="../newproduct.php"><i class="fa fa-caret-right"></i> Hàng Mới Về</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="../lienhe.php">Liên Hệ</a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <form role="search" action="/search">
                                <div class="input-group header-search">
                                    <input type="text" maxlength="50" name="query" id="searchs" class="form-control" placeholder="Nhập từ khóa..." style="font-size: 14px;">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default btn-search" type="submit"><span class="fa fa-search"></span>
                                        </button>
                                    </span>
                                </div>
                                <div class="cart-total">
                                    <a class="bg_cart" href="/cart" title="Giỏ hàng">
                                        <button type="button" class="btn header-cart"><span class="fa fa-shopping-cart"></span> &nbsp;<span id="cart-total">0</span> sản phẩm</button>
                                    </a>
                                    <div class="mini-cart-content shopping_cart">
                                    </div>
                                </div>
                            </form>
                        </ul>
                    </div>
                    </div>
                </nav>
        </div>
        </header>
    <div class="container" style="margin-top: 40px;">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <center><h4><strong> TÌM LẠI MẬT KHẨU </strong></h4></center>
                        <?php echo $message; ?>
                    </div><div class="panel-body">
                        <form action="forgot-password-back.php" method="post" name="form-forgot" accept-charset="utf-8">
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <p>Vui lòng nhập địa chỉ email bạn đã đăng ký. Mật khẩu mới sẽ được gửi qua email.</p>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope fa-lg"></i></span>
                                            <input type="email" name="email" class="form-control" placeholder="Email của bạn" required />
                                        </div>
                                    </div><div class="form-group">
                                        <input type="submit" name="submit" class="btn btn-warning btn-block btn-lg" value="Gửi Mật Khẩu Mới">
                                    </div></div></div></form>
                    </div><div class="panel-footer">
                        <p><a href="login.php"> Quay lại trang Đăng nhập </a></p>
                    </div></div></div></div></div></body>
</html>