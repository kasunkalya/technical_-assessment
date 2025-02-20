<?php
require 'app/models/Order.php';
require 'app/models/Product.php';
require 'app/models/Customer.php';

class CustomerController {
    public function index() {
        $CustomerModel = new Customer();
        $orders = $CustomerModel->getOrderById();
        require 'app/views/orders/index.php';
    }    
    
}
?>