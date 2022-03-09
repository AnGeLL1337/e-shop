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
require_once '../core/init.php';
if(!isset($_SESSION['userid'])){
    header("Location: ../index.php");
}
else{
    if(!isset($_POST['submit'])){
        header("Location: ../index.php");
    }else{
        $userid = $_SESSION['userid'];
        $oldPwd = $_POST['pwdOld'];
        $oldPwd = trim($oldPwd);
        $pwd = $_POST['pwd'];
        $pwd = trim($pwd);
        $check = $_POST['pwdCheck'];
        $check = trim($check);
        $newHashed = password_hash($pwd, PASSWORD_DEFAULT);

        $sql2 = "UPDATE usersindex SET `password` = '{$newHashed}' WHERE id = '{$userid}'";
        mysqli_query($db,$sql2);
        header("Location: ../account.php");
    }
}