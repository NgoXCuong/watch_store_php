<?php
namespace App\Models;

use Core\Database;
use PDO;

class VoucherModel {
    protected $conn;
    private $table = 'vouchers';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả voucher
    public function getAll($page = 1, $limit = 10, $search = '') {
        $offset = ($page - 1) * $limit;
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (code LIKE :search OR description LIKE :search)";
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

    // Đếm tổng số voucher
    public function countAll($search = '') {
        $where = "1=1";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (code LIKE :search OR description LIKE :search)";
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

    // Lấy voucher theo ID
    public function findById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy voucher theo code
    public function findByCode($code) {
        $query = "SELECT * FROM " . $this->table . " WHERE code = :code AND is_active = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo voucher mới
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (code, description, discount_type, discount_value, max_discount_amount,
                   min_order_value, usage_limit, start_date, end_date, is_active)
                  VALUES (:code, :description, :discount_type, :discount_value, :max_discount_amount,
                          :min_order_value, :usage_limit, :start_date, :end_date, :is_active)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':code', $data['code']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':max_discount_amount', $data['max_discount_amount']);
        $stmt->bindParam(':min_order_value', $data['min_order_value']);
        $stmt->bindParam(':usage_limit', $data['usage_limit']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_active', $data['is_active']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật voucher
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

    // Xóa voucher
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Kiểm tra voucher có hợp lệ không
    public function isValid($code, $orderTotal) {
        $voucher = $this->findByCode($code);
        if (!$voucher) return false;

        $now = date('Y-m-d H:i:s');

        // Kiểm tra thời hạn
        if ($voucher['start_date'] && $now < $voucher['start_date']) return false;
        if ($voucher['end_date'] && $now > $voucher['end_date']) return false;

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($orderTotal < $voucher['min_order_value']) return false;

        // Kiểm tra số lần sử dụng
        if ($voucher['usage_count'] >= $voucher['usage_limit']) return false;

        return $voucher;
    }

    // Tính giá trị giảm giá
    public function calculateDiscount($voucher, $orderTotal) {
        if ($voucher['discount_type'] === 'fixed') {
            return min($voucher['discount_value'], $orderTotal);
        } else { // percent
            $discount = $orderTotal * ($voucher['discount_value'] / 100);
            if ($voucher['max_discount_amount']) {
                $discount = min($discount, $voucher['max_discount_amount']);
            }
            return $discount;
        }
    }

    // Update voucher usage count
    public function updateUsage($voucherCode) {
        $query = "UPDATE " . $this->table . " SET usage_count = usage_count + 1 WHERE code = :code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':code', $voucherCode);

        if (!$stmt->execute()) {
            throw new \Exception('Không thể cập nhật voucher');
        }
    }
}
