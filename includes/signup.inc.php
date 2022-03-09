<?php
require_once '../core/init.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $nickname = $_POST['nickname'];
    $pwd = $_POST['pwd'];
    $pwdAgain = $_POST['pwdAgain'];

    $sql = "SELECT * FROM usersindex WHERE email='$email' OR nickname='$nickname'";
    $result = mysqli_query($db, $sql);
    if(empty($name) || empty($email) || empty($nickname) || empty($pwd) || empty($pwdAgain)){
        header("Location: ../signup.php?chyba=prazdnepolia");
        exit();
    }elseif(mysqli_num_rows($result) > 0){
        header("Location: ../signup.php?chyba=uzivatelexistuje");
        exit();
    }elseif($pwd !== $pwdAgain){
        header("Location: ../signup.php?chyba=nezhodahesiel");
        exit();
    }else{
        $pwdHashed = password_hash($pwd, PASSWORD_DEFAULT);
        $sql2 = "INSERT INTO usersindex (`name`, email, nickname, `password`) VALUES ('$name', '$email', '$nickname', '$pwdHashed')";
        mysqli_query($db, $sql2);
        header("Location: ../signup.php?chyba=ziadna");
        exit();
    }
}else{
    header('Location: ../signup.php');
    exit();
}