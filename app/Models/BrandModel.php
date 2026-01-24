<?php
namespace App\Models;

use Core\Database;
use PDO;

class BrandModel {
    private $conn;
    private $table = 'brands';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả thương hiệu với phân trang
    public function getAll($page = 1, $limit = 10, $search = '') {
        $offset = ($page - 1) * $limit;
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT * FROM " . $this->table . " WHERE $where ORDER BY name ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số thương hiệu
    public function countAll($search = '') {
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
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

    // Lấy thương hiệu theo ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo thương hiệu mới
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (name, slug, description, logo_url)
                  VALUES (:name, :slug, :description, :logo_url)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':logo_url', $data['logo_url']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật thương hiệu
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET ";
        $setParts = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $setParts[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $query .= implode(', ', $setParts) . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    // Xóa thương hiệu
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Kiểm tra xem thương hiệu có sản phẩm không
    public function hasProducts($id) {
        $query = "SELECT COUNT(*) as count FROM products WHERE brand_id = :brand_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':brand_id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
