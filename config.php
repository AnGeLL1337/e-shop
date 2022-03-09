<?php
define('BASEURL', $_SERVER['DOCUMENT_ROOT'].'/eshop/');
define('CART_COOKIE', 'SBwi72UCklwiqzz2');
define('CART_COOKIE_EXPIRE', time() + (86400 * 30)); //urcili sme ze nas kosik bude v cookie 30 dni. Tych 86400 je jeden den v sekundach
define('TAXRATE',0.087); //sem si môžme dať dan aku chceme. možme dať aj žiadnu a to tým, že tam dáme nulu