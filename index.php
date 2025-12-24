<?php
    require_once('model/connect.php');
    error_reporting(2);
?>
<?php
    session_start(); 
    $prd = 0;
    if (isset($_SESSION['cart']))
    {
        $prd = count($_SESSION['cart']);
    }
    
    
    $where_condition = " status = 0 "; // Trạng thái mặc định từ bảng

    // 1. Lọc Tình trạng dựa trên cột quantity
    if (isset($_GET['status_filter']) && $_GET['status_filter'] !== '') {
        if ($_GET['status_filter'] == '1') {
            $where_condition .= " AND quantity > 0 ";
        } else {
            $where_condition .= " AND quantity = 0 ";
        }
    }

    // 2. Lọc Giá - Ép kiểu cột price từ float sang số nguyên để so sánh chuẩn
    if (isset($_GET['price_range']) && !empty($_GET['price_range'])) {
        $range = $_GET['price_range'];
        if ($range == 'duoi_1tr') {
            $where_condition .= " AND CAST(price AS SIGNED) < 1000000 ";
        } elseif ($range == '1tr_3tr') {
            $where_condition .= " AND CAST(price AS SIGNED) BETWEEN 1000000 AND 3000000 ";
        } elseif ($range == 'tren_3tr') {
            $where_condition .= " AND CAST(price AS SIGNED) > 3000000 ";
        }
    }
    // <<< KẾT THÚC LOGIC LỌC PHP >>>
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VAI HOTEL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logohong.png">

    <link rel="stylesheet" type="text/css" href="admin/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src='js/wow.js'></script>
    <script type="text/javascript" src="js/mylishop.js"></script>
    <link rel="stylesheet" type="text/css" href="css/animate.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>
    <a href="#" class="back-to-top"><i class="fa fa-arrow-up"></i></a>

    <?php include("model/header.php"); ?>
    <div class="main">
        <?php include("model/slide.php"); ?>
        <?php include("model/banner.php"); ?>
        <div class="container">
           
        <div class="filter-wrapper">
    <div class="filter-box">
        <h4 class="filter-title"><i class="fa fa-filter"></i> Tìm phòng VAI Luxury</h4>
        <form action="index.php" method="GET" class="form-inline-custom">
            <div class="form-group-custom">
                <label>Tình trạng:</label>
                <select name="status_filter" class="form-control-custom">
                    <option value="">-- Tất cả --</option>
                    <option value="1" <?php if(isset($_GET['status_filter']) && $_GET['status_filter'] == '1') echo 'selected'; ?>>Còn phòng</option>
                    <option value="0" <?php if(isset($_GET['status_filter']) && $_GET['status_filter'] == '0') echo 'selected'; ?>>Hết phòng</option>
                </select>
            </div>
            <div class="form-group-custom">
                <label>Giá phòng:</label>
                <select name="price_range" class="form-control-custom">
                    <option value="">-- Tất cả giá --</option>
                    <option value="duoi_1tr" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == 'duoi_1tr') echo 'selected'; ?>>Dưới 1.000.000đ</option>
                    <option value="1tr_3tr" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == '1tr_3tr') echo 'selected'; ?>>1tr - 3tr</option>
                    <option value="tren_3tr" <?php if(isset($_GET['price_range']) && $_GET['price_range'] == 'tren_3tr') echo 'selected'; ?>>Trên 3tr</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-submit-filter">LỌC PHÒNG</button>
                <a href="index.php" class="btn-reset-filter">XÓA LỌC</a>
            </div>
        </form>
    </div>
