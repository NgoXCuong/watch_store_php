<?php
namespace App\Models;

use Core\Database;
use PDO;

class CategoryModel {
    private $conn;
    private $table = 'categories';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả danh mục
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY parent_id ASC, name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục theo ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo danh mục mới
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (name, slug, description, parent_id)
                  VALUES (:name, :slug, :description, :parent_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':parent_id', $data['parent_id']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật danh mục
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

    // Xóa danh mục
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy danh mục cha
    public function getParentCategories() {
        $query = "SELECT * FROM " . $this->table . " WHERE parent_id IS NULL ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh mục con theo parent_id
    public function getChildCategories($parentId) {
        $query = "SELECT * FROM " . $this->table . " WHERE parent_id = :parent_id ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':parent_id', $parentId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kiểm tra xem danh mục có sản phẩm không
    public function hasProducts($id) {
        $query = "SELECT COUNT(*) as count FROM products WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
