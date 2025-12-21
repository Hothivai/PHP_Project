<?php
    require_once('model/connect.php');
    error_reporting(2);
?>
<?php
    session_start(); 
    require_once('model/connect.php');
    $prd = 0;
    if (isset($_SESSION['cart']))
    {
        $prd = count($_SESSION['cart']);
    }
    
    
    // Khởi tạo điều kiện lọc mặc định
    $where_condition = " status = 0 "; 

    // 1. Xử lý Lọc theo Tình trạng (quantity)
    if (isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
        $status_filter = $_GET['status_filter'];
        
        if ($status_filter == 'con_hang') {
            $where_condition .= " AND quantity > 0 ";
        } elseif ($status_filter == 'het_hang') {
            $where_condition .= " AND quantity = 0 ";
        }
    }

    // 2. Xử lý Lọc theo Khoảng Giá (price)
    if (isset($_GET['price_range']) && !empty($_GET['price_range'])) {
        $price_range = $_GET['price_range'];
        
        if ($price_range == 'duoi_150') {
            $where_condition .= " AND price < 150000 ";
        } elseif ($price_range == '150_300') {
            $where_condition .= " AND price >= 150000 AND price <= 300000 ";
        } elseif ($price_range == 'tren_300') {
            $where_condition .= " AND price > 300000 ";
        }
    }
    
    // <<< KẾT THÚC LOGIC LỌC PHP >>>
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Fashion VAIFASHION</title>
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

            <div class="filter-wrapper" style="margin-top: 20px; margin-bottom: 30px;">
                <div class="filter-box" style="padding: 15px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
                    <h4 style="margin-top: 0; margin-bottom: 15px;"><i class="fa fa-filter"></i> **Bộ lọc sản phẩm**</h4>
                    <form action="" method="GET" class="form-inline">
                        
                        <div class="form-group" style="margin-right: 20px;">
                            <label for="status_filter" style="font-weight: normal;">Tình trạng:</label>
                            <select name="status_filter" class="form-control">
                                <option value="">-- Tất cả --</option>
                            </select>
                        </div>

                        <div class="form-group" style="margin-right: 20px;">
                            <label for="price_range" style="font-weight: normal;">Khoảng giá:</label>
                            <select name="price_range" class="form-control">
                                <option value="">-- Tất cả --</option>
                                <option value="duoi_150" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == 'duoi_150') ? 'selected' : ''; ?>>Dưới 150.000đ</option>
                                <option value="150_300" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == '150_300') ? 'selected' : ''; ?>>150.000đ - 300.000đ</option>
                                <option value="tren_300" <?php echo (isset($_GET['price_range']) && $_GET['price_range'] == 'tren_300') ? 'selected' : ''; ?>>Trên 300.000đ</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-info">Lọc</button>
                        <a href="index.php" class="btn btn-default">Xóa Lọc</a>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="product-main">
                        
                        <div class="title-product-main">
                            <h3 class="section-title">Sản phẩm mới</h3>
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
                                                    <label style="color: red;">&hearts;</label> Mua hàng <label
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
                            <h3 class="section-title">Thời Trang Nam</h3>
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
                                                    <label style="color: red;">&hearts;</label> Mua hàng <label
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
                            <h3 class="section-title">Thời Trang Nữ</h3>
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
                                                    <label style="color: red;">&hearts;</label> Mua hàng <label
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