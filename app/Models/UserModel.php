<?php
namespace App\Models;

use Core\Database;
use PDO;

class UserModel {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Tìm user theo email
    public function findByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm user theo username
    public function findByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo user mới
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (username, email, password, full_name, phone, role, status)
                  VALUES (:username, :email, :password, :full_name, :phone, :role, :status)";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Prepare variables for binding
        $username = $data['username'];
        $email = $data['email'];
        $fullName = $data['full_name'] ?? null;
        $phone = $data['phone'] ?? null;
        $role = $data['role'] ?? 'customer';
        $status = $data['status'] ?? 'active';

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật user
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " SET ";
        $setParts = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            if ($key === 'password') {
                $setParts[] = "password = :password";
                $params[':password'] = password_hash($value, PASSWORD_DEFAULT);
            } else {
                $setParts[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        
        $query .= implode(', ', $setParts) . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    // Xóa user
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy user theo ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả users với phân trang
    public function getAll($page = 1, $limit = 10, $search = '') {
        $offset = ($page - 1) * $limit;
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (username LIKE :search OR email LIKE :search OR full_name LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        $query = "SELECT * FROM " . $this->table . " WHERE $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Đếm tổng số users
    public function countAll($search = '') {
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (username LIKE :search OR email LIKE :search OR full_name LIKE :search)";
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

    // Cập nhật trạng thái user
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Cập nhật role của user
    public function updateRole($id, $role) {
        $query = "UPDATE " . $this->table . " SET role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Analytics methods
    public function countNewUsers($startDate, $endDate) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . "
                  WHERE created_at BETWEEN :start_date AND :end_date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function countActiveUsers($days) {
        $date = date('Y-m-d', strtotime("-{$days} days"));

        $query = "SELECT COUNT(DISTINCT u.id) as count
                  FROM " . $this->table . " u
                  JOIN orders o ON u.id = o.user_id
                  WHERE o.created_at >= :date AND u.status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }
}
