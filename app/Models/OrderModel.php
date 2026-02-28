<?php
namespace App\Models;

use Core\Database;
use PDO;

class OrderModel {
    protected $conn;
    private $table = 'orders';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả đơn hàng với phân trang
    public function getAll($page = 1, $limit = 10, $search = '', $status = null) {
        $offset = ($page - 1) * $limit;
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (o.full_name LIKE :search OR o.phone_number LIKE :search OR o.id = :order_id)";
            $params[':search'] = '%' . $search . '%';
            $params[':order_id'] = is_numeric($search) ? $search : 0;
        }

        if ($status) {
            $where .= " AND o.status = :status";
            $params[':status'] = $status;
        }

        $query = "SELECT o.*, u.full_name as user_name, u.email as user_email
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE $where
                  ORDER BY o.created_at DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số đơn hàng
    public function countAll($search = '', $status = null, $userId = null) {
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (full_name LIKE :search OR phone_number LIKE :search OR id = :order_id)";
            $params[':search'] = '%' . $search . '%';
            $params[':order_id'] = is_numeric($search) ? $search : 0;
        }

        if ($status) {
            $where .= " AND status = :status";
            $params[':status'] = $status;
        }

        if ($userId) {
            $where .= " AND user_id = :user_id";
            $params[':user_id'] = $userId;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE $where";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Lấy đơn hàng theo ID
    public function findById($id) {
        $query = "SELECT o.*, u.full_name as user_name, u.email as user_email
                  FROM " . $this->table . " o
                  LEFT JOIN users u ON o.user_id = u.id
                  WHERE o.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn hàng
    public function getOrderDetails($orderId) {
        $query = "SELECT od.*, p.name as product_name, p.image_url
                  FROM order_details od
                  JOIN products p ON od.product_id = p.id
                  WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($id, $paymentStatus) {
        $query = "UPDATE " . $this->table . " SET payment_status = :payment_status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':payment_status', $paymentStatus);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy thống kê đơn hàng
    public function getStatistics() {
        $query = "SELECT
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_orders,
                    SUM(CASE WHEN status = 'shipping' THEN 1 ELSE 0 END) as shipping_orders,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                    SUM(total_amount) as total_revenue
                  FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy đơn hàng theo user
    public function getByUserId($userId, $page = 1, $limit = 10, $status = null) {
        $offset = ($page - 1) * $limit;
        $where = "user_id = :user_id";
        $params = [':user_id' => $userId];
        
        if ($status) {
            $where .= " AND status = :status";
            $params[':status'] = $status;
        }

        $query = "SELECT * FROM " . $this->table . " WHERE $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tổng doanh thu
    public function getTotalRevenue() {
        $query = "SELECT SUM(total_amount) as revenue FROM " . $this->table . " WHERE status != 'cancelled'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($result['revenue'] ?? 0);
    }

    // Lấy dữ liệu đơn hàng theo tháng (12 tháng gần nhất)
    public function getMonthlyOrders() {
        $query = "SELECT
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as orders,
                    SUM(total_amount) as revenue
                  FROM " . $this->table . "
                  WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                  ORDER BY month ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy đơn hàng gần đây
    public function getRecentOrders($limit = 5) {
        $query = "SELECT
                    o.id, o.total_amount, o.status, o.created_at,
                    u.full_name as customer_name
                  FROM " . $this->table . " o
                  JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Transaction management methods
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    public function commit() {
        return $this->conn->commit();
    }

    public function rollback() {
        return $this->conn->rollback();
    }

    // Create order with transaction
    public function createOrder($data) {
        $query = "INSERT INTO orders
                  (user_id, full_name, phone_number, shipping_address, total_amount, shipping_fee,
                   discount_amount, voucher_id, note, status, payment_method, payment_status)
                  VALUES
                  (:user_id, :full_name, :phone_number, :shipping_address, :total_amount, :shipping_fee,
                   :discount_amount, :voucher_id, :note, :status, :payment_method, :payment_status)";

        $stmt = $this->conn->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Create order details
    public function createOrderDetails($orderId, $cartItems) {
        $query = "INSERT INTO order_details (order_id, product_id, quantity, price)
                  VALUES (:order_id, :product_id, :quantity, :price)";

        $stmt = $this->conn->prepare($query);

        foreach ($cartItems as $item) {
            $stmt->bindValue(':order_id', $orderId);
            $stmt->bindValue(':product_id', $item['product_id']);
            $stmt->bindValue(':quantity', $item['quantity']);
            $stmt->bindValue(':price', $item['price']);

            if (!$stmt->execute()) {
                throw new \Exception('Không thể tạo chi tiết đơn hàng');
            }
        }
    }

    // Check if user has purchased a specific product
    public function hasUserPurchasedProduct($userId, $productId) {
        $query = "SELECT COUNT(*) as count
                  FROM orders o
                  JOIN order_details od ON o.id = od.order_id
                  WHERE o.user_id = :user_id
                  AND od.product_id = :product_id
                  AND o.status IN ('confirmed', 'shipping', 'delivered')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    // Get order statistics for a specific user
    public function getUserOrderStats($userId) {
        $query = "SELECT
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as completed_orders,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(total_amount) as total_spent
                  FROM " . $this->table . "
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return [
            'total_orders' => (int)($result['total_orders'] ?? 0),
            'completed_orders' => (int)($result['completed_orders'] ?? 0),
            'cancelled_orders' => (int)($result['cancelled_orders'] ?? 0),
            'pending_orders' => (int)($result['pending_orders'] ?? 0),
            'total_spent' => (float)($result['total_spent'] ?? 0)
        ];
    }

    // Analytics methods
    public function getRevenueByDateRange($startDate, $endDate) {
        $query = "SELECT SUM(total_amount) as revenue
                  FROM " . $this->table . "
                  WHERE created_at BETWEEN :start_date AND :end_date
                  AND status != 'cancelled'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($result['revenue'] ?? 0);
    }

    public function getRevenueByDate($date) {
        $query = "SELECT SUM(total_amount) as revenue
                  FROM " . $this->table . "
                  WHERE DATE(created_at) = :date
                  AND status != 'cancelled'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($result['revenue'] ?? 0);
    }

    public function countOrdersByDateRange($startDate, $endDate) {
        $query = "SELECT COUNT(*) as count
                  FROM " . $this->table . "
                  WHERE created_at BETWEEN :start_date AND :end_date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function countOrdersByDate($date) {
        $query = "SELECT COUNT(*) as count
                  FROM " . $this->table . "
                  WHERE DATE(created_at) = :date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function countOrdersByStatus($status, $startDate = null, $endDate = null) {
        $where = "status = :status";
        $params = [':status' => $status];

        if ($startDate && $endDate) {
            $where .= " AND created_at BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $startDate;
            $params[':end_date'] = $endDate;
        }

        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE $where";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function getAverageOrderValue($startDate, $endDate) {
        $query = "SELECT AVG(total_amount) as average
                  FROM " . $this->table . "
                  WHERE created_at BETWEEN :start_date AND :end_date
                  AND status != 'cancelled'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($result['average'] ?? 0);
    }
}
