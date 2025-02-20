<?php
$title = "Order";
ob_start();
session_start();

?>
<div class="container mt-5">
    <h2>My Orders</h2>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['customer_name']; ?></td>                
                <td>EGP <?php echo number_format($order['total'], 2); ?></td>
                <td>
                    <?php
                        if ($order['status'] == 1) {
                            echo '<span class="badge bg-success">Paid</span>';
                        } elseif ($order['status'] == 2) {
                            echo '<span class="badge bg-warning text-dark">Refund Requested</span>';
                        } else {
                            echo '<span class="badge bg-danger">Pending Payment</span>';
                        }
                    ?>
                </td>
                <td>
                    <a href="?page=details&id=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">
                        View Details
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout/layout.php";
?>
