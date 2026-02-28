<?php
namespace App\Models;

use Core\Database;
use PDO;

class WishlistModel {
    private $conn;
    private $table = 'wishlists';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Add a product to wishlist
    public function add($userId, $productId) {
        // First check if it exists to avoid error
        if ($this->check($userId, $productId)) {
            return false;
        }

        $query = "INSERT INTO " . $this->table . " (user_id, product_id) VALUES (:user_id, :product_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        
        return $stmt->execute();
    }

    // Remove a product from wishlist
    public function remove($userId, $productId) {
        $query = "DELETE FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        
        return $stmt->execute();
    }

    // Check if product is in user's wishlist
    public function check($userId, $productId) {
        $query = "SELECT id FROM " . $this->table . " WHERE user_id = :user_id AND product_id = :product_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Get all wishlist items for a user
    public function getUserWishlist($userId, $limit = null, $offset = 0) {
        $query = "SELECT w.*, p.name as product_name, p.price, p.old_price, p.image_url as image, p.slug as product_slug, p.status as product_status, c.name as category_name, b.name as brand_name 
                  FROM " . $this->table . " w
                  JOIN products p ON w.product_id = p.id
                  LEFT JOIN product_categories pc ON p.id = pc.product_id
                  LEFT JOIN categories c ON pc.category_id = c.id
                  LEFT JOIN brands b ON p.brand_id = b.id
                  WHERE w.user_id = :user_id 
                  GROUP BY w.id
                  ORDER BY w.created_at DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count total wishlist items for a user
    public function countByUser($userId) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'] ?? 0;
    }
}
