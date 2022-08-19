<?php require 'pages/header.php'; ?>
<?php require 'classes/AdvertisementClass.php';  ?>
<?php require 'classes/UserClass.php'; ?>
<?php
    $advertisements = new Advertisement();
    $user = new User();

    $totalAds = $advertisements->getAll();
    $totalSub = $user->getTotal();

    $p = 1;
    if(isset($_GET['p']))
        $p = addslashes($_GET['p']);

    $perPage = 2;
    $totalPages = ceil(count($totalAds) / $perPage);
    $laatested = $advertisements->getLatested($p, $perPage);
?>

<div class="container-fluid">
    <div class="jumbotron">
        <h1>Today we have <?= count($totalAds); ?> advertisements.</h1>
        <p>And more then <?= $totalSub; ?> subscribed users.</p>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <h2 class="h4">Filters</h2>
        </div>
        <div class="col-sm-9">
            <h2 class="h4">Last Posts</h2>
            <table class="table table-striped">
                <tbody>
                    <?php foreach ($laatested as $item): ?>
                        <tr>
                            <td>
                                <?php isset($item['url']) ? $img = $item['url'] : $img = 'default.png'; ?>
                                <img src="assets/images/advertisements/<?= $img ?>" alt="" height="75">
                            </td>
                            <td>
                               <a href="showAdvertisement.php?id=<?= $item['id']; ?>"><?= $item['title']; ?></a>
                            </td>
                            <td>
                                <?= number_format($item['price'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <ul class="pagination">
                <?php for($q=0;$q<$totalPages;$q++): ?>
                    <li class="<?= ($p==($q+1)) ? 'active' : ''; ?>"><a href="index.php?p=<?= $q+1; ?>"><?= $q+1; ?></a></li>
                <?php endfor; ?>
            </ul>
        </div>
    </div>
</div>

<?php require 'pages/footer.php'; ?>
