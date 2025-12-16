<?php
require_once("connect.php");

// Bật hiển thị lỗi để biết chính xác sai ở đâu
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $sql = "SELECT image FROM slides WHERE status=2";
    $result = mysqli_query($conn, $sql);

} catch (Exception $e) {
    die("Lỗi SQL: " . $e->getMessage());
}
error_reporting(0);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Banner</title>
    <link rel="stylesheet" href="../CSS/bootstrap.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f7f7f7;
        margin: 0;
        padding: 20px;
    }

    .banner-container {
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 15px;
    }

    .banner-item {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: 0.3s;
    }

    .banner-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .banner-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
    }

    .section-title {
        text-align: center;
        margin: 20px 0;
        color: #2c3e50;
        font-weight: bold;
    }

    .no-banner {
        text-align: center;
        color: #666;
        padding: 40px;
        font-style: italic;
    }
    </style>
</head>

<body>
    <h2 class="section-title">BANNER - KPNV27</h2>
    <div class="banner-container">
        <?php 
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)): 
        ?>
        <div class="banner-item">
            <img src="<?= htmlspecialchars($row['image']) ?>" alt="Banner">
        </div>
        <?php 
            endwhile; 
        } else {
        ?>
        <div class="no-banner">
            <p>Chưa có banner nào!</p>
        </div>
        <?php } ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>