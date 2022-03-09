<?php
function nahoda() //aby sme nemuseli refresovat 
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
    require_once '../core/init.php'; //aby sme boli pripojeny na server
    if(!is_logged_in()){
        header('Location: login.php');
    }
    include 'includes/head.php';
    include 'includes/navigation.php';

    $txnQuery = "SELECT t.id, t.cart_id, t.full_name, t.txn_date, t.grand_total, c.items, c.paid, c.shipped
        FROM transactions t
        LEFT JOIN cart c ON t.cart_id = c.id
        WHERE c.paid = 1 AND c.shipped = 0
        ORDER BY t.txn_date";
    $txnResults = $db->query($txnQuery);
?>
<div class="col-md-12">
    <h3 class="text-center">Objednávky na odoslanie</h3>
    <table class="table table-condensed table-bordered table-striped">
        <thead>
            <th></th><th>Meno</th><th>Suma</th><th>Dátum</th>
        </thead>
        <tbody>
            <?php while($order = mysqli_fetch_assoc($txnResults)): ?>
                <tr>
                    <td><a href="orders.php?txn_id=<?=$order['id'];?>" class="btn btn-xs btn-info">Detaily</a></td>
                    <td><?=$order['full_name'];?></td>
                    <td><?= money($order['grand_total']);?></td>
                    <td><?=pretty_date($order['txn_date']);?></td>
                </tr>
            <?php endwhile;?>
        </tbody>
    </table>
</div>
<?php
    include 'includes/footer.php';
?>