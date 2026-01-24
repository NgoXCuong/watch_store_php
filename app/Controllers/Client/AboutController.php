<?php
namespace App\Controllers\Client;

use Core\Controller;

class AboutController extends Controller {
    public function index() {
        $this->view('client/about/index', [
            'title' => 'Về chúng tôi - Watch Store',
            'layout' => 'client'
        ]);
    }
}
