<?php
    if(isset($_SESSION['userid'])){
        $userid = $_SESSION['userid'];
        $resultNickname = mysqli_query($db, "SELECT nickname FROM usersindex WHERE id = {$userid}");
        $nickname = mysqli_fetch_assoc($resultNickname);
    
?>
    <h3 style="text-align: center;"><Strong>Dobrý deň <?= $nickname['nickname'];?></Strong></h3>
<?php }?>