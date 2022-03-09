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


$sql1 = "SELECT * FROM products";
$result1 = mysqli_query($db, $sql1);

$sql2 = "SELECT * FROM rating";
$result2 = mysqli_query($db, $sql2);
$pocetRiadkov2 = mysqli_num_rows($result2);
?>

<body>
    <div class="container-fluid-reviews">

        <h2>Hodnotenie produktov</h2>
        <section class="prvaCast">
            <?php while ($row1 = mysqli_fetch_assoc($result1)) : ?>
                <?php $sucet = $row1['dobre'] + $row1['zle'];
                if ($sucet != 0) {
                    $pocetKladnych = $row1['dobre'];
                    $vysledok = round(($pocetKladnych / $sucet) * 100, 2);
                }
                ?>
                <div class="okna">
                    <h3><?= $row1['title']; ?></h3>
                    <h4><?= $row1['price']; ?>€</h4>
                    <?php if ($sucet != 0) : ?>
                        <h4>Percentá spokojných: <?= $vysledok; ?>%</h4>
                    <?php endif;
                    if ($sucet == 0) :
                    ?>
                        <h4>Zatiaľ žiadna recenzia na tento produkt.</h4>
                    <?php endif; ?>
                </div>
            <?php
                $sucet = 0;
                $pocetKladnych = 0;
                $vysledok = 0;
            endwhile; ?>
            <br>
        </section>
        <hr style="height: 5px; color: black; background-color: black; width: 90%;">
        <h2>Recenzie</h2>
        <section class="druhaCast">
            <?php while ($row2 = mysqli_fetch_assoc($result2)) : ?>
                <div class="<?= $row2['rate'] == 1 ? "recenziaDobra" : "recenziaZla" ?>">
                    <h4 class="izba">Apartmán: <?= $row2['product']; ?></h4>
                    <h4>Prezývka: <?= $row2['nickname']; ?></h4>
                    <h4 class="<?= $row2['rate'] == 1 ? "recenziaGood" : "recenziaBad" ?>"><?= ($row2['rate'] == 1) ? "Odporúča" : "Neodporúča"; ?></h4>
                    <h4 style="">Samotná recenzia: <?= $row2['review']; ?></h4>
                    <div class="<?= $row2['rate'] == 1 ? "recenziaGood1" : "recenziaBad1" ?>"></div>
                    <br>
                </div>
            <?php endwhile; ?>
        </section>
        <?php
        include 'includes/footer.php';
        ?>