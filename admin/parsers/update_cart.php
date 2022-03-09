<?php 
    require_once $_SERVER['DOCUMENT_ROOT'].'/eshop/core/init.php';
    $mode = sanitize($_POST['mode']);
    $edit_id = sanitize($_POST['edit_id']);
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['items'],true);
    $updated_items = array();
    $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

    if($mode == 'removeone'){ //for one mode
        foreach($items as $item){
            if($item['id'] == $edit_id){
                $item['quantity'] = $item['quantity'] - 1;
            }
            if($item['quantity'] > 0){
                $updated_items[] = $item;
            }
        }
    }
    if($mode == 'addone'){ //for another mode
        foreach($items as $item){
            if($item['id'] == $edit_id){
                $item['quantity'] = $item['quantity'] + 1;
            }
            $updated_items[] = $item;
        }
    }
    if(!empty($updated_items)){ //ak sme vymazali, všetky produkty, tak sa aj vymaže cookie a dá sa preč daná položka z databázy
        $json_updated = json_encode($updated_items);
        $db->query("UPDATE cart SET items = '{$json_updated}' WHERE id = '{$cart_id}'");
        // $_SESSION['success_flash'] = 'Váš nákupný košík bol aktualizovaný!';
    }
    if(empty($updated_items)){
        $db->query("DELETE FROM cart WHERE id = '{$cart_id}'");
        setcookie(CART_COOKIE,'',1,"/",$domain,false); //this destroy our cookie
    }
    
?>