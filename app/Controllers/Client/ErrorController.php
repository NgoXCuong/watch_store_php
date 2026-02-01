<?php
namespace App\Controllers\Client;

use Core\Controller;

class ErrorController extends Controller {
    public function notFound() {
        $this->view('errors/404', [
            'page_title' => '404 - Không tìm thấy trang'
        ]);
    }
}
