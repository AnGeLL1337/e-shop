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

require_once 'core/init.php'; 
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/leftbar.php';

$sql = "SELECT * FROM products WHERE featured = 1 AND deleted = 0"; //vyberie produkty na hlavnu stranku
$featured = $db->query($sql);
?>
    <div class="col-md-8">
        <div class="row">
            <h2 class="text-center">Vybrané produkty</h2>
            <?php while ($product = mysqli_fetch_assoc($featured)) : ?>
                <!-- Namiesto mnozinovych zatvoriek sme cyklus while vyriesili takto -->
                <div class="col-md-3">
                    <h4><?= $product['title']; ?></h4> <!-- php echo sa rovna = -->
                    <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb">
                    <p class="list-price text-danger">Pôvodná cena: <s><?= $product['list_price']; ?></s></p>
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
    