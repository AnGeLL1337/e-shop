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

if (isset($_POST['submit'])) {
    $vybranyProdukt = $_POST['vybranyProdukt'];
}
?>

<body>
    <div class="container-fluid-rate">
        <section class="rate-form">
            <div class="wrapper">
                <form class="form-area" action="includes/rate.inc.php" method="post">
                    <div class="msg-area">
                        <label for="msg">Recenzia</label>
                        <textarea id="msg" name="recenzia"></textarea>
                    </div>
                    <div class="details-area">
                        <div>
                            <label for="name">Vybraný produkt</label>
                            <input type="text" name="produkt" id="produkt" value="<?= (!empty($vybranyProdukt)) ? $vybranyProdukt : "" ?>"><br>
                        </div>
                        <input type="radio" id="dobre" name="hodnotenie" value="1">
                        <label for="dobre">Spokojný</label><br>
                        <input type="radio" id="zle" name="hodnotenie" value="2">
                        <label for="zle">Nespokojný</label><br>

                        <button type="submit" name="submit">Pridajte recenziu</button>
                    </div>
                </form>
            </div>
            <?php
            ?>
        </section>


        <?php
        include 'includes/footer.php';
        ?>