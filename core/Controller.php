<?php
namespace Core;

class Controller {
    // Hàm gọi Model (Dữ liệu)
    public function model($model) {
        $modelClass = "App\\Models\\" . $model;
        return new $modelClass();
    }

    // Hàm gọi View (Giao diện)
    public function view($view, $data = []) {
        // Truyền dữ liệu ra view
        if (!empty($data)) {
            extract($data);
        }

        // Kiểm tra xem có sử dụng layout không
        $layout = $data['layout'] ?? null;

        if ($layout) {
            // Sử dụng layout
            $layoutPath = __DIR__ . '/../resources/views/layouts/' . $layout . '.php';
            if (file_exists($layoutPath)) {
                // Lấy nội dung của view con
                ob_start();
                $viewPath = __DIR__ . '/../resources/views/' . $view . '.php';
                if (file_exists($viewPath)) {
                    require_once $viewPath;
                }
                $content = ob_get_clean();

                // Truyền content vào layout
                $data['content'] = $content;
                extract($data);

                require_once $layoutPath;
            } else {
                echo "Layout không tồn tại: $layout";
            }
        } else {
            // Không sử dụng layout
            $viewPath = __DIR__ . '/../resources/views/' . $view . '.php';
            if (file_exists($viewPath)) {
                require_once $viewPath;
            } else {
                echo "View không tồn tại: $view";
            }
        }
    }
}
