<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css"> 
     <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
 <?php
//  include 'header.php';
// require_once('../model/header.php');
// require_once("../model/connect.php");
require_once('../model/connect.php');
error_reporting(2);

// Xóa sản phẩm
if (isset($_GET['ps']))
{
echo "<script type=\"text/javascript\">
alert(\"Bạn đã xóa sản phẩm thành công!\");
</script>";
}
if (isset($_GET['pf']))
{
echo "<script type=\"text/javascript\">
alert(\"Không thể xóa sản phẩm!\");
</script>";
}

// Thêm sản phẩm
if (isset($_GET['addps']))
{
echo "<script type=\"text/javascript\">
alert(\"Bạn đã thêm sản phẩm thành công!\");
</script>";
}
if (isset($_GET['addpf']))
{
echo "<script type=\"text/javascript\">
alert(\"Thêm sản phẩm thất bại!\");
</script>";
}
?>

<!-- page content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="page-header"> Danh sách sản phẩm </h1>
            </div><!-- /.col -->
            <div class="col-lg-6 col-md-6 col-sm-6 " style="padding-top: 30px;">
                <a href="../index.php" class="btn btn-primary">
                    Quay lại trang chủ
               </a>
               <a href="product-add.php" class="btn btn-success add-btn">
                  <i class="fa fa-plus-circle"></i> Thêm sản phẩm mới
               </a>
            </div>

<div class="clearfix"></div>
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr align="center">
                        <th> STT </th>
                        <th> Tên sản phẩm </th>
                        <th> Mã danh mục </th>
                        <th> Hình ảnh </th>
                        <th> Giá </th>
                        <th> Giảm giá </th>
                        <th> Chỉnh sửa </th>
                        <th> Xóa </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
						$sql = "SELECT * FROM products ORDER BY id DESC";
						$result = mysqli_query($conn,$sql);

						if ($result)
						{
							while ($row = mysqli_fetch_assoc($result))
							{
								if ($row['image'] == null || $row['image'] == '')
								{
                                	$thumbImage = "";
		                        } else {
		                            $thumbImage = "../" . $row['image'];
		                        }
					?>
                    <tr class="odd gradeX" align="center">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['category_id']; ?></td>
                        <td>
                            <img src="<?php echo $thumbImage; ?>" width="100px" ; height="100px" ;>
                        </td>
                        <td><?php echo $row['price']; ?></td>
                        <td><?php echo $row['saleprice']; ?></td>

                        <td class="center">
                            <a href="product-edit.php?idProduct=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary" title="Chỉnh sửa">
                                <i class="fa fa-pencil"></i> Sửa
                            </a>
                        </td>

                        <td class="center">
                            <a href="product-delete.php?idProducts=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Xóa" 
                                onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
                                <i class="fa fa-trash-o"></i> Xóa
                            </a>
                        </td>
                    </tr>
                    <?php
							}
						}
					?>
                </tbody>
            </table>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div><!-- /#page-wrapper -->   
</body>
</html>