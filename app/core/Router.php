<?php
class Router {
    public function route() {
        $page = $_GET['page'] ?? 'orders';
        switch ($page) {
            case 'orders':
                require 'app/controllers/ProductController.php';
                $controller = new ProductController();
                $controller->index();
                break;
            case 'details':
                require 'app/controllers/OrderController.php';
                $controller = new OrderController();
                $controller->show($_REQUEST['id']);
                break;    
            case 'my-orders':
                require 'app/controllers/OrderController.php';
                $controller = new OrderController();
                $controller->index();
                break;   
            case 'create-order':
                require 'app/controllers/OrderController.php';
                $controller = new OrderController();
                $controller->create();
                break;    
            case 'store-order':
                require 'app/controllers/OrderController.php';
                $controller = new OrderController();
                $controller->store();
                break; 
            case 'shop':
                require 'app/controllers/ProductController.php';
                $controller = new ProductController();
                $controller->index();
                break;
            case 'cart':
                require 'app/views/cart/cart.php';
                break;
            case 'add-to-cart':
                require 'app/controllers/CartController.php';
                $controller = new CartController();
                $controller->addToCart();
                break;
            case 'remove-from-cart':
                require 'app/controllers/CartController.php';
                $controller = new CartController();
                $controller->removeFromCart();
                break;
            case 'checkout':
                    require 'app/views/cart/checkout.php';
                    break;
                
            case 'init-payment':
                    require 'app/controllers/PaymentController.php';
                    $controller = new PaymentController();
                    $controller->initPayment();
                    break;
            case 'addpayment':
                    require 'app/controllers/PaymentController.php';
                    $controller = new PaymentController();
                    $controller->paymentAdd();
                    break;      
           
            case 'payment_callback':
                require 'app/controllers/PaymentController.php';
                $controller = new PaymentController();
                $controller->paymentSuccess();     
                    echo '<div id="payment-notification" class="notification" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    background: linear-gradient(135deg, #28a745, #218838);
                    color: white;
                    padding: 20px;
                    text-align: center;
                    font-size: 18px;
                    font-weight: bold;
                    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
                    opacity: 0;
                    transform: translateY(-100%);
                    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
                    z-index: 9999;
                    ">
                        Payment Successful!
                    </div>';
                    
                    echo '<script>
                        setTimeout(function() {
                            let notification = document.getElementById("payment-notification");
                            notification.style.opacity = "1";
                            notification.style.transform = "translateY(0%)";
                    
                            setTimeout(function() {
                                notification.style.opacity = "0";
                                notification.style.transform = "translateY(-100%)";
                                setTimeout(function() {
                                    window.top.location.href = "?page=shop"; // Redirect after the animation
                                }, 500);
                            }, 4000);
                        }, 500);
                    </script>';
                break;                
                    case 'payment_return':                    
                    echo '<div id="payment-notification" class="notification" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    background: linear-gradient(135deg,rgb(255, 8, 8),rgb(255, 1, 1));
                    color: white;
                    padding: 20px;
                    text-align: center;
                    font-size: 18px;
                    font-weight: bold;
                    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
                    opacity: 0;
                    transform: translateY(-100%);
                    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
                    z-index: 9999;
                    ">
                        Payment Callback Received!!
                    </div>';
                    
                    echo '<script>
                        setTimeout(function() {
                            let notification = document.getElementById("payment-notification");
                            notification.style.opacity = "1";
                            notification.style.transform = "translateY(0%)";
                    
                            setTimeout(function() {
                                notification.style.opacity = "0";
                                notification.style.transform = "translateY(-100%)";
                            }, 4000);
                        }, 500);
                    </script>';
                break;    
            default:
                echo "Page not found";
                break;
        }
    }
}
?>
