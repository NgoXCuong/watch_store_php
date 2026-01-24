<?php
// 1. Khởi động Session (Để sau này dùng cho Giỏ hàng / Đăng nhập)
session_start();

// 2. Định nghĩa BASE_URL cho các link
define('BASE_URL', 'http://localhost:8000');

// 3. Load bộ thư viện Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

use Core\App;

// 4. Khởi chạy ứng dụng
$app = new App();
