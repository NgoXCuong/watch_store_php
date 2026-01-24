<?php
namespace App\Controllers\Client;

use Core\Controller;

class BrandsController extends Controller {
    private $brandModel;

    public function __construct() {
        $this->brandModel = $this->model('BrandModel');
    }

    public function index() {
        $brands = $this->brandModel->getAll();
        $this->view('client/brands/index', [
            'title' => 'Thương hiệu - Watch Store',
            'brands' => $brands,
            'layout' => 'client'
        ]);
    }
}
