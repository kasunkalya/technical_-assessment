<?php

require 'app/models/Product.php';

class ProductController {
    public function index() {
        $productModel = new Product();
        $products = $productModel->getAllProducts();
        require 'app/views/shop/shop.php';
    }
}
