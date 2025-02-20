<?php
$title = "Order Details";
ob_start();
session_start();
?>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white text-center">
            <h2>Order Details (#<?= $order['order_id']; ?>)</h2>
        </div>
        <div class="card-body">            
            
            <h3 class="text-primary">Customer Details</h3>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> <?= htmlspecialchars($order['name']); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>
                </div>
            </div>

            <hr>
          
            <h3 class="text-primary">Order Summary</h3>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Total Amount:</strong> $<?= number_format($order['total'], 2); ?></p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-<?= ($order['status'] == '1') ? 'success' : 'warning'; ?>">
                            <?php
                            if($order['status'] ==1){
                                echo 'Paid';
                            }elseif($order['status'] ==2){
                                echo 'Refund Requested';
                            }else{
                                echo 'Pending Payments';
                            }
                            ?>
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Placed On:</strong> <?= date('F j, Y', strtotime($order['created_at'])); ?></p>
                </div>
            </div>

            <hr>

            <h3 class="text-primary">Items in this Order</h3>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']); ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td>$<?= number_format($item['price'], 2); ?></td>
                        <td>$<?= number_format($item['quantity'] * $item['price'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <hr>

           
            <?php if ($order['status'] == 'Completed'): ?>
            <div class="text-center">
                <button id="refund-btn" data-order-id="<?= $order['order_id']; ?>" class="btn btn-danger btn-lg">
                    Request Refund
                </button>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
document.getElementById("refund-btn")?.addEventListener("click", function() {
    let orderId = this.getAttribute("data-order-id");

    if (!confirm("Are you sure you want to request a refund?")) return;

    fetch("request_refund.php?order_id=" + orderId, {
        method: "GET"
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            location.reload();
        }
    })
    .catch(error => console.error("Error:", error));
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout/layout.php";
?>
