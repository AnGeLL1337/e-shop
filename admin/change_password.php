<?php
function nahoda()
{
    $znaky = "1234567890asdfghjklqwertyuiopzxcvbnm";
    $vystup = "";
    for ($i = 0; $i < 10; $i++) {
        $vystup .= $znaky[rand(0, strlen($znaky) - 1)];
    }
    return $vystup;
}
require_once $_SERVER['DOCUMENT_ROOT'].'/eshop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php'; 


$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();
?>

<div id="login-form">
    <div>
    
    <?php
        if($_POST){
            //form validation
            if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
                $errors[] = 'Musíte vyplniť všetky polia.';
            }
            //password is more than 6 characters
            if(strlen($password) < 6){
                $errors[] = 'Heslo musí mať aspoň 6 znakov.';
            }
            //if new password matches confirm
            if($password != $confirm){
                $errors[] = 'Nové heslo a potvrďovacie heslo sa nezhodujú.';
            }

            if(!password_verify($old_password, $hashed)){ //tato funkcie hashne $password a skontroluje ci sa rovna heslu z databazy
                $errors[] = 'Vaše staré heslo nezodpovedá našim záznamom. Prosím, vyskúšajte znova.';
            }

            //check for errors
            if(!empty($errors)){
                echo display_errors($errors);
            }else{
                //change password
                $db->query("UPDATE users SET `password` = '$new_hashed' WHERE id = '$user_id'");
                $_SESSION['success_flash'] = 'Vaše heslo bolo zmenené!';
                header('Location: index.php');
            }
        }
    ?>
    
    </div>
    <h2 class="text-center">Zmeniť heslo</h2><hr>
    <form action="change_password.php" method="post">
        <div class="form-group">
            <label for="old_password">Staré heslo:</label>
            <input type="password" name="old_password" id="old_password" class="form-control" value="<?=$old_password;?>">
        </div>
        <div class="form-group">
            <label for="password">Nové heslo:</label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
        </div>
        <div class="form-group">
            <label for="confirm">Potvrdenie nového hesla:</label>
            <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
        </div>
        <div class="form-group">
            <a href="index.php" class="btn btn-default">Zatvoriť</a>
            <input type="submit" value="Prihlásiť sa" class="btn btn-primary">
        </div>
    </form>
    <p class="text-right"><a href="/eshop/index.php" alt="home">Navštíviť stránku</a></p>
</div>
<?php
include 'includes/footer.php';
?>