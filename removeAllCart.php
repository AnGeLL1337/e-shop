<?php
if(isset($_GET['cart_id'])){
    require_once 'core/init.php';
    $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);

    $db->query("DELETE FROM cart WHERE id = '{$cart_id}'");
    setcookie(CART_COOKIE,'',1,"/",$domain,false); //this destroy our cookie
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}