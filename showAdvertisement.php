<?php require 'pages/header.php'; ?>
<?php require 'classes/AdvertisementClass.php';  ?>
<?php require 'classes/UserClass.php'; ?>
<?php
    if(!isset($_SESSION['user_id'])){
        ?>
            <script type="text/javascript">window.location.href="login.php"</script>
        <?php
        exit;
    }

    $advertisements = new Advertisement();
    $user = new User();

    if(isset($_GET['id'])){
        $id = addslashes($_GET['id']);
        $info = $advertisements->getAdvertisement($id);
    }else{
        ?>
            <script type="text/javascript">window.location.href="index.php"</script>
        <?php
        exit;
    }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <div class="carousel slide" data-ride="carousel" id="myCarousel">
                <div class="carousel-inner" role="listbox">
                    <?php if(isset($info['photos'])): ?>
                        <?php foreach($info['photos'] as $key => $photo): ?>
                            <div class="item <?= ($key == 0) ? 'active' : '' ?>">
                                <?php isset($photo['url']) ? $img = $photo['url'] : $img = 'default.png';?>
                                <img src="assets/images/advertisements/<?= $img ?>" alt="">
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="item active">
                            <?php  $img = 'default.png';?>
                            <img src="assets/images/advertisements/<?= $img ?>" alt="">
                        </div>
                    <?php endif; ?>
                </div>
                <a href="#myCarousel" class="left carousel-control" role="button" data-slide="prev"><span><</span></a>
                <a href="#myCarousel" class="right carousel-control" role="button" data-slide="next"><span>></span></a>
            </div>
        </div>
        <div class="col-sm-8">
            <h1><?= $info['title']; ?></h1>
            <h2 class="h4"><?= $info['category']; ?></h2>
            <p><?= $info['description']; ?></p><br>
            <h3>U$<?= number_format($info['price'], 2); ?></h3>
            <h4>Phone: <?= $info['phone']; ?></h4>
        </div>
    </div>
</div>

<?php require 'pages/footer.php'; ?>
