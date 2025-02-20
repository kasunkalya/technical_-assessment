<?php
class Order {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function getAllOrders() {
        $result = $this->db->query("SELECT orders.id, orders.total, orders.status, 
                        customers.name AS customer_name, customers.email
                FROM orders 
                JOIN customers ON orders.customer_id = customers.id
                ORDER BY orders.id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    } 


    public function createOrderFromCart($productIds, $quantities) {
        $total = 0;
    

        $products = [];
        foreach ($productIds as $index => $productId) {
            $stmt = $this->db->prepare("SELECT name, price FROM products WHERE id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
    
            if (!$result) {
                continue; 
            }
    
            $products[$productId] = $result;
            $subtotal = $result['price'] * $quantities[$index];
            $total += $subtotal;
        }
    
      
        $stmt = $this->db->prepare("INSERT INTO orders (total, status) VALUES (?, 'pending')");
        $stmt->bind_param("d", $total);
        $stmt->execute();
        $orderId = $stmt->insert_id;    
      
        foreach ($productIds as $index => $productId) {
            if (!isset($products[$productId])) {
                continue; 
            }
    
            $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $orderId, $productId, $quantities[$index], $products[$productId]['price']);
            $stmt->execute();
        }
    
        return $orderId;
    }
    public function updateOrder($postData) {       
        $stmt = $this->db->prepare("SELECT id FROM customers WHERE email = ?");
        $stmt->bind_param("s", $postData['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        $date=date("Y-m-d H:i:s");
    
        if ($customer) {           
            $customerId = $customer['id'];
                $stmt = $this->db->prepare("UPDATE customers 
                                SET phone = ?, address = ?, city = ?, state = ?, country = ?, zip = ?, 
                                    shipping_name = ?, shipping_address = ?, shipping_city = ?, shipping_state = ?, shipping_country = ?, shipping_areacode = ?, updated_at = ?
                                WHERE id = ?");
                $stmt->bind_param(
                    "ssssssssssssss",
                    $postData['phone'],
                    $postData['address'],
                    $postData['city'],
                    $postData['state'],
                    $postData['country'],
                    $postData['zip'],
                    $postData['shipping_name'],
                    $postData['shipping_address'],
                    $postData['shipping_city'],
                    $postData['shipping_state'],
                    $postData['shipping_country'],
                    $postData['shipping_zip'],
                    $date,
                    $customerId
                );
                $stmt->execute();
        } else { 
                
            $stmt = $this->db->prepare("INSERT INTO customers (name, email, phone, address, city, state, country, zip, created_at, shipping_name, shipping_address, shipping_city, shipping_country, shipping_state, shipping_areacode) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "sssssssssssssss",
                $postData['name'],
                $postData['email'],
                $postData['phone'],
                $postData['address'],
                $postData['city'],
                $postData['state'],
                $postData['country'],
                $postData['zip'],
                $date,
                $postData['shipping_name'],
                $postData['shipping_address'],
                $postData['shipping_city'],
                $postData['shipping_state'],
                $postData['shipping_country'],
                $postData['shipping_zip']
            );
            $stmt->execute();
            $customerId = $stmt->insert_id;
        }    
     
        $stmt = $this->db->prepare("UPDATE orders 
                                    SET customer_id = ? 
                                    WHERE id = ?");
        $stmt->bind_param("ii", $customerId, $postData['orderId']);
        
        return $stmt->execute();
    }
    

    public function getOrderById($orderId) {      
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderDetailsById($postData) {
        $stmt = $this->db->prepare("
            SELECT 
                o.id AS order_id, 
                o.total, 
                o.status, 
                o.created_at, 
                c.id AS customer_id, 
                c.name, 
                c.email, 
                c.phone, 
                c.address, 
                c.city, 
                c.state, 
                c.country, 
                c.zip, 
                c.shipping_name, 
                c.shipping_address, 
                c.shipping_city, 
                c.shipping_state, 
                c.shipping_country, 
                c.shipping_areacode 
            FROM orders o
            JOIN customers c ON o.customer_id = c.id
            WHERE o.id = ?
        ");
        
        $stmt->bind_param("i", $postData['orderId']);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getOrderDetails($orderId) {
        $stmt = $this->db->prepare("
            SELECT 
                o.id AS order_id, 
                o.total, 
                o.status, 
                o.created_at, 
                c.id AS customer_id, 
                c.name, 
                c.email, 
                c.phone, 
                c.address, 
                c.city, 
                c.state, 
                c.country, 
                c.zip, 
                c.shipping_name, 
                c.shipping_address, 
                c.shipping_city, 
                c.shipping_state, 
                c.shipping_country, 
                c.shipping_areacode 
            FROM orders o
            JOIN customers c ON o.customer_id = c.id
            WHERE o.id = ?
        ");
        
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        if ($order) {     
            $order['items'] = $this->getOrderItems($orderId);
        }

        return $order;
    }    

    public function getOrderItems($orderId) {
        $stmt = $this->db->prepare("
            SELECT 
                oi.product_id, 
                p.name AS product_name, 
                oi.quantity, 
                oi.price, 
                (oi.quantity * oi.price) AS subtotal
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    
        return $items;
    }   

}
?>
