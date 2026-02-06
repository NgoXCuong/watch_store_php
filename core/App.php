<?php
namespace Core;

class App {
    public function __construct() {
        $url = $this->getUrl();
        $uri = implode('/', $url);

        // Load Routes
        $router = require_once __DIR__ . '/../routes/web.php';
        
        // Dispatch
        $router->dispatch($uri);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        } else {
            $requestUri = $_SERVER['REQUEST_URI'] ?? '';
            $url = parse_url($requestUri, PHP_URL_PATH);
            $basePath = dirname($_SERVER['SCRIPT_NAME']);
            if ($basePath !== '/' && strpos($url, $basePath) === 0) {
                $url = substr($url, strlen($basePath));
            }
            $url = trim($url, '/');
            return $url ? explode('/', $url) : [];
        }
    }
}

