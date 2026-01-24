<?php
namespace App\Controllers\Client;

use Core\Controller;

class HomeController extends Controller {
    private $productModel;
    private $categoryModel;
    private $brandModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->categoryModel = $this->model('CategoryModel');
        $this->brandModel = $this->model('BrandModel');
    }

    public function index() {
        // Lấy dữ liệu cho trang chủ
        $featuredProducts = $this->productModel->getFeatured(8); // 8 sản phẩm nổi bật
        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();

        // Lấy sản phẩm mới nhất (không phải featured)
        $latestProducts = $this->productModel->getAll(1, 8, '', null, null, 'latest');

        // Gọi view trang chủ với dữ liệu
        $this->view('client/home', [
            'title' => 'Watch Store - Trang chủ',
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'categories' => $categories,
            'brands' => $brands,
            'layout' => 'client'
        ]);
    }
}
