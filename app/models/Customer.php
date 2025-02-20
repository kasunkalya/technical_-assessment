<?php
class Customer {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function getAllOrders() {
        $result = $this->db->query("SELECT * FROM customers");
        return $result->fetch_all(MYSQLI_ASSOC);
    } 

    public function getOrderById($orderId) {
      
        $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
