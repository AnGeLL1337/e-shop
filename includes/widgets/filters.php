<?php
    $cat_id = ((isset($_REQUEST['cat']))?sanitize($_REQUEST['cat']):'');
    $price_sort = ((isset($_REQUEST['price_sort']))?sanitize($_REQUEST['price_sort']):'');
    $min_price = ((isset($_REQUEST['min_price']))?sanitize($_REQUEST['min_price']):'');
    $max_price = ((isset($_REQUEST['max_price']))?sanitize($_REQUEST['max_price']):'');
?>
<div class="left" style="text-align: center; padding-right: 30px;">
    <h3 class="text-center">Zoraď podľa:</h3>
    <h4 class="text-center">Cena</h4>
    <form action="search.php" method="post">
        <input type="hidden" name="cat" value="<?= $cat_id;?>">
        <input type="hidden" name="price_sort" value="0">
        <input type="radio" name="price_sort" value="low"<?=(($price_sort == 'low')?' zoradené':' ');?>> Od najnižšej po najvyšiu<br>
        <input type="radio" name="price_sort" value="high"<?=(($price_sort == 'high')?' zoradené':' ');?>> Od najvyššej po najnižšiu<br><br>
        <input type="text" name="min_price" class="price-range" placeholder="Min €" value="<?= $min_price;?>"> Do 
        <input type="text" name="max_price" class="price-range" placeholder="Max €" value="<?= $max_price;?>"><br><br>
        <input type="submit" value="Hľadaj" class="btn btn-xs btn-primary">
    </form>
</div>