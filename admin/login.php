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
include 'includes/head.php';


$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();
?>
<style>
    body{
        background-image: url("/eshop/images/loginCover.jpg");
        background-size: cover;
    }
</style>
<div id="login-form">
    <div>
    
    <?php
        if($_POST){
            //form validation
            if(empty($_POST['email']) || empty($_POST['password'])){
                $errors[] = 'Musíte poskytnúť heslo a email.';
            }
            //validate email
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors[] = 'Musíte zadaj správnu formu emailu.';
            }
            //password is more than 6 characters
            if(strlen($password) < 6){
                $errors[] = 'Heslo musí mať aspoň 6 znakov.';
            }
            //check if email exist in the database
            $query = $db->query("SELECT * FROM users WHERE email = '$email'");
            $user = mysqli_fetch_assoc($query);
            $userCount = mysqli_num_rows($query);
            if($userCount < 1){
                $errors[] = 'Tento email neexistuje v našej databáze.';
            }

            // if(!password_verify($password, $user['password'])){ //tato funkcie hashne $password a skontroluje ci sa rovna heslu z databazy
            //     $errors[] = 'Heslo nezodpovedá našim záznamom. Prosím, vyskúšajte znova.';
            // }

            //check for errors
            if(!empty($errors)){
                echo display_errors($errors);
            }else{
                //log user in
                $user_id = $user['id'];
                login($user_id); //funkciu login som si vytvoril v helpers
            }
        }
    ?>
    
    </div>
    <h2 class="text-center">Prihlásiť sa</h2><hr>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
        </div>
        <div class="form-group">
            <label for="password">Heslo:</label>
            <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
        </div>
        <div class="form-group">
            <input type="submit" value="Prihlásiť sa" class="btn btn-primary">
        </div>
    </form>
    <p class="text-right"><a href="/eshop/index.php" alt="home">Navštíviť stránku</a></p>
</div>
<?php
include 'includes/footer.php';
?>