<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/eshop/core/init.php';
$parentID = (int)$_POST['parentID'];
$selected = sanitize($_POST['selected']);
$childQuery = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");
ob_start(); //nieco s bufferom ?> 
    <option value=""></option>
    <?php while($child = mysqli_fetch_assoc($childQuery)): ?>
        <option value="<?=$child['id']; ?>"<?=(($selected == $child['id'])?' selected':'');?>><?=$child['category'] ;?></option>
    <?php endwhile; ?>
<?php
    echo ob_get_clean(); //vycisti pamat
?>