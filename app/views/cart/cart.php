<?php
$title = "Cart";
ob_start();
session_start();

?>
<?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) { ?>
    <div class='container mt-5'><div class='alert alert-warning'>Your cart is empty.</div></div>
 
<?php }else{ ?>
<div class="container mt-5">
    <h2 class="mb-4">Shopping Cart</h2>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                <td>EGP <?php echo number_format($item['price'], 2); ?></td>
                <td>EGP <?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <a href="?page=remove-from-cart&id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center">
        <h3>Total: EGP <?php echo number_format($total, 2); ?></h3>
        <div>
            <a href="?page=shop" class="btn btn-secondary">Continue Shopping</a>
            <form action="?page=store-order" method="POST" class="d-inline">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <input type="hidden" name="product_id[]" value="<?php echo $item['id']; ?>">
                    <input type="hidden" name="quantity[]" value="<?php echo $item['quantity']; ?>">
                <?php endforeach; ?>
                <button type="submit" class="btn btn-success">Checkout</button>
            </form>
        </div>
    </div>
</div>
<?php } ?>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layout/layout.php";
?>
