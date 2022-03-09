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

if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
}else{
    $cat_id = '';
}


$sql = "SELECT * FROM products WHERE categories = '$cat_id' AND deleted = 0"; //vyberie produkty na hlavnu stranku
$productQ = $db->query($sql);
$category = get_category($cat_id);
?>
        <div class="col-md-8">
            <div class="row">
                <h2 class="text-center"><?= $category['parent']. ' ' . $category['child'];?></h2>
                <?php while($product = mysqli_fetch_assoc($productQ)) : ?> <!-- Namiesto mnozinovych zatvoriek sme cyklus while vyriesili takto -->
                    <div class="col-md-3">
                        <h4><?= $product['title']; ?></h4> <!-- php echo sa rovna = -->
                        <img style="max-width: 200px !important; height: auto !important;" src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb">
                        <p class="list-price text-danger">Pôvodná cena: <s><?= $product['list_price']; ?></s></p>
                        <p class="price">Naša cena: <?= $product['price']; ?>€</p>
                        <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id']; ?>)">Detaily</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- <div class="col-md-2">Right Side Bar</div> -->
    
<?php
    include 'includes/rightbar.php';
    include 'includes/footer.php';
    include 'includes/detailsmodal.php';
?>