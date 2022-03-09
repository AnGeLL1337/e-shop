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
include 'includes/navigation.php';
//Delete product
if(isset($_GET['delete'])){
    $id = sanitize($_GET['delete']);
    $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
    header('Location: products.php');
}

$dbpath = '';
if(isset($_GET['add']) || isset($_GET['edit'])){
$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand"); //aby sme to mali zoradene od Apple Asus Samsung..
$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
$category = ((isset($_POST['child'])) && !empty($_POST['child'])?sanitize($_POST['child']):'');
$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
$available = ((isset($_POST['available']) && $_POST['available'] != '')?sanitize($_POST['available']):'');
$saved_image = '';

    if(isset($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($productResults);
        if(isset($_GET['delete_image'])){
            $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
            unlink($image_url);
            $db->query("UPDATE products SET `image` = '' WHERE id = '$edit_id'");
            header('Location: products.php?edit='.$edit_id);
        }
        $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
        $title = ((isset($_POST['title']) && $_POST['title'] !='')?sanitize($_POST['title']):$product['title']);
        $brand = ((isset($_POST['brand']) && $_POST['brand'] !='')?sanitize($_POST['brand']):$product['brand']);
        $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
        $parentResult = mysqli_fetch_assoc($parentQ);
        $parent = ((isset($_POST['parent']) && $_POST['parent'] !='')?sanitize($_POST['parent']):$parentResult['parent']);
        $price = ((isset($_POST['price']) && $_POST['price'] !='')?sanitize($_POST['price']):$product['price']);
        $list_price = ((isset($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
        $available = ((isset($_POST['available']) && $_POST['available'] !='')?sanitize($_POST['available']):$product['available']);
        $description = ((isset($_POST['description']))?sanitize($_POST['description']):$product['description']);
        $saved_image = (($product['image'] != '')?$product['image']:'');
        $dbpath = $saved_image;
    }
if($_POST){
    // $categories = sanitize($_POST['child']);  nema byt v 20. videu povedal
    // $price = sanitize($_POST['price']);
    // $list_price = sanitize($_POST['list_price']);
    // $description = sanitize($_POST['description']);
    // $available = sanitize($_POST['available']);
    // $dbpath = ''; -- toto som musel zakomentovat, aby mi zostal obrazok ---------------------------------------------------
    $errors = array();
    $required = array('title','brand','price','parent','child','available');
    foreach($required as $field){
        if($_POST[$field] == ''){
            $errors[] = 'Všetky polia s hviezdičkou sú požadované.';
            break;
        }
    }
    if ($_FILES['photo']['name'] != ''){ //kontrola suboru "obrazku" nema byt  v 20. videu povedal
        // var_dump($_FILES);
        $photo = $_FILES['photo'];
        $name = $photo['name'];
        $nameArray = explode('.',$name);
        $fileName = $nameArray[0];
        $fileExt = $nameArray[1];
        $mime = explode('/',$photo['type']);
        $mimeType = $mime[0];
        $mimeExt = $mime[1];
        $tmpLoc = $photo['tmp_name'];
        $fileSize = $photo['size'];
        $allowed = array('png','jpg','jpeg','gif');
        $uploadName = md5(microtime()).'.'.$fileExt;
        $uploadPath = BASEURL.'images/'.$uploadName;
        $dbpath = '/eshop/images/'.$uploadName;
        if($mimeType != 'image'){
            $errors[] = 'Súbor musí byť obrázok.';
        }
        if(!in_array($fileExt, $allowed)){
            $errors[] = 'Súbor musí byť png, jpg, jpeg, alebo gif.';
        }
        if($fileSize > 15000000){
            $errors[] = 'Veľkosť súboru musí byť pod 15MB.';
        }
        if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
            $errors[] = 'Prípona súboru nezodpovedá súboru.';
        }
    }
    if(!empty($errors)){
        echo display_errors($errors); //vdaka helpers sa nam to zobrazuje
    }else{
        if(!empty($_FILES)){
            move_uploaded_file($tmpLoc, $uploadPath);
        }
        $insertSql = "INSERT INTO products (`title`, `price`, `list_price`, `brand`, `categories`, `image`, `description`,`available`) 
        VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$dbpath', '$description', '$available')";
        if(isset($_GET['edit'])){
            $insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand',
             categories = '$category', `image` = '$dbpath', `description` = '$description', `available` = '$available' WHERE id = '$edit_id'";
        }
        $db->query($insertSql);
        header('Location: products.php');
    }
}
?>
    <h2 class="text-center ideTo2"><?=((isset($_GET['edit']))?'Upraviť':'Pridať nový') ;?> produkt</h2><hr>
    <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1') ;?>" method="POST" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="title">Názov*:</label> <!-- ta hviezdicka znamena, ze to je pozadovane "required" -->
            <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
        </div>
        <div class="form-group col-md-3">
            <label for="brand">Značka*:</label>
            <select class="form-control" name="brand" id="brand">
                <option value=""<?=(($brand == '')?' selected':'') ;?>></option>
                <?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
                    <option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'') ;?>><?=$b['brand'];?></option>
                <?php endwhile; ?>
            </select> <!-- name ma nieco spolocne s POST metodou, je to key "kluc" -->
        </div>
        <div class="form-group col-md-3">
            <label for="parent">Rodič kategórie*:</label> 
            <select class="form-control" name="parent" id="parent">
                <option value=""<?=(($parent == '')?' selected':'') ;?>></option>
                <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
                    <option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?' selected':'') ;?>><?=$p['category'] ;?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
                <label for="child">Dieťa kategórie*:</label>
                <select class="form-control" name="child" id="child"></select> <!-- medzi selectom nic nemam, lebo to riesim v JS vo footeri -->
        </div>
        <div class="form-group col-md-3">
                <label for="price">Cena*:</label>
                <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
        </div>
        <div class="form-group col-md-3">
                <label for="list_price">List Price:</label>
                <input type="text" id="list_price" name="list_price" class="form-control" value="<?=$list_price;?>">
        </div>
        <?php if($saved_image != ''):?>
        <div class="form-group col-md-6">
            <label for="description">Popis:</label>
            <textarea name="description" id="description" class="form-control" rows="6"><?=$description;?></textarea>
        </div>
        <div class="form-group col-md-6">
            <?php if($saved_image != ''): ?>
                <div class="saved-image">
                    <img src="<?=$saved_image ;?>" alt="saved image"><br>
                    <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Vymazať obrázok</a>
                </div>
            <?php else: ?>
                <label for="photo">Fotka produktu:</label>
                <input type="file" name="photo" id="photo" class="form-control">
            <?php endif;?>
        </div>
        <?php else: ?>
        <div class="form-group col-md-6">
                <label for="photo">Fotka produktu:</label>
                <input type="file" name="photo" id="photo" class="form-control">
        </div>
        <div class="form-group col-md-6">
                <label for="description">Popis:</label>
                <textarea name="description" id="description" class="form-control" rows="6"><?=$description;?></textarea>
        </div>
        <?php endif; ?>
        <div class="form-group col-md-3">
                <label for="available">K dispozícii*:</label>
                <input type="text" id="available" name="available" class="form-control" value="<?=$available;?>">
        </div>
        <div class="form-group pull-right">
            <a href="products.php" class="btn btn-default">Zatvoriť</a>
            <input type="submit" value="<?=((isset($_GET['edit']))?'Upraviť':'Pridať nový') ;?> produkt" class="btn btn-success">
        </div><div class="clearfix"></div>
    </form>
<?php
}
else{


$sql = "SELECT * FROM products WHERE deleted = 0"; //vyberie vsetky produkty, ktore nie su odstranene
$presults = $db->query($sql);
if(isset($_GET['featured'])){
    $id = (int)$_GET['id'];
    $featured = (int)$_GET['featured'];
    $featuredSql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
    $db->query($featuredSql);
    header('Location: products.php');
}
?>
<h2 class="text-center ideTo2">Produkty</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Pridať produkt</a><div class="clearfix"></div> <!-- pull-right nam to da doprava to tlacidlo -->
<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead><th></th><th>Produkt</th><th>Cena</th><th>Kategória</th><th>Vybrané</th><th>K dispozícii</th></thead>
    <tbody>
        <?php while($product = mysqli_fetch_assoc($presults)): 
                $childID = $product['categories'];
                $catSql = "SELECT * FROM categories WHERE id = '$childID'";
                $result = $db->query($catSql);
                $child = mysqli_fetch_assoc($result);
                $parentID = $child['parent'];
                $pSql = "SELECT * FROM categories WHERE id = '$parentID'";
                $presult = $db->query($pSql);
                $parent = mysqli_fetch_assoc($presult);
                $category = $parent['category'].'~'.$child['category'];
            ?>
            <tr>
                <td>
                    <a class="btn btn-xs btn-default" href="products.php?edit=<?= $product['id'];?>"><span class="glyphicon glyphicon-pencil"></span></a> <!-- btn-xs = extra small -->
                    <a class="btn btn-xs btn-default" href="products.php?delete=<?= $product['id'];?>"><span class="glyphicon glyphicon-remove"></span></a>
                </td>
                <td><?= $product['title'];?></td>
                <td><?=money($product['price']);?></td> <!-- tato funkcia je z helpers a dava nam € za cenu -->
                <td><?=$category ;?></td>
                <td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0'); ?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
                    <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus'); ?>"></span> 
                    </a>&nbsp <?= (($product['featured']==1))?'Featured Product':'' ;?></td>
                <td><?=$product['available'];?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php }include 'includes/footer.php'; ?>
<script>
    jQuery('document').ready(function(){
        get_child_options('<?=$category;?>');
    });
</script>