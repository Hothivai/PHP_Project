<?php
	include '../model/header.php';
    require_once("../model/connect.php");
    error_reporting(2);

    // Chỉnh sửa sản phẩm
    if (isset($_GET['idProduct']))
    {
        if (isset($_GET['es'])) {
            echo "<script type=\"text/javascript\">alert(\"Bạn đã sửa sản phẩm thành công!\");</script>";
        }
        if (isset($_GET['ef'])) {
            echo "<script type=\"text/javascript\">alert(\"Sửa sản phẩm thất bại!\");</script>";
        }
    }


	if (isset($_GET['idProduct']))
	{
	    $idProduct = $_GET['idProduct'];
	    $sql = "SELECT * FROM products WHERE id  = " . $idProduct;
	    $result = mysqli_query($conn,$sql);
?>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Chỉnh sửa phòng </h1>
                </div>
                <!-- /.col-lg-12 -->

                <div class="col-lg-7" style="padding-bottom:120px">
                    <?php
                        if ($result)
                        {
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                $thumImage = "../" . $row['image'];
                    ?>
                                <form action="productedit-back.php?idProduct=<?php echo $row['id']; ?>" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label> Tên phòng </label>
                                        <input type="text" class="form-control" name="txtName" value="<?php echo $row['name'] ?>" required>
                                    </div>
                                    <!-- //Tên phòng -->

                                    <div class="form-group">
                                        <label> Danh mục phòng </label>
                                        <select class="form-control" name="category">
                                            <?php
    	                                        $idCategory = $row['category_id'];
    	                                        $sqlCategory = "SELECT * FROM categories WHERE id = $idCategory";
    	                                        $resCategory = mysqli_query($conn,$sqlCategory);
    	                                        while ($rowCa = mysqli_fetch_assoc($resCategory))
    	                                        {
                                            ?>
    	                                            <option value= "<?php echo $rowCa['id']; ?>"><?php echo $rowCa['name']; ?></option>

                                            <?php
    	                                        }

    	                                        $sqlCate = "SELECT * FROM categories";
$resCate = mysqli_query($conn,$sqlCate);
    	                                        while ($rowCate = mysqli_fetch_assoc($resCate))
    	                                        {
                                            ?>
    	                                            <option value= "<?php echo $rowCate['id']; ?>"><?php echo $rowCate['name']; ?></option>
                                            <?php
                                        		}
                                            ?>
                                        </select>
                                    </div>
                                    <!-- //Danh mục sản phẩm -->

                                     <div class="form-group">
                                        <label> Chọn hình ảnh phòng </label>
                                        <input type="file" name="FileImage">
                                        <img src ="<?php echo $thumImage ?>" width="150px" height ="150px">
                                        <!-- <input type="hidden" name="image" value="<?php echo $row['image']; ?>"> -->
                                    </div>
                                    <!-- //Hình ảnh phòng -->

                                    <div class="form-group">
                                        <label> Mô tả phòng </label>
                                        <textarea class="form-control" rows="3" name="txtDescript"><?php echo $row['description']; ?></textarea>
                                    </div>
                                    <!-- //Mô tả phòng -->

                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label> Giá phòng </label>
                                                <input type ="number"  class="form-control" name="txtPrice" value="<?php echo $row['price']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label> Phần trăm giảm (nếu có) </label>
                                                <input type = "number" class="form-control" name="txtSalePrice" value="<?php echo $row['saleprice']; ?>" min="0" max="50">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- //Giá sản phẩm -->

                                    <div class="form-group">
                                        <label> Nhập từ cho khách hàng tìm kiếm </label>
                                        <input class="form-control" name="txtKeyword" value="<?php echo $row['keyword']; ?>">
                                    </div>
                                    <!-- //keyword search sản phẩm -->

                                    <div class ="form-group">
                                        <label> Tình trạng phòng </label>
                                        <?php
                                            // status = 0 còn hàng, 1 hết hàng
                                            if ($row['status'] == 0)
                                            { 
                                        ?>
                                                <label class="radio-inline">
                                                    <input name="status" value="0" type="radio" checked=""> Còn phòng
                                                </label>
                                                <label class="radio-inline">
                                                    <input name="status" value="1" id ="hide" type="radio"> Hết phòng
                                                </label>
                                    <?php 
                                            } else
                                            {
                                    ?>
                                                <label class="radio-inline">
                                                    <input name="status" value="0" type="radio"> Còn phòng
                                                </label>
                                                <label class="radio-inline">
                                                    <input name="status" value="1" id ="hide" type="radio" checked=""> Hết phòng
                                                </label>
                                    <?php
                                            }
                                    ?>
                                    </div>
                                    <!-- //Tình trạng phòng -->
    	                    <?php
    	                                }
    	                            }
    	                        }
    	                    ?>
    	                    		<button type="submit" name="editProduct" class="btn btn-warning btn-lg">Chỉnh sửa phòng</button>
    	                		</form>
                </div><!-- /.col -->
    	    </div><!-- /.row -->
    	</div><!-- /.container-fluid -->
    </div><!-- /#page-wrapper -->
