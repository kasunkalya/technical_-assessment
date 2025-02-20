<?php
session_start();
require 'app/models/Order.php';
require 'app/models/Product.php';

class CartController {
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $productModel = new Product();
            $product = $productModel->getProductById($productId);

            if ($product) {
                $_SESSION['cart'][$productId] = [
                    'id' => $productId,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity
                ];
            }
        }
        header("Location: ?page=cart");
        exit();
    }

    public function removeFromCart() {
        if (isset($_GET['id'])) {
            $productId = $_GET['id'];
            unset($_SESSION['cart'][$productId]);
        }
        header("Location: ?page=cart");
        exit();
    }
}
