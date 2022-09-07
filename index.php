<?php require 'pages/header.php'; ?>
<?php require 'classes/AdvertisementClass.php';  ?>
<?php require 'classes/UserClass.php'; ?>
<?php require 'classes/CategoryClass.php'; ?>
<?php
    $advertisements = new Advertisement();
    $user = new User();

    $filters = array(
        'category' => null,
        'title' => null,
        'max-price' => null,
        'min-price' => null,
        'status' => null
    );

    if(isset($_GET['filter'])){
        $filters = $_GET['filter'];
    }

    $totalAds = $advertisements->getAll($filters);
    $totalSub = $user->getTotal();

    $p = 1;
    if(isset($_GET['p']))
        $p = addslashes($_GET['p']);

    $perPage = 2;
    $totalPages = ceil(count($totalAds) / $perPage);
    $laatested = $advertisements->getLatested($p, $perPage, $filters);


?>

<div class="container-fluid">
    <div class="jumbotron">
        <h1>Today we have <?= count($totalAds); ?> advertisements.</h1>
        <p>And more then <?= $totalSub; ?> subscribed users.</p>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <h2 class="h4">Filters</h2>
            <form method="GET">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" name="filter[category]" id="category">
                        <option value=""></option>
                        <?php 
                            $c = new Category();
                            $categories = $c->getAll();
                            foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= isset($filters['category']) && $filters['category'] == $category['id'] ? "selected='selected'": '' ?>>
                                <?= $category['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" name="filter[title]" id="title">
                </div>
                <div class="form-group">
                    <label for="max-price">Price:</label>
                    <input class="form-control" name="filter[max-price]" id="max-price" placeholder="Max">
                    <input class="form-control" name="filter[min-price]" id="min-price" placeholder="Min">
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" name="filter[status]" id="status">
                        <option value="0" <?= isset($filters['status']) && $filters['status'] == 0 ? "selected='selected'": '' ?>>New</option>
                        <option value="1" <?= isset($filters['status']) && $filters['status'] == 1 ?"selected='selected'": '' ?>>Semi-new</option>
                        <option value="2" <?= isset($filters['status']) && $filters['status'] == 2 ?"selected='selected'": '' ?>>Used</option>
                    </select>
                </div>
                <input type="submit" class="btn btn-success"value="Search">
            </form>
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
