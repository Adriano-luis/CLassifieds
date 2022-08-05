<?php require 'pages/header.php'; ?>
<?php 
    if(!isset($_SESSION['user_id'])){
        ?>
            <script type="text/javascript">window.location.href="login.php"</script>
        <?php
        exit;
    }
?>
<div class="container">
    <h1>My Advertisements</h1>
    <a href="newAdvertisement.php" class="btn btn-default">New Advertisement</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Title</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <?php
            require 'classes/AdvertisementClass.php';

            $advertisement = new Advertisement();
            $list = $advertisement->getAll();

            foreach($list as $item): 
        ?>
                <tr>
                    <td>
                        <img src="assets/images/advertisements/<?= $item['url'] ?>" alt="">
                    </td>
                    <td>
                        <?= $item['title']; ?>
                    </td>
                    <td>
                        <?= number_format($item['price'], 2); ?>
                    </td>
                    <td>
                        
                    </td>
                </tr>
        <?php 
            endforeach;    
        ?>
    </table>
</div>
<?php require 'pages/footer.php'; ?>