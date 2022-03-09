<?php
function nahoda() //aby mi fungovalo refreshovanie stranky (pomohol mi s tým učiteľ)
{
    $znaky = "1234567890asdfghjklqwertyuiopzxcvbnm";
    $vystup = "";
    for ($i = 0; $i < 10; $i++) {
        $vystup .= $znaky[rand(0, strlen($znaky) - 1)];
    }
    return $vystup;
}

require_once 'core/init.php'; // require znamena vyzadovat
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/leftbar.php';

$sql = "SELECT * FROM products";
$cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
if($cat_id == ''){
    $sql .= " WHERE deleted = 0";
}else{
    $sql .= " WHERE categories = '{$cat_id}' AND deleted = 0";
}
$price_sort = (($_POST['price_sort'] != '')?sanitize($_POST['price_sort']):'');
$min_price = (($_POST['min_price'] != '')?sanitize($_POST['min_price']):'');
$max_price = (($_POST['max_price'] != '')?sanitize($_POST['max_price']):'');
if($min_price != ''){
    $sql .= " AND price >= '{$min_price}'";
}
if($max_price != ''){
    $sql .= " AND price <= '{$max_price}'";
}
if($price_sort == 'low'){
    $sql .= " ORDER BY price";
}
if($price_sort == 'high'){
    $sql .= " ORDER BY price DESC";
}
$productQ = $db->query($sql);
$category = get_category($cat_id);

// echo $price_sort; echo $min_price; echo $max_price; echo $cat_id; die();
?>



   
    

        <div class="col-md-8">
            <div class="row">
                <?php if($cat_id != ''):?>
                    <h2 class="text-center"><?= $category['parent']. ' ' . $category['child'];?></h2>
                <?php else: ?>
                    <h2 class="text-center"></h2>
                <?php endif; ?>
                <?php while($product = mysqli_fetch_assoc($productQ)) : ?> <!-- Namiesto mnozinovych zatvoriek sme cyklus while vyriesili takto -->
                    <div class="col-md-3">
                        <h4><?= $product['title']; ?></h4> <!-- php echo sa rovna = -->
                        <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb">
                        <p class="list-price text-danger">List Price: <s><?= $product['list_price']; ?></s></p>
                        <p class="price">Naša cena: <?= $product['price']; ?>€</p>
                        <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id']; ?>)">Detaily</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        
    
<?php
    include 'includes/rightbar.php';
    include 'includes/footer.php';
    include 'includes/detailsmodal.php';
?>