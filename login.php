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

require_once 'core/init.php'; // require znamena vyzadovat
include 'includes/head.php';
include 'includes/navigation.php';
?>

<body>
    <div class="container-fluid-signup">
        <section class="login-form">
            <div class="wrap">
                <h2>Prihlásenie</h2>
                <form action="includes/login.inc.php" method="post">
                    <input type="text" name="email" placeholder="Email..">
                    <input type="password" name="pwd" placeholder="Heslo..">
                    <input type="submit" value="Prihlaste sa" name="submit">
                </form>
            </div>
            <?php
            ?>
        </section>


        <?php
        include 'includes/footer.php';
        ?>