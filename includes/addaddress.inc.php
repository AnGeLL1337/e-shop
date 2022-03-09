<?php
require_once '../core/init.php';

if(isset($_POST['submit']) && $_SESSION['userid']){
    $userid = $_SESSION['userid'];
    // $sql = "SELECT * FROM usersindex WHERE id = {$userid}";
    // $result = mysqli_query($db,$sql);
    // $row = mysqli_fetch_assoc($result);
    // $name =  $row['name'];
    // $email =  $row['email'];

    $street = $_POST['adresa'];
    $street2 = $_POST['adresa2'];
    $city = $_POST['mesto'];
    $state = $_POST['stat'];
    $zip_code = $_POST['zipcode'];

    $sql = "SELECT * FROM adresy WHERE userid = {$userid}";
    $result = mysqli_query($db, $sql);
    $numRows = mysqli_num_rows($result);
    if($numRows > 0){
        $sql2 = "UPDATE adresy SET adresa1 = '{$street}', adresa2 = '{$street2}', mesto = '{$city}', stat = '{$state}', smerovaciecislo = '{$zip_code}' WHERE userid = {$userid}";
        mysqli_query($db,$sql2);
        header("Location: ../account.php");
    }else{
        $sql3 = "INSERT INTO adresy (userid,adresa1,adresa2,mesto,stat,smerovaciecislo) VALUES ({$userid},'{$street}','{$street2}','{$city}','{$state}','{$zip_code}')";
        mysqli_query($db,$sql3);
        header("Location: ../account.php");
    }
}else{
    header("Location: ../index.php");
}