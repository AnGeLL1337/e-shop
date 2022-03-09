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
?>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/eshop/core/init.php';
    if(!is_logged_in()){
        login_error_redirect();
    }
    include 'includes/head.php';
    include 'includes/navigation.php';

    $sql = "SELECT * FROM categories where parent = 0";
    $result = $db->query($sql);
    $errors = array();
    $category = '';
    $post_parent = '';

    //Edit Category
    if(isset($_GET['edit']) && !empty($_GET['edit'])){ //ak je isset a nie je prazdna, tak ideme editovat kategoriu
        $edit_id = (int)$_GET['edit'];
        $edit_id = sanitize($edit_id);
        $edit_sql = "SELECT * FROM categories WHERE id = '$edit_id'"; //vyberieme si tu kategoriu, ktoru ideme menit
        $edit_result = $db->query($edit_sql);
        $edit_category = mysqli_fetch_assoc($edit_result);
    }

    //Delete Category
    if(isset($_GET['delete']) && !empty($_GET['delete'])){
        $delete_id = (int)$_GET['delete'];
        $delete_id = sanitize($delete_id);
        $sql = "SELECT * FROM categories WHERE id = '$delete_id'";
        $result = $db->query($sql);
        $category = mysqli_fetch_assoc($result);
        if($category['parent'] == 0){ //ak sa rovna id 0 tak vymaze vsetko kategorie, ktore su pod nim
            $sql = "DELETE FROM categories WHERE parent = '$delete_id'";
            $db->query($sql);
        }
        $dsql = "DELETE FROM categories WHERE id = '$delete_id'"; //vymaze z databaty konkretnu categoriu
        $db->query($dsql);
        header('Location: categories.php');
    }

    //Process Form
    if(isset($_POST) && !empty($_POST)){
        $post_parent = sanitize($_POST['parent']);
        $category = sanitize($_POST['category']);
        $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'"; // AND parent = '$PARENT'" aby sme nemali toho vela. Laicky povedane
        if(isset($_GET['edit'])){
            $id = $edit_category['id'];
            $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id != '$id'"; //id sa nesmie rovnat id categorie
        }
        $fresult = $db->query($sqlform);
        $count = mysqli_num_rows($fresult); //ulozi sa sem hodnota toho, kolko mame riadkov uz danej kategorie. Ak bude viacej ako nula, uz sa neda vytvorit. Cize moze byt len jedna
        //if category is blank "prazdna"
        if($category == ''){
            $errors[] .= "Kategória nemôže zostať prázdna.";
        }

        //if exist in the database
        if($count > 0){
            $errors[] .= $category. ' už existuje. Prosím vyberte novú kategóriu.';
        }

        //Display Errors or Update Database
        if(!empty($errors)){
            //display errors
            $display = display_errors($errors); ?> <!-- je to prepojene s helpers.php  -->
            <script>
                jQuery('document').ready(function(){
                    jQuery('#errors').html('<?=$display; ?>');
                });
            </script>
        <?php    }else{
            //update database
            $updatesql = "INSERT INTO categories (category, parent) VALUES ('$category','$post_parent')"; //vlozenie novej kategorie do databazy
            if(isset($_GET['edit'])){
                $updatesql = "UPDATE categories SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";//upravi uz existujucu kategoriu
            }
            $db->query($updatesql); //odoslanie do databazy
            header('Location: categories.php');
        }
    }
    $category_value = '';
    $parent_value = 0;   
    if(isset($_GET['edit'])){ 
        $category_value = $edit_category['category']; //vypise to do inputu, ktoru kategoriu ideme menit
        $parent_value = $edit_category['parent']; //ulozime do parent_id, rodica nasej zmenej kategorie
    }
    else{
        if(isset($_POST)){
            $category_value = $category;
            $parent_value = $post_parent;
        }
    }
?>
<h2 class="text-center ideTo2">Kategórie</h2><hr>
<div class="row">
    <!-- Form -->
    <div class="col-md-6"> <!-- form --> <!-- class="col-md-6" bude to zaberat polovicku strany -->
        <form action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'') ?>" class="form" method="post"> <!-- action: ak som upravil kategoriu, tak sa mi to zobrazi aj s id zmenenou kategoriu, ak nic nezmenim, tak sa mi nic nevypise cize '' blank-->
            <legend><?=((isset($_GET['edit']))?'Zmeň':'Pridaj'); ?> kategóriu</legend><!-- Ak pojdeme zmenit kategoriu, tak vypise Zmeň a ak nie, tak to vypíše pridaj -->
            <div id="errors"></div>
            <div class="form-group">
                <label for="parent">Rodič</label>
                <select name="parent" id="parent" class="form-control">
                    <option value="0"<?=(($parent_value == 0)?' selected="selected"':''); ?>>Rodič</option> <!-- value="0" lebo mobily, tablety, notebooky maju parent 0 -->
                    <?php while($parent = mysqli_fetch_assoc($result)) : ?>
                        <option value="<?=$parent['id']; ?>"<?=(($parent_value == $parent['id'])?' selected="selected"':''); ?>><?=$parent['category']; ?></option>
                    <?php endwhile;?>
                </select>
            </div>
            <div class="form-group">
                <label for="category">Kategória</label>
                <input type="text" class="form-control" id="category" name="category" value="<?=$category_value;?>">
            </div>
            <div class="form-group">
                <input type="submit" value="<?=((isset($_GET['edit']))?'Zmeniť':'Pridať'); ?> kategóriu" class="btn btn-success"> <!--class="btn btn-success" vdaka tomuto sme zmenili vzhlad tlacidla "Na zelenu"-->
            </div>
        </form>
    </div>
    <!-- Category Table -->
    <div class="col-md-6">  <!-- table --> <!-- class="col-md-6" bude to zaberat polovicku strany -->
        <table class="table table-bordered">
            <thead>
                <th>Kategória</th><th>Rodič</th><th></th>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM categories where parent = 0";
                $result = $db->query($sql);
                while($parent = mysqli_fetch_assoc($result)):
                        $parent_id = (int)$parent['id']; //make sure, that it will be int
                        $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                        $cresult = $db->query($sql2);
                    ?>
                    <tr class="bg-primary"> <!-- nastavíme modrú farbu -->
                        <td><?=$parent['category']; ?></td> <!-- category -->
                        <td>Rodič</td> <!-- parent -->
                        <td>
                            <a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a> <!-- btn-xs = extra small -->
                            <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                        </td>
                    </tr>
                    <?php while($child = mysqli_fetch_assoc($cresult)): ?>
                        <tr class="bg-info"> <!-- nastavíme farbu -->
                            <td><?=$child['category']; ?></td> <!-- category -->
                            <td><?=$parent['category']; ?></td> <!-- parent -->
                            <td>
                                <a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a> <!-- btn-xs = extra small -->
                                <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div> 
</div>

<?php include 'includes/footer.php'; ?>