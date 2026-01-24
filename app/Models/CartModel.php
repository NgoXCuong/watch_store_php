<?php
namespace App\Models;

use Core\Database;
use PDO;

class CartModel {
    private $conn;
    private $table = 'carts';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy giỏ hàng của user
    public function getCartByUserId($userId) {
        $query = "SELECT c.*, p.name as product_name, p.price, p.old_price, p.image_url, p.stock, p.status
                  FROM " . $this->table . " c
                  JOIN products p ON c.product_id = p.id
                  WHERE c.user_id = :user_id AND p.status = 'active'
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart($userId, $productId, $quantity = 1) {
        // Kiểm tra sản phẩm có tồn tại và active không
        $product = $this->getProductById($productId);
        if (!$product || $product['status'] !== 'active') {
            return false;
        }

        // Kiểm tra số lượng tồn kho
        if ($quantity > $product['stock']) {
            return false;
        }

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $existingItem = $this->getCartItem($userId, $productId);

        if ($existingItem) {
            // Cập nhật số lượng
            $newQuantity = $existingItem['quantity'] + $quantity;
            if ($newQuantity > $product['stock']) {
                $newQuantity = $product['stock'];
            }
            return $this->updateQuantity($userId, $productId, $newQuantity);
        } else {
            // Thêm mới
            $query = "INSERT INTO " . $this->table . " (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);
            return $stmt->execute();
        }
    }

    // Cập nhật số lượng sản phẩm trong giỏ
    public function updateQuantity($userId, $productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $productId);
        }

        // Kiểm tra tồn kho
        $product = $this->getProductById($productId);
        if (!$product || $quantity > $product['stock']) {
            return false;
        }

        $query = "UPDATE " . $this->table . " SET quantity = :quantity, updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        return $stmt->execute();
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart($userId, $productId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        return $stmt->execute();
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCart($userId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    // Lấy một item trong giỏ hàng
    public function getCartItem($userId, $productId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đếm số lượng item trong giỏ
    public function getCartCount($userId) {
        $query = "SELECT SUM(quantity) as total FROM " . $this->table . " c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id AND p.status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    // Tính tổng tiền giỏ hàng
    public function getCartTotal($userId) {
        $query = "SELECT SUM(c.quantity * p.price) as total FROM " . $this->table . " c JOIN products p ON c.product_id = p.id WHERE c.user_id = :user_id AND p.status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($result['total'] ?? 0);
    }

    // Lấy thông tin sản phẩm
    private function getProductById($productId) {
        $query = "SELECT * FROM products WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $productId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra sản phẩm có thể thêm vào giỏ không
    public function canAddToCart($productId, $quantity = 1) {
        $product = $this->getProductById($productId);
        if (!$product || $product['status'] !== 'active') {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc không khả dụng'];
        }

        if ($product['stock'] < $quantity) {
            return ['success' => false, 'message' => 'Không đủ hàng trong kho'];
        }

        return ['success' => true, 'product' => $product];
    }
}
