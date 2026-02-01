<?php
namespace Core;

class App {
    protected $controller = 'HomeController'; // Controller mặc định của Client
    protected $action = 'index';              // Hàm mặc định
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // --- LOGIC ĐỊNH TUYẾN (ROUTING) ---
        
        $folder = 'Client'; // Mặc định tìm trong folder Client

        // 1. Kiểm tra xem URL có bắt đầu bằng 'admin' không?
        if (isset($url[0]) && strtolower($url[0]) == 'admin') {
            // Kiểm tra quyền admin
            if (!$this->checkAdminAccess()) {
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }

            $folder = 'Admin';
            $this->controller = 'DashboardController'; // Mặc định của Admin
            array_shift($url); // Xóa chữ 'admin' khỏi mảng URL
        }

        // 2. Kiểm tra Controller (VD: /san-pham -> ProductController)
        if (isset($url[0])) {
            $checkName = ucfirst($url[0]) . 'Controller';
            $path = __DIR__ . '/../app/Controllers/' . $folder . '/' . $checkName . '.php';
            
            if (file_exists($path)) {
                $this->controller = $checkName;
                array_shift($url);
            }
        }

        // 3. Khởi tạo Class Controller
        $controllerClass = "App\\Controllers\\$folder\\" . $this->controller;
        
        // Kiểm tra xem Class có tồn tại không (Tránh lỗi Fatal Error)
        if (!class_exists($controllerClass)) {
            $this->show404();
            return;
        }

        $this->controller = new $controllerClass();

        // 4. Kiểm tra Action (Hàm bên trong Controller)
        if (isset($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->action = $url[0];
                array_shift($url);
            }
        }

        // 5. Lấy tham số còn lại
        $this->params = $url ? array_values($url) : [];

        // 6. Chạy hàm
        call_user_func_array([$this->controller, $this->action], $this->params);
    }
    
    private function show404() {
        require_once __DIR__ . '/../app/Controllers/Client/ErrorController.php';
        $controller = new \App\Controllers\Client\ErrorController();
        call_user_func_array([$controller, 'notFound'], []);
        exit;
    }

    // Kiểm tra quyền truy cập admin
    private function checkAdminAccess() {
        // Kiểm tra xem user đã đăng nhập chưa
        if (!isset($_SESSION['user'])) {
            return false;
        }

        // Kiểm tra xem user có quyền admin không
        if ($_SESSION['user']['role'] !== 'admin') {
            return false;
        }

        return true;
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            // For Apache with mod_rewrite
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        } else {
            // Fallback for servers without mod_rewrite (e.g., PHP built-in server)
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            // Remove query string
            $url = parse_url($requestUri, PHP_URL_PATH);
            // Remove leading slash and script name if present
            $basePath = dirname($_SERVER['SCRIPT_NAME']);
            if ($basePath !== '/' && strpos($url, $basePath) === 0) {
                $url = substr($url, strlen($basePath));
            }
            $url = trim($url, '/');
            return $url ? explode('/', $url) : [];
        }
    }
}
