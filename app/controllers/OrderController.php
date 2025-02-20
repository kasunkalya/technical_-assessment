<?php
require 'app/models/Order.php';
require 'app/models/Product.php';

class OrderController {
    public function index() {
        $orderModel = new Order();
        $orders = $orderModel->getAllOrders();
        require 'app/views/orders/index.php';
    }

    public function create() {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        require 'app/views/orders/create.php';
    }

    public function show($orderId) {
        $orderModel = new Order();
        $order = $orderModel->getOrderDetails($orderId);
        $refunds = $orderModel->getRefundHistory($orderId);

        if (!$order) {
            die("Order not found");
        }

        require 'app/views/orders/order_details.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
            $productIds = $_POST['product_id'];
            $quantities = $_POST['quantity'];
    
            $orderModel = new Order();
            $orderId = $orderModel->createOrderFromCart($productIds, $quantities);
    
            if ($orderId) {             
                unset($_SESSION['cart']);    
                header("Location: ?page=checkout&order_id=" . $orderId);
                exit();
            } else {
                echo "Failed to create order.";
            }
        } else {
            echo "Invalid order request.";
        }
    }
    
}
?>