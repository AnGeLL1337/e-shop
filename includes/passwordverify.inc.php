<?php
    require_once '../core/init.php';


    if(isset($_POST['oldpassword'])){

    $old = $_POST['oldpassword'];
    $userid = $_SESSION['userid'];
    $sql = "SELECT * FROM usersindex WHERE id = {$userid}";
    $result = mysqli_query($db,$sql);
    $row = mysqli_fetch_assoc($result);
    $hashed =  $row['password'];
    if(!password_verify($old, $hashed)){
        echo 0;
    }
    else{
        echo 1;
    }
    }else{
        header("Location: ../index.php");
    }
?>