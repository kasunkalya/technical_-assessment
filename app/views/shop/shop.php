<?php
$title = "Shop";
ob_start();
?>
<h2>Shop</h2>
<div class="container">
    <div class="row">
        <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                    <p class="card-text">EGP <?php echo $product['price']; ?></p>
                    <form method="POST" action="?page=add-to-cart">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="number" name="quantity" min="1" value="1" class="form-control mb-2">
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . "/../layout/layout.php";
?>