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

if ($cart_id != '') {
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'], true);
    $i = 1;
    $sub_total = 0;
    $item_count = 0;
}
if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $sql = "SELECT * FROM adresy WHERE userid = {$userid}";
    $result = mysqli_query($db,$sql);
    $numRows = mysqli_num_rows($result);
    if($numRows > 0){
        $row = mysqli_fetch_assoc($result);
        $sql2 = "SELECT * FROM usersindex WHERE id = {$userid}";
        $result2 = mysqli_query($db,$sql2);
        $row2 = mysqli_fetch_assoc($result2);
    }
}
?>

<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">Môj nákupný košík</h2>
        <hr>
        <?php if ($cart_id == '') : ?>
            <div class="bg-danger">
                <p class="text-center text-danger">
                    Váš nákupný košík je prázdny!
                </p>
            </div>
        <?php else : ?>
            <table class="table table-bordered table-condensed table-striped" style="width: 90%; margin: auto">
                <thead>
                    <th>#</th>
                    <th>Produkt</th>
                    <th>Cena</th>
                    <th>Množstvo</th>
                    <th>Medzisúčet</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($items as $item) {
                        $product_id = $item['id'];
                        $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                        $product = mysqli_fetch_assoc($productQ);
                        $available = $product['available'];
                    ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $product['title']; ?></td>
                            <td><?= money($product['price']); ?></td>
                            <td>
                                <button class="btn btn-xs btn-defaul" onclick="update_cart('removeone','<?= $product['id']; ?>')">-</button>
                                <?= $item['quantity']; ?>
                                <?php if ($item['quantity'] < $available) : ?>
                                    <button class="btn btn-xs btn-defaul" onclick="update_cart('addone','<?= $product['id']; ?>')">+</button>
                                <?php else : ?>
                                    <span class="text-danger">Maximálny počet</span>
                                <?php endif; ?>
                            </td>
                            <td><?= money($item['quantity'] * $product['price']); ?></td>
                        </tr>
                    <?php
                        $i++;
                        $item_count += $item['quantity'];
                        $sub_total += ($product['price'] * $item['quantity']);
                    }
                    $tax = TAXRATE * $sub_total;
                    $tax = number_format($tax, 2);
                    $grand_total = (int) $tax + $sub_total;
                    ?>
                </tbody>
            </table>
            <table class="table table-bordered table-condensed text-right" style="width: 90%; margin: auto; margin-top: 25px">
                <legend style="width: 90%; margin: auto; margin-top: 25px">Súčty</legend>
                <thead class="totals-table-header">
                    <th>Počet produktov</th>
                    <th>Medzisúčet</th>
                    <th>Daň</th>
                    <th>Celková suma</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $item_count; ?></td>
                        <td><?= money($sub_total); ?></td>
                        <td><?= money((int) $tax); ?></td>
                        <td class="bg-success"><?= money($grand_total); ?></td>
                    </tr>
                </tbody>
            </table>
            <div style="width: 90%; margin: auto; margin-top: 25px;">
                <!-- Chceck Out Button -->
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal" style="margin-left: 20px;">
                    <span class="glyphicon glyphicon-shopping-cart"></span> Platba >>
                </button>
                <a href="removeAllCart.php?cart_id=<?=$cart_id;?>">
                    <button type="button" class="btn btn-secondary pull-right">
                        Vyprázdniť košík
                    </button>
                </a>

                <!-- Modal -->
                <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                    <h3 class="modal-title" id="checkoutModalLabel" style="display: inline-block;">Dodacia adresa</h3>
                                    <?php if(isset($_SESSION['userid']) && $numRows > 0):?>  
                                        <button id="btnPridajAdresu">Použiť aktuálnu adresu</button>
                                    <?php endif;?>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="thankYou.php" method="post" id="payment-form">
                                    <span class="bg-danger" id="payment-errors"></span>
                                    <input type="hidden" name="tax" value="<?= $tax;?>">
                                    <input type="hidden" name="sub_total" value="<?= $sub_total;?>">
                                    <input type="hidden" name="grand_total" value="<?= $grand_total;?>">
                                    <input type="hidden" name="cart_id" value="<?= $cart_id;?>">
                                    <div id="step1" style="display:flex; flex-wrap:wrap;">
                                        <!--sem daj display:flex -->
                                        <div class="" col-md-6 style="width: 50% !important; padding: 15px;">
                                            <label for="full_name">Celé Meno:</label>
                                            <input type="text" id="full_name" name="full_name" class="form-control">
                                        </div>
                                        <div class="" col-md-6 style="width: 50% !important; padding: 15px;">
                                            <label for="email">Email:</label>
                                            <input type="email" id="email" name="email" class="form-control">
                                        </div>
                                        <div class="form-group" col-md-6 style="width: 50% !important; padding: 15px;">
                                            <label for="street">Adresa:</label>
                                            <input type="text" id="street" name="street" class="form-control">
                                        </div>
                                        <div class="form-group" col-md-6 style="width: 50% !important; padding: 15px;">
                                            <label for="street2">Adresa 2:</label>
                                            <input type="text" id="street2" name="street2" class="form-control">
                                        </div>
                                        <div class="form-group" col-md-6 style="width: 50% !important; padding: 15px;">
                                            <label for="city">Mesto:</label>
                                            <input type="text" id="city" name="city" class="form-control">
                                        </div>
                                        <div class="form-group" col-md-6 style="width: 50% !important; padding: 15px;">
                                            <label for="state">Štát:</label>
                                            <input type="text" id="state" name="state" class="form-control">
                                        </div>
                                        <div class="form-group" col-md-6 style="width: 50% !important; padding: 15px;">
                                            <label for="zip_code">Smerovacie číslo:</label>
                                            <input type="text" id="zip_code" name="zip_code" class="form-control">
                                        </div>
                                    </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Zatvoriť</button>
                                <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Ďalej</button>
                                <button type="submit" class="btn btn-primary" id="checkout_button" style="display:none;">Check Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<script>
    let btnAddress = document.querySelector("#btnPridajAdresu");
        btnAddress.addEventListener('click', function(){
        let name = '<?=$row2['name'];?>';
        let email = '<?=$row2['email'];?>';
        let adresa1 = '<?= $row['adresa1'];?>';
        let adresa2 = '<?= $row['adresa2'];?>';
        let mesto = '<?= $row['mesto'];?>';
        let stat = '<?= $row['stat'];?>';
        let smerovaciecislo = '<?= $row['smerovaciecislo'];?>';

        document.querySelector("#full_name").value = name;
        document.querySelector("#email").value = email;
        document.querySelector("#street").value = adresa1;
        document.querySelector("#street2").value = adresa2;
        document.querySelector("#city").value = mesto;
        document.querySelector("#state").value = stat;
        document.querySelector("#zip_code").value = smerovaciecislo;
    });

    function check_address() {
        let full_name = document.querySelector("#full_name").value;
        let email = document.querySelector("#email").value;
        let street = document.querySelector("#street").value;
        let street2 = document.querySelector("#street2").value;
        let city = document.querySelector("#city").value;
        let state = document.querySelector("#state").value;
        let zip_code = document.querySelector("#zip_code").value;

        let submit = document.querySelector("#checkout_button");
        var data = 'full_name=' + full_name + '&email=' + email + '&street=' + street + '&street2=' + street2 + '&city=' + city + '&state=' + state + '&zip_code=' + zip_code;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/eshop/admin/parsers/check_address.php', true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (this.readyState == 4 && this.status == 200) {
                var responseData = JSON.parse(this.responseText);
                document.querySelector("#payment-errors").innerHTML = "";
                //xhr.responseText;
                if (responseData.errors != '') {
                    for (var i in responseData.errors) {
                        document.querySelector("#payment-errors").innerHTML += responseData.errors[i] + '<br>';
                    }
                } else if (responseData.errors == '') {
                    document.querySelector("#payment-errors").innerHTML = "";
                    submit.click();
                }

            }
            if (this.status == 404) {
                alert("Dáta sa neodoslali, lebo sa nemajú kde odoslať");
            }
        }
        xhr.send(data);
    }
    function update_cart(mode, edit_id){
            // var data = {"mode" : mode, "edit_id" : edit_id};
            let data = "mode="+mode+"&edit_id="+edit_id;
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "/eshop/admin/parsers/update_cart.php", true);
            xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
            xhr.onload = function(){
                if(this.status == 200){
                    location.reload();
                }
                if(this.status == 404){ //not found
                        alert("Not Found...");
                }
            }
            xhr.send(data);
        }
</script>
