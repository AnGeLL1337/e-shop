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
    
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $street = sanitize($_POST['street']);
    $street2 = sanitize($_POST['street2']);
    $city = sanitize($_POST['city']);
    $state = sanitize($_POST['state']);
    $zip_code = sanitize($_POST['zip_code']);
    $tax = sanitize($_POST['tax']);
    $sub_total = sanitize($_POST['sub_total']);
    $grand_total = sanitize($_POST['grand_total']);
    $cart_id = sanitize($_POST['cart_id']);

    //just inventory
    $sql0 = "SELECT * FROM cart WHERE id = '{$cart_id}'";
    $itemQ = mysqli_query($db,$sql0);
    $iresults = mysqli_fetch_assoc($itemQ);
    $items = json_decode($iresults['items'],true);
    foreach($items as $item){
        $item_id = $item['id'];
        $productQ = $db->query("SELECT available FROM products WHERE id = '{$item_id}'");
        $product = mysqli_fetch_assoc($productQ);
        $newQuantity = $product['available'] - $item['quantity'];
        $db->query("UPDATE products SET available = {$newQuantity} WHERE id = {$item_id}"); 
    }


    //update cart
    $sql = "UPDATE cart SET paid = 1 WHERE id = {$cart_id}";
    mysqli_query($db, $sql);
    $sql2 = "INSERT INTO transactions(cart_id,full_name,email,street,street2,city,`state`,zip_code,sub_total,tax,grand_total) VALUES ('$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$sub_total','$tax','$grand_total')";
    mysqli_query($db, $sql2);

    $domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
    setcookie(CART_COOKIE,'',1,"/",$domain,false);

        
    
    include 'includes/head.php';
    include 'includes/navigation.php';
    
    ?>  <div style="margin-left: 30px;">
            <h1 class="text-center text-success">Ďakujeme Vám!</h1>
            <p>Môžete očakávať dobierku v hodnote <?= money($grand_total);?> do troch pracovní dní. Dostali ste e-mail s potvrdením.</p>
            <p>Číslo vašej objednávky je: <strong><?= $cart_id?></strong></p>
            <p>Vaša objednávka príde na adresu uvedenú nižšie</p>
            <address>
                <?= $full_name?><br>
                <?= $street;?><br>
                <?= (($street2 != '')?$street2.'<br>':'')?>
                <?= $city. ','.$state.' '.$zip_code?>
            </address>
        </div>
    <?php

    include 'includes/footer.php';
    
?>