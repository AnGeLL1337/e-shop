<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/eshop/core/init.php';
unset($_SESSION['SBUuser']);
header('Location: login.php');
?>