<?php
require_once '../core/init.php';
session_start();

if(isset($_POST['submit'])){
    $recenzia = $_POST['recenzia'];
    $hodnotenie = $_POST['hodnotenie'];
    $produkt = $_POST['produkt'];
    $nickname = $_SESSION['usernickname'];
    $vyberTu = "";
    $vyberHodnotenia = "";

    if($hodnotenie == 1){
        $vyberTu = "dobre";
    }
    elseif($hodnotenie == 2){
        $vyberTu = "zle";
    }
    if(empty($recenzia) || empty($nickname) || empty($hodnotenie) || empty($produkt)){
        header("Location: ../rate.php?error=prazdnepolia");
        exit();
    }
    $sql1 = "INSERT INTO rating (nickname, review, rate, product) VALUES ('$nickname', '$recenzia', '$hodnotenie', '$produkt')";
    mysqli_query($db, $sql1);
    $sql2 = "SELECT * FROM products";
    $result2 = mysqli_query($db, $sql2);

    while($row = mysqli_fetch_assoc($result2)){
        if($produkt == $row['title']){
            $vyberHodnotenia = $row[$vyberTu];
        }
    }if($hodnotenie == 1){
        $vyberHodnotenia = $vyberHodnotenia + 1;
    }
    elseif($hodnotenie == 2){
        $vyberHodnotenia = $vyberHodnotenia + 1;
    }
    
    $sql3 = "UPDATE products SET $vyberTu='$vyberHodnotenia' WHERE title = '$produkt'";
    mysqli_query($db, $sql3);
    header("Location: ../rate.php?error=none");
}else{
    header("Location: ../rate.php");
    exit();
}