<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    // Thông tin kết nối (Sửa lại cho đúng với máy bạn)
    private $host = 'localhost';
    private $db_name = 'watch_store_db';
    private $username = 'root';
    private $password = ''; // Mặc định XAMPP không có pass
    private $port = '3307'; // <--- QUAN TRỌNG: Phải khớp với cổng MySQL của bạn

    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Chuỗi kết nối DSN có thêm tham số port
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";

            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Cấu hình để ném lỗi ngoại lệ (Exception) khi gặp lỗi SQL
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Cấu hình trả về dữ liệu dạng mảng kết hợp (Associative Array)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {
            echo "Lỗi kết nối Database: " . $exception->getMessage();
            exit();
        }

        return $this->conn;
    }
}
