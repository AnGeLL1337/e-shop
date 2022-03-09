<h3 class="text-center">Nákupný košík</h3>
<div>
<?php if(empty($cart_id)): ?>
    <p>Tvoj nákupný košík je prázdny.</p>
<?php else: 
    $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
    $results = mysqli_fetch_assoc($cartQ);
    $items = json_decode($results['items'],true);
    $sub_total = 0;    
?>
    <table class="table table-condensed" id="cart_widget">
        <tbody>
            <?php foreach($items as $item):
                $productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}'");
                $product = mysqli_fetch_assoc($productQ);
            ?>
            <tr>
                <td><?= $item['quantity'];?></td>
                <td><?= $product['title'];?></td>
                <td><?= money($item['quantity'] * $product['price']);?></td>
            </tr>
            <?php 
            $sub_total += ($item['quantity'] * $product['price']);
            endforeach; ?>
            <tr>
                <td></td>
                <td>Súčet</td>
                <td><?= money($sub_total);?></td>
            </tr>
        </tbody>
    </table>
    <a href="cart.php" class="btn btn-xs btn-primary pull-right">Košík</a><br>

<?php endif; ?>
</div>