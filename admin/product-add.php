<meta charset="utf-8">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Phòng</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css"> 
    <link rel="stylesheet" href="../css/style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php
    // Bắt đầu khối PHP
    require_once('../model/connect.php');
    error_reporting(E_ALL); // Nên dùng E_ALL để debug
    
    // Sửa lỗi Undefined variable $notimage (Dòng 89)
    // Khởi tạo biến $noimage trước khi sử dụng
    $noimage = ''; 

    if (isset($_GET['notimage'])) {
        $noimage = 'Vui lòng chọn hình ảnh hợp lệ!';
    }
?>

<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
            <h1 class="page-header"> Thêm phòng </h1>
            </div><div class="col-lg-7" style="padding-bottom:120px">
                <form action="productadd-back.php" method="POST" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <label> Tên phòng </label>
                        <input type="text" class="form-control" name="txtName" placeholder="Nhập tên phòng" required />
                    </div>
                    
                    <div class="form-group">
                        <label> Danh mục phòng </label>
                        <select class="form-control" name="category">
                            <?php
                            $sql = "SELECT * FROM categories";
                            $result = mysqli_query($conn,$sql);
                            if($result)
                            {
                                while($row = mysqli_fetch_assoc($result))
                                {
                            ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label> Giá phòng </label>
                                <input type="number" class="form-control" name="txtPrice"
                                    placeholder="Nhập giá phòng" min="20000" required />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label> Phần trăm giảm (nếu có) </label>
                                <input type="number" class="form-control" name="txtSalePrice"
                                    placeholder="Nhập phần trăm giá giảm" value="0" min="0" max="50" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label> Chọn hình ảnh phòng </label>
                        <input type="file" name="FileImage" required>
                        <span style="color: red"><?php echo $noimage; ?></span> 
                    </div>
                    
                    <div class="form-group">
                        <label> Nhập từ cho khách hàng tìm kiếm </label>
                        <input class="form-control" name="txtKeyword" placeholder="Nhập từ khóa tìm kiếm" />
                    </div>
                    
                    <div class="form-group">
                        <label> Mô tả phòng </label>
                        <textarea class="form-control" rows="3" name="txtDescript"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <button type="submit" name="addProduct" class="btn btn-warning btn-block btn-lg"> Thêm </button>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <button type="reset" class="btn btn-default btn-block btn-lg" style="background: gray; color:white;"> Thiết lập lại </button>
                        </div>
                    </div></form>
            </div></div></div></div><script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

</body>
</html>