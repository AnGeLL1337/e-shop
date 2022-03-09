<?php
require_once '../core/init.php';

if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $sql = "SELECT * FROM usersindex WHERE email='$email'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_assoc($result);
    $pwdFromDb = $row['password'];
    if(empty($email) || empty($pwd)){
        header("Location: ../login.php?chyba=prazdnepolia");
        exit();
    }elseif(mysqli_num_rows($result) == 0){
        header("Location: ../login.php?chyba=emailniejezaregistrovany");
        exit();
    }elseif(password_verify($pwd, $pwdFromDb) === false){
        header("Location: ../login.php?chyba=zleheslo");
        exit();
    }else{
        session_start();
        $_SESSION["userid"] = $row["id"];
        $_SESSION["usernickname"] = $row["nickname"];
        header("Location: ../index.php");
        exit();
    }
}else{
    header('Location: ../login.php');
    exit();
}