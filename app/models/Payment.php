<?php
class Payment {

    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function processPayment() {  
        return ['status' => 'success', 'transaction_id' => rand(1000, 9999)];
    }


    private $profileID = "132344";
    private $integrationKey = "SWJ992BZTN-JHGTJBWDLM-BZJKMR2ZHT";

    public function createPaymentRequest($postData) {      
     

        $order_id = $postData['order_id'];
        $amount = $postData['total']; 
        $cartID = uniqid();

        $data = [
            "profile_id" => "$this->profileID",
            "tran_type" => "sale",
            "tran_class" => "ecom",
            "cart_id" => $cartID,
            "cart_currency" => "EGP",
            "cart_amount" => $amount,
            "cart_description" => "$order_id",
            "customer_details" => [
                "name" => $postData['name'],
                "email" => $postData['email'],
                "phone" => $postData['phone'],
                "street1" =>$postData['address'],
                "city" =>$postData['city'],
                "state" =>$postData['state'],
                "country" =>$postData['country'],
                "zip" =>$postData['zip']
            ],
            "shipping_details" => [
                "name" => $postData['shipping_name'],
                "email" => $postData['email'],
                "phone" => $postData['phone'],
                "street1" => $postData['shipping_address'],
                "city" => $postData['shipping_city'],
                "state" => $postData['shipping_state'],
                "country" => $postData['shipping_country'],
                "zip" => $postData['shipping_areacode']
            ],
            "callback" => "http://localhost/cart/?page=payment_return",
            "return" =>  "http://localhost/cart/?page=payment_callback",
            "payment_methods" => ["card"],
            "framed"=>true,
            "framed_return_top"=>true
        ];
        
        $ch = curl_init("https://secure-egypt.paytabs.com/payment/request");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: $this->integrationKey"
        ]);
        
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
      
        
        if (!empty($response['redirect_url'])) {
            session_start(); 
            $_SESSION['tran_ref'] = $response['tran_ref'];          
            echo json_encode(['redirect_url' => $response['redirect_url']]);
            exit();
        } else {
            echo json_encode(['error' => 'Failed to initialize payment']);
        }
    }
    public function getPaymentStatus() {   

        session_start();
        $tranRef = $_SESSION['tran_ref'];      
        $ch = curl_init("https://secure-egypt.paytabs.com/payment/query");
       
        $data = [
            "profile_id" => "$this->profileID",
            "tran_ref" => $tranRef
        ];
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: $this->integrationKey"
        ]);
    
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);     
        file_put_contents('paytabs_status_log.txt', print_r($response, true), FILE_APPEND);
    
        return $response;

    }


    public function paymentSuccess($paymentData) {           
        if (isset($paymentData['payment_result']['response_status'])) {
            $status = $paymentData['payment_result']['response_status'];
            $orderID = $paymentData['cart_description'];
          
                $query = "INSERT INTO `payments` (`order_id`, `status`, `tran_ref`, `tran_type`, `cart_id`, `cart_currency`, `cart_amount`, `response_status`, `response_code`, `transaction_time`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt =$this->db->prepare($query);
                if ($stmt) {    
                    $stmt->bind_param(
                        "ssssssdsss", 
                        $paymentData['cart_description'],
                        $paymentData['payment_result']['response_status'],
                        $paymentData['tran_ref'],
                        $paymentData['tran_type'],
                        $paymentData['cart_description'],
                        $paymentData['cart_currency'],
                        $paymentData['cart_amount'],
                        $paymentData['payment_result']['response_status'],
                        $paymentData['payment_result']['response_code'],
                        $paymentData['payment_result']['transaction_time']
                    );
                    $stmt->execute();
                }  

            if ($status === 'A') {
                unset($_SESSION['cart']); 
                $query = "UPDATE orders SET status='1' WHERE id=?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("s", $orderID);
                $stmt->execute();
                http_response_code(200);
            } else {
                $query = "UPDATE orders SET status='2' WHERE id=?";
                $stmt = $this->db->prepare($query);
                $stmt->bind_param("s", $orderID);
                $stmt->execute();
                http_response_code(400); 
            }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid Payment Data']);
            }

    }



}
