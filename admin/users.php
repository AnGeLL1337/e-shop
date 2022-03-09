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
    require_once '../core/init.php'; //aby sme boli pripojeny na server
    if(!is_logged_in()){
        login_error_redirect();
    }
    if(!has_permission('admin')){
        permission_error_redirect('index.php');
    }
    include 'includes/head.php';
    include 'includes/navigation.php';
    if(isset($_GET['delete'])){
        $delete_id = sanitize($_GET['delete']);
        $db->query("DELETE FROM users WHERE id = '$delete_id'");
        $_SESSION['success_flash'] = 'Užívateľ bol vymazaný!';
        header('Location: users.php');
    }
    if(isset($_GET['add'])){
        $name = ((isset($_POST['name']))?sanitize($_POST['name']):'');
        $email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
        $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
        $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
        $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']):'');
        $errors = array();
        if($_POST){
            $emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
            $emailCount = mysqli_num_rows($emailQuery);

            if($emailCount > 0){
                $errors[] = 'Tento email už existuje v našej databáze';
            }

            $required = array('name', 'email', 'password','confirm','permissions');
            foreach($required as $f){
                if(empty($_POST[$f])){
                    $errors[] = 'Musíte vyplniť všetky polia';
                    break;
                }
            }
            if(strlen($password) < 6){
                $errors[] = 'Vaše heslo musí mať najmenej 6 znakov';
            }
            if($password != $confirm){
                $errors[] = 'Vaše heslá sa nezhodujú';
            }
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $errors[] = 'Musíte zadať platný email';
            }
            if(!empty($errors)){
                echo display_errors($errors);
            }else{
                $hashed = password_hash($password,PASSWORD_DEFAULT);
                $db->query("INSERT INTO users (full_name, email, `password`, `permissions`) values ('$name', '$email', '$hashed','$permissions')");
                $_SESSION['success_flash'] = 'Užívateľ bol pridaný!';
                header('Location: users.php');
            }
        }
        ?>
        <h2 class="text-center ideTo2">Pridať nového užívateľa</h2><hr>
        <form action="users.php?add=1" method="post">
            <div class="form-group col-md-6">
                <label for="name">Celé Meno:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?=$name; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" value="<?=$email; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="password">Heslo:</label>
                <input type="password" name="password" id="password" class="form-control" value="<?=$password; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="confirm">Potvrdenie Hesla:</label>
                <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm; ?>">
            </div>
            <div class="form-group col-md-6">
                <label for="permissions">Oprávnenia:</label>
                <select class="form-control" name="permissions">
                    <option value=""<?=(($permissions == '')?'selected':'');?>></option>
                    <option value="editor"<?=(($permissions == 'editor')?'selected':'');?>>Editor</option>
                    <option value="admin,editor"<?=(($permissions == 'admin,editor')?'selected':'');?>>Admin</option>
                </select>
            </div>
            <div class="form-group col-md-6 text-right" style="margin-top:25px">
                <a href="users.php" class="btn btn-default">Zatvoriť</a>
                <input type="submit" value="Pridať užívateľa" class="btn btn-primary">
            </div>
        </form>
        <?php
    }else{
    $usersQuery = $db->query("SELECT * FROM users ORDER BY full_name");
?>
<h2 class="ideTo2">Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Pridať nového užívateľa</a>
<hr>
<table class="table table-bordered table-striped table-condensed">
    <thead><th></th><th>Name</th><th>Email</th><th>Join Date</th><th>Last Login</th><th>Permissions</th></thead>
    <tbody>
        <?php while($user = mysqli_fetch_assoc($usersQuery)):?>
            <tr>
                <td>
                    <?php if($user['id'] != $user_data['id']) :?>  <!-- aby sme nedokazali vymazat seba -->
                        <a href="users.php?delete=<?=$user['id'];?>" class="btn btn-default btx-xs"><span class="glyphicon glyphicon-remove-sign"></span></a>
                    <?php endif; ?>
                </td>
                <td><?=$user['full_name']?></td>
                <td><?=$user['email']?></td>
                <td><?=pretty_date($user['join_date']);?></td>
                <td><?=(($user['last_login'] == '0000-00-00 00:00:00')?"Never":pretty_date($user['last_login']));?></td>
                <td><?=$user['permissions']?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php
    } include 'includes/footer.php';
?>