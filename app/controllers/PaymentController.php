<?php
require 'app/models/Payment.php';
require 'app/models/Order.php';

class PaymentController {
    public function checkout() {
        $paymentModel = new Payment();
        $result = $paymentModel->processPayment();

        if ($result['status'] == 'success') {
            header("Location: ?page=success");
        } else {
            header("Location: ?page=error");
        }
    }

    public function paymentAdd() {
        $paymentModel = new Payment();
        $paymentUrl = $paymentModel->createPaymentRequest();

        if ($paymentUrl) {
            header("Location: $paymentUrl");
            exit();
        } else {
            echo "Payment request failed!";
        }
    }

    public function initPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel = new Order();
            $orderId = $orderModel->updateOrder($_POST);
            $orderDetails = $orderModel->getOrderDetailsById($_POST);

            $paymentModel = new Payment();
            $paymentUrl = $paymentModel->createPaymentRequest($orderDetails);
                        
            echo json_encode(['redirect_url' => $paymentUrl]);
        }
    }
    public function paymentSuccess() {
        $paymentModel = new Payment();
        $paymentstatus = $paymentModel->getPaymentStatus();     
        $paymentUrl = $paymentModel->paymentSuccess($paymentstatus);         
    
    }
}
