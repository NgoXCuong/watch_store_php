<?php
namespace App\Models;

use Core\Database;
use PDO;

class ReviewModel {
    protected $conn;
    private $table = 'reviews';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả đánh giá với phân trang
    public function getAll($page = 1, $limit = 10, $approved = null) {
        $offset = ($page - 1) * $limit;
        $where = "1=1";
        $params = [];

        if ($approved !== null) {
            $where .= " AND r.is_approved = :approved";
            $params[':approved'] = $approved;
        }

        $query = "SELECT r.*, p.name as product_name, u.full_name as user_name
                  FROM " . $this->table . " r
                  JOIN products p ON r.product_id = p.id
                  JOIN users u ON r.user_id = u.id
                  WHERE $where
                  ORDER BY r.created_at DESC
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

    // Đếm tổng số đánh giá
    public function countAll($approved = null) {
        $where = "1=1";
        $params = [];

        if ($approved !== null) {
            $where .= " AND is_approved = :approved";
            $params[':approved'] = $approved;
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

    // Lấy đánh giá theo ID
    public function findById($id) {
        $query = "SELECT r.*, p.name as product_name, p.image_url, u.full_name as user_name, u.avatar_url
                  FROM " . $this->table . " r
                  JOIN products p ON r.product_id = p.id
                  JOIN users u ON r.user_id = u.id
                  WHERE r.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy đánh giá theo sản phẩm
    public function getByProductId($productId, $approvedOnly = true) {
        $where = "product_id = :product_id";
        if ($approvedOnly) {
            $where .= " AND is_approved = 1";
        }

        $query = "SELECT r.*, u.full_name as user_name, u.avatar_url
                  FROM " . $this->table . " r
                  JOIN users u ON r.user_id = u.id
                  WHERE $where
                  ORDER BY r.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Duyệt đánh giá
    public function approve($id) {
        $query = "UPDATE " . $this->table . " SET is_approved = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Từ chối đánh giá
    public function reject($id) {
        $query = "UPDATE " . $this->table . " SET is_approved = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Xóa đánh giá
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy thống kê đánh giá
    public function getStatistics() {
        $query = "SELECT
                    COUNT(*) as total_reviews,
                    AVG(rating) as average_rating,
                    SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as approved_reviews,
                    SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) as pending_reviews
                  FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo đánh giá mới
    public function create($data) {
        try {
            // Kiểm tra xem bảng reviews có tồn tại không
            $result = $this->conn->query("SHOW TABLES LIKE '" . $this->table . "'");
            if ($result->rowCount() == 0) {
                // Tạo bảng reviews nếu chưa có
                $this->createReviewsTable();
            }

            // Kiểm tra user_id và product_id có tồn tại không
            $userCheck = $this->conn->prepare("SELECT id FROM users WHERE id = ?");
            $userCheck->execute([$data['user_id']]);
            if ($userCheck->rowCount() == 0) {
                error_log('Review creation failed: User ID ' . $data['user_id'] . ' does not exist');
                return false;
            }

            $productCheck = $this->conn->prepare("SELECT id FROM products WHERE id = ?");
            $productCheck->execute([$data['product_id']]);
            if ($productCheck->rowCount() == 0) {
                error_log('Review creation failed: Product ID ' . $data['product_id'] . ' does not exist');
                return false;
            }

            $query = "INSERT INTO " . $this->table . "
                      (user_id, product_id, rating, comment, is_approved)
                      VALUES (:user_id, :product_id, :rating, :comment, :is_approved)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $data['user_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $data['product_id'], \PDO::PARAM_INT);
            $stmt->bindParam(':rating', $data['rating'], \PDO::PARAM_INT);
            $stmt->bindParam(':comment', $data['comment'], \PDO::PARAM_STR);
            $stmt->bindParam(':is_approved', $data['is_approved'], \PDO::PARAM_INT);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }

            // Log the error
            $errorInfo = $stmt->errorInfo();
            error_log('Review creation failed: ' . json_encode($errorInfo));
            error_log('Review data: ' . json_encode($data));
            return false;
        } catch (\Exception $e) {
            error_log('Review creation exception: ' . $e->getMessage());
            error_log('Review data: ' . json_encode($data));
            return false;
        }
    }

    // Tạo bảng reviews nếu chưa tồn tại
    private function createReviewsTable() {
        $query = "
            CREATE TABLE IF NOT EXISTS `reviews` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `product_id` INT(11) NOT NULL,
                `rating` TINYINT(4) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
                `comment` TEXT DEFAULT NULL,
                `is_approved` TINYINT(1) DEFAULT 0,
                `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_review` (`user_id`, `product_id`),
                KEY `idx_review_product` (`product_id`),
                KEY `idx_review_user` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";

        $this->conn->exec($query);

        // Thêm foreign key constraints (sẽ bỏ qua nếu đã tồn tại)
        try {
            $this->conn->exec("ALTER TABLE `reviews` ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE");
        } catch (\Exception $e) {
            // Ignore if constraint already exists
        }

        try {
            $this->conn->exec("ALTER TABLE `reviews` ADD CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE");
        } catch (\Exception $e) {
            // Ignore if constraint already exists
        }
    }

    // Lấy đánh giá theo user
    public function getByUserId($userId) {
        $query = "SELECT r.*, p.name as product_name, p.image_url as product_image
                  FROM " . $this->table . " r
                  JOIN products p ON r.product_id = p.id
                  WHERE r.user_id = :user_id
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
