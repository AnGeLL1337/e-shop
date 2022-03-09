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
    <section class="signup-form">
        <div class="wrap">
        <h2>Registrácia</h2>
        <form action="includes/signup.inc.php" method="post">
            <input type="text" name="name" placeholder="Celé meno..">
            <input type="text" name="email" placeholder="Email..">
            <input type="text" name="nickname" placeholder="Uživateľské meno..">
            <input type="password" name="pwd" placeholder="Heslo..">
            <input type="password" name="pwdAgain" placeholder="Kontrola hesla..">
            <input type="submit" value="Zaregistrujte sa" name="submit">
        </form>
        </div>
        <?php
        ?>
    </section>

    
    <?php
    include 'includes/footer.php';
 ?>