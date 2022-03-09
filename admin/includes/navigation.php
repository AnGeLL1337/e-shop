<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="/eshop/admin/index.php" class="navbar-brand">eShop</a>
        <ul class="nav navbar-nav">
            <li><a href="brands.php">Značky</a></li>
            <li><a href="categories.php">Kategórie</a></li>
            <li><a href="products.php">Produkty</a></li>
            <?php if(has_permission('admin')): ?>
            <li><a href="users.php">Užívatelia</a></li>
            <?php endif; ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Zdravím <?=$user_data['first'];?>!
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="change_password.php">Zmeniť heslo</a></li>
                    <li><a href="logout.php">Odlhásiť sa</a></li>
                </ul>
            </li>
    </div>
</nav>