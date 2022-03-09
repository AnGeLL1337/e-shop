<?php
$sql = "SELECT * FROM categories WHERE parent = 0";
$pquery = $db->query($sql);
?>

    <header>
        <div id="container1">
            
                <?php if(isset($_SESSION['userid'])):?>
                <a href="account.php">Môj účet</a>
                <a href="rate.php">Zhodnoť</a>
                <a href="reviews.php">Recenzie</a>
                <a href="includes/logout.inc.php">Odhlásiť sa</a>
                <?php endif;
                    if(!isset($_SESSION['userid'])):
                ?>
                <a href="signup.php">Zaregistrovať sa</a>
                <a href="login.php">Prihlásiť sa</a>
                <?php endif; ?>
  
        </div>
        <div id="container2">
            <ul>
                <li><a href="index.php">Domov</a></li>
                <?php while($parent = mysqli_fetch_assoc($pquery)) : ?>
                    <?php 
                        $parent_id = $parent['id'];
                        $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                        $cquery = $db->query($sql2);
                    ?>
                <li><a href="#"><?php echo $parent['category'];?></a>
                    <ul class="dropdown">
                        <?php while($child = mysqli_fetch_assoc($cquery)) : ?>
                            <li><a href="category.php?cat=<?=$child['id']; ?>"><?php echo $child['category'];?></a></li>
                        <?php endwhile; ?>
                    </ul>
                </li>
                <?php endwhile; ?>
                <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Môj košík</a></li>
            </ul>
        </div>
    </header>
    <div class="skuskaBody">