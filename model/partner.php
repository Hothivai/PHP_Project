<div class="container">
    <div class="banner wow bounceInLeft">
        <div class="row">

            <?php
            require_once("connect.php");
            $sql = "SELECT image FROM slides WHERE status = 3";
            $result = mysqli_query($conn, $sql);

            while ($kq = mysqli_fetch_assoc($result)) {
        ?>
            <div class="col-md-2 col-sm-2 text-center">
                <div class="thumbnail">
                    <div class="hoverimage1">
                        <img src="<?php echo $kq['image']; ?>" alt="" class="img-responsive">
                    </div>
                </div>
            </div>
            <?php
            }
        ?>

        </div>
    </div>
</div>