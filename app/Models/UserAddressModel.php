<?php
namespace App\Models;

use Core\Database;
use PDO;

class UserAddressModel {
    private $conn;
    private $table = 'user_addresses';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả địa chỉ của một user
    public function getByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY is_default DESC, id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 địa chỉ theo ID và UserID (để bảo mật)
    public function findByIdAndUserId($id, $userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy địa chỉ mặc định của user
    public function getDefaultAddress($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id AND is_default = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm địa chỉ mới
    public function create($data) {
        // Nếu là địa chỉ đầu tiên hoặc được set thành mặc định, cần xóa mặc định các địa chỉ cũ
        if (!empty($data['is_default']) && $data['is_default'] == 1) {
            $this->clearDefault($data['user_id']);
        } else {
            // Kiểm tra xem user đã có địa chỉ nào chưa, nếu chưa có thì ép thành mặc định
            $currentAddresses = $this->getByUserId($data['user_id']);
            if (empty($currentAddresses)) {
                $data['is_default'] = 1;
            } else {
                $data['is_default'] = 0;
            }
        }

        $query = "INSERT INTO " . $this->table . " 
                  (user_id, recipient_name, recipient_phone, address_line, city, district, is_default)
                  VALUES (:user_id, :recipient_name, :recipient_phone, :address_line, :city, :district, :is_default)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':recipient_name', $data['recipient_name']);
        $stmt->bindParam(':recipient_phone', $data['recipient_phone']);
        $stmt->bindParam(':address_line', $data['address_line']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':district', $data['district']);
        $stmt->bindParam(':is_default', $data['is_default'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Cập nhật địa chỉ
    public function update($id, $userId, $data) {
        // Nếu set thành mặc định
        if (!empty($data['is_default']) && $data['is_default'] == 1) {
            $this->clearDefault($userId);
        } else {
            $data['is_default'] = 0;
            
            // Nếu update address đang mặc định thành không mặc định, chặn (phải có ít nhất 1 cái mặc định nếu có địa chỉ)
            $oldAddress = $this->findByIdAndUserId($id, $userId);
            if ($oldAddress && $oldAddress['is_default'] == 1) {
               $data['is_default'] = 1; // Forcing it back or you can handle logic to set another one as default. We'll simply disallow unchecking the only default
            }
        }

        $query = "UPDATE " . $this->table . " 
                  SET recipient_name = :recipient_name, 
                      recipient_phone = :recipient_phone, 
                      address_line = :address_line, 
                      city = :city, 
                      district = :district, 
                      is_default = :is_default 
                  WHERE id = :id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':recipient_name', $data['recipient_name']);
        $stmt->bindParam(':recipient_phone', $data['recipient_phone']);
        $stmt->bindParam(':address_line', $data['address_line']);
        $stmt->bindParam(':city', $data['city']);
        $stmt->bindParam(':district', $data['district']);
        $stmt->bindParam(':is_default', $data['is_default'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Xóa địa chỉ
    public function delete($id, $userId) {
        // Kiểm tra xem có đang là mặc định không
        $address = $this->findByIdAndUserId($id, $userId);
        if (!$address) return false;

        $wasDefault = $address['is_default'] == 1;

        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result && $wasDefault) {
             // Chọn 1 địa chỉ khác làm mặc định (nếu còn)
             $remaining = $this->getByUserId($userId);
             if (!empty($remaining)) {
                 $firstRemainingId = $remaining[0]['id'];
                 $this->setDefault($firstRemainingId, $userId);
             }
        }

        return $result;
    }

    // Xóa trạng thái mặc định của tất cả địa chỉ của 1 user
    private function clearDefault($userId) {
        $query = "UPDATE " . $this->table . " SET is_default = 0 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Set 1 địa chỉ cụ thể làm mặc định
    public function setDefault($id, $userId) {
        $this->clearDefault($userId);

        $query = "UPDATE " . $this->table . " SET is_default = 1 WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