</div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="product-main">
                        
                        <div class="title-product-main">
                            <h3 class="section-title">Phòng có ưu đãi hot</h3>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                  
                                    // Gốc: $sql = "SELECT id,image,name,price FROM products WHERE category_id=3 AND status = 0";
                                    $sql_new = "SELECT id,image,name,price FROM products WHERE category_id=3 AND " . $where_condition;
                                    $result = mysqli_query($conn, $sql_new);
                                    while ($kq = mysqli_fetch_assoc($result)) {
                                        
                                ?>
                                <div class="col-md-3 col-sm-6 text-center">
                                    <div class="thumbnail">
                                        <div class="hoverimage1">
                                            <img src="<?php echo $kq['image']; ?>" alt="Generic placeholder thumbnail"
                                                width="100%" height="300">
                                        </div>
                                        <div class="name-product">
                                            <?php echo $kq['name']; ?>
                                        </div>
                                        <div class="price">
                                            Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                        </div>
                                        <div class="product-info">
                                            <a href="addcart.php?id=<?php echo $kq['id']; ?>">
                                                <button type="button" class="btn btn-primary">
                                                    <label style="color: red;">&hearts;</label> Đặt phòng <label
                                                        style="color: red;">&hearts;</label>
                                                </button>
                                            </a>
                                            <a href="detail.php?id=<?php echo $kq['id']; ?>">
                                                <button type="button" class="btn btn-primary">
                                                    <label style="color: red;">&hearts;</label> Chi Tiết <label
                                                        style="color: red;">&hearts;</label>
                                                </button>
                                            </a>
                                        </div></div></div><?php } ?>
                            </div></div><div class="title-product-main">
                            <h3 class="section-title">Phòng đơn</h3>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                    // <<< VỊ TRÍ 3B: SỬA TRUY VẤN SQL THỜI TRANG NAM >>>
                                    // Gốc: $sql = "SELECT id,image,name,price FROM products WHERE category_id=1 LIMIT 8";
                                    $sql_men = "SELECT id,image,name,price FROM products WHERE category_id=1 AND " . $where_condition . " LIMIT 8";
                                    $result = mysqli_query($conn, $sql_men);
                                    
                                    while ($kq = mysqli_fetch_assoc($result)) {
                                        
                                ?>
                                <div class="col-md-3 col-sm-6 text-center">
                                    <div class="thumbnail">
                                        <div class="hoverimage1">
                                            <img src="<?php echo $kq['image']; ?>" alt="Generic placeholder thumbnail"
                                                width="100%" height="300">
                                        </div>
                                        <div class="name-product">
                                            <?php echo $kq['name']; ?>
                                        </div>
                                        <div class="price">
                                            Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                        </div>
                                        <div class="product-info">
                                            <a href="addcart.php?id=<?php echo $kq['id']; ?>">
                                                <button type="button" class="btn btn-primary">
                                                    <label style="color: red;">&hearts;</label> Đặt phòng <label
                                                        style="color: red;">&hearts;</label>
                                                </button>
                                            </a>
                                            <a href="detail.php?id=<?php echo $kq['id'] ?>">
                                                <button type="button" class="btn btn-primary">
                                                    <label style="color: red;">&hearts;</label> Chi Tiết <label
                                                        style="color: red;">&hearts;</label>
                                                </button>
                                            </a>
                                        </div></div></div><?php } ?>
                            </div></div><div class="title-product-main">
                            <h3 class="section-title">Phòng đôi</h3>
                        </div>
                        <div class="content-product-main">
                            <div class="row">
                                <?php
                                    // <<< VỊ TRÍ 3C: SỬA TRUY VẤN SQL THỜI TRANG NỮ >>>
                                    // Gốc: $sql = "SELECT id,image,name,price FROM products WHERE category_id=2 LIMIT 8";
                                    $sql_women = "SELECT id,image,name,price FROM products WHERE category_id=2 AND " . $where_condition . " LIMIT 8";
                                    $result = mysqli_query($conn, $sql_women);
                                    
                                    while ($kq = mysqli_fetch_assoc($result)) {
                                        
                                ?>
                                <div class="col-md-3 col-sm-6 text-center">
                                    <div class="thumbnail">
                                        <div class="hoverimage1">
                                            <img src="<?php echo $kq['image']; ?>" alt="Generic placeholder thumbnail"
                                                width="100%" height="300">
                                        </div>
                                        <div class="name-product">
                                            <?php echo $kq['name']; ?>
                                        </div>
                                        <div class="price">
                                            Giá: <?php echo $kq['price']; ?><sup> đ</sup>
                                        </div>
                                        <div class="product-info">
                                            <a href="addcart.php?id=<?php echo $kq['id']; ?>">
                                                <button type="button" class="btn btn-primary">
                                                    <label style="color: red;">&hearts;</label> Đặt phòng <label
                                                        style="color: red;">&hearts;</label>
                                                </button>
                                            </a>
                                            <a href="detail.php?id=<?php echo $kq['id'] ?>">
                                                <button type="button" class="btn btn-primary">
                                                    <label style="color: red;">&hearts;</label> Chi Tiết <label
                                                        style="color: red;">&hearts;</label>
                                                </button>
                                            </a>
                                        </div></div></div><?php } ?>
                            </div></div></div> </div> </div></div><?php include("model/partner.php"); ?>
        <?php include("model/footer.php"); ?>