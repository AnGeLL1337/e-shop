<?php
require_once  $_SERVER['DOCUMENT_ROOT'].'/eshop/core/init.php';
if(isset($_POST["id"])){
    $id = $_POST["id"];
}else{
    $id = NULL;
}
$id = (int)$id;
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);
$brand_id = $product['brand'];
$sql = "SELECT brand FROM brand WHERE id = '$brand_id'";
$brand_query = $db->query($sql);
$brand = mysqli_fetch_assoc($brand_query);
?>
<? ob_start(); ?> <!-- vsetko toto sa posle do buffer (docastnej vyrovnavacej pamati) -->
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="diaolg" aria-labelledby="details-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #BDBDBD;">
                    <button class="close" type="button" onclick="closeModal()" arial-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-titl text-center" style="color: white;"><?= $product['title'];?></h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                        <span id="modal_errors" class="bg-danger"></span>
                            <div class="col-sm-6">
         
                                    <img src="<?= $product['image'];?>" alt="<?= $product['title'];?>" style="height: 300px; width: auto;" >
                                
                            </div>
                            <div class="col-sm-6">
                                <h4>Detaily</h4>
                                <p><?= nl2br($product['description']);?></p>
                                <hr>
                                <p>Cena: <?= $product['price'];?>€</p>
                                <p>Značka: <?= $brand['brand'];?></p>
                                <form action="add_cart.php" method="post" id="add_product_form">
                                    <input type="hidden" name="product_id" value="<?=$id;?>">
                                    <input type="hidden" name="available" id="available" value="">
                                    <div class="form-group">
                                        <div class="col-xs-3">
                                            <label for="quantity">Množstvo:</label>
                                            <input type="number" min="0" class="form-control" id="quantity" name="quantity">
                                        </div>
                                        <p><?=$product['available'] ;?></p> <!-- Toto vymazal kvoli velkostiam ale tie nemam -->
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"  style="background-color: #BDBDBD;">
                    <form action="rate.php" method="POST" style="display:inline">
                        <button class="btn btn-default" type="submit" name="submit">Pridaj recenziu</button>
                        <input type="hidden" name="vybranyProdukt" value="<?= $product['title'];?>">
                    </form>
                    <button class="btn btn-default" onclick="closeModal()">Zatvoriť</button>
                    <button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Pridať do košíka</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let availableModal = document.querySelector("#available");
        availableModal.value = <?=$product['available'];?>;
    

        function closeModal(){ //zatvaranie okna detailov
            jQuery('#details-modal').modal('hide');
            setTimeout(function(){
                jQuery('#details-modal').remove();
                jQuery('.modal-backdrop').remove();
            },500); 
        };
    </script>
<?php echo ob_get_clean(); ?>