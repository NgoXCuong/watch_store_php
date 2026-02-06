<?php
namespace Core;

class Router {
    protected $routes = [];

    // Thêm các route GET
    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    // Thêm các route POST
    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    // Hàm chung để thêm route
    protected function addRoute($method, $uri, $action) {
        // Chuẩn hóa URI: xóa dấu / ở đầu và cuối
        $uri = trim($uri, '/');
        // Chuyển {id} thành regex ([a-zA-Z0-9-_]+)
        $uri = preg_replace('/\{([a-zA-Z0-9-_]+)\}/', '([a-zA-Z0-9-_]+)', $uri);
        
        $this->routes[$method][$uri] = $action;
    }

    // Xử lý request
    public function dispatch($uri) {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim($uri, '/');

        // Check if route exists directly
        if (isset($this->routes[$method][$uri])) {
            $this->callAction($this->routes[$method][$uri]);
            return;
        }

        // Check regex patterns
        foreach ($this->routes[$method] as $route => $action) {
            if (preg_match("#^$route$#", $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->callAction($action, $matches);
                return;
            }
        }

        // 404 Not Found
        $this->show404();
    }

    protected function callAction($action, $params = []) {
        if (is_array($action)) {
            $controller = new $action[0];
            $method = $action[1];
        } else {
            // Case for closure (if implemented later) or string "Controller@method"
             $parts = explode('@', $action);
             $controllerName = "App\\Controllers\\" . $parts[0];
             $controller = new $controllerName();
             $method = $parts[1];
        }

        call_user_func_array([$controller, $method], $params);
    }

    protected function show404() {
        require_once __DIR__ . '/../app/Controllers/Client/ErrorController.php';
        $controller = new \App\Controllers\Client\ErrorController();
        call_user_func_array([$controller, 'notFound'], []);
        exit;
    }
}
