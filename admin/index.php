<?php 
    require_once('../model/connect.php');

    // 1. Thống kê tổng số sản phẩm (Bảng 'products')
    $sql_products = "SELECT COUNT(*) as total FROM products";
    $res_products = mysqli_query($conn, $sql_products);
    $total_products = ($res_products) ? mysqli_fetch_assoc($res_products)['total'] : 0;

    // 2. Thống kê đơn hàng mới chờ xác nhận (Bảng 'orders', status = 0)
    $sql_new_orders = "SELECT COUNT(*) as total FROM orders WHERE status = 0";
    $res_new_orders = mysqli_query($conn, $sql_new_orders);
    $total_new_orders = ($res_new_orders) ? mysqli_fetch_assoc($res_new_orders)['total'] : 0;

    // 3. Thống kê khách hàng (Bảng 'users')
    $sql_users = "SELECT COUNT(*) as total FROM users";
    $res_users = mysqli_query($conn, $sql_users);
    $total_users = ($res_users) ? mysqli_fetch_assoc($res_users)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Trị Hệ Thống - Mylishop</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- CSS chung của website -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="dashboard-container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header text-center" style="color: #4e73df; font-weight: bold;">HỆ THỐNG QUẢN TRỊ ADMIN</h1>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <a href="product-list.php" class="stat-card bg-blue">
                <i class="fa fa-cubes fa-3x"></i>
                <span class="stat-num"><?php echo $total_products; ?></span>
                <span class="stat-text">Quản lý Sản phẩm</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="order-list.php" class="stat-card bg-yellow">
                <i class="fa fa-shopping-cart fa-3x"></i>
                <span class="stat-num"><?php echo $total_new_orders; ?></span>
                <span class="stat-text">Đơn hàng mới</span>
            </a>
        </div>
        <div class="col-md-4">
            <a href="user-list.php" class="stat-card bg-green">
                <i class="fa fa-users fa-3x"></i>
                <span class="stat-num"><?php echo $total_users; ?></span>
                <span class="stat-text">Khách hàng</span>
            </a>
        </div>
    </div>

    <div class="row" style="margin-top: 30px;">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-list-alt"></i> ĐƠN HÀNG CHỜ XÁC NHẬN (MỚI NHẤT)
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="info">
                                    <th>Mã Đơn</th>
                                    <th>Tên Khách Hàng</th>
                                    <th>Sản Phẩm Đặt</th>
                                    <th>Ngày Đặt</th>
                                    <th>Trạng Thái</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Lấy danh sách đơn hàng chờ xác nhận
                                $sql_list = "SELECT o.id, u.fullname, o.date_order, o.status 
                                             FROM orders o 
                                             JOIN users u ON o.user_id = u.id 
                                             WHERE o.status = 0 
                                             ORDER BY o.id DESC LIMIT 5";
                                
                                $res_list = mysqli_query($conn, $sql_list);

                                if ($res_list && mysqli_num_rows($res_list) > 0) {
                                    while ($row = mysqli_fetch_assoc($res_list)) {
                                        // Lấy sản phẩm trong đơn hàng này
                                        $order_id = $row['id'];
                                        $sql_products_order = "SELECT p.name 
                                                              FROM products p 
                                                              JOIN product_order po ON p.id = po.product_id 
                                                              WHERE po.order_id = $order_id";
                                        $res_products_order = mysqli_query($conn, $sql_products_order);
                                        
                                        $product_names = array();
                                        while ($product_row = mysqli_fetch_assoc($res_products_order)) {
                                            $product_names[] = $product_row['name'];
                                        }
                                        
                                        // Hiển thị tên sản phẩm, tối đa 2 sản phẩm
                                        $display_products = implode(", ", array_slice($product_names, 0, 2));
                                        if (count($product_names) > 2) {
                                            $display_products .= "...";
                                        }
                                ?>
                                    <tr>
                                        <td>#DH-<?php echo $row['id']; ?></td>
                                        <td><?php echo $row['fullname']; ?></td>
                                        <td><?php echo $display_products; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['date_order'])); ?></td>
                                        <td><span class="label label-warning">Chờ duyệt</span></td>
                                        <td>
                                            <a href="order-confirm.php?id=<?php echo $row['id']; ?>" class="btn btn-xs btn-success">Xác nhận đơn</a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Hiện không có đơn hàng mới nào.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>