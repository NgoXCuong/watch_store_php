<?php
namespace App\Controllers\Client;

use Core\Controller;

class ProductsController extends Controller {
    private $productModel;
    private $categoryModel;
    private $brandModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->categoryModel = $this->model('CategoryModel');
        $this->brandModel = $this->model('BrandModel');
    }

    // Hiển thị danh sách sản phẩm
    public function index() {
        // Lấy tham số từ URL
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $brandId = isset($_GET['brand']) ? (int)$_GET['brand'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
        $minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
        $maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;

        $perPage = 9; // Số sản phẩm mỗi trang

        // Lấy danh sách sản phẩm
        $products = $this->productModel->getAll($page, $perPage, $search, $categoryId, $brandId, $sort, $minPrice, $maxPrice);
        $total = $this->productModel->countAll($search, $categoryId, $brandId, $minPrice, $maxPrice);
        $totalPages = ceil($total / $perPage);

        // Lấy danh sách categories và brands để filter
        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();

        // Lấy thông tin category và brand hiện tại (nếu có)
        $currentCategory = null;
        $currentBrand = null;
        if ($categoryId) {
            foreach ($categories as $cat) {
                if ($cat['id'] == $categoryId) {
                    $currentCategory = $cat;
                    break;
                }
            }
        }
        if ($brandId) {
            foreach ($brands as $brand) {
                if ($brand['id'] == $brandId) {
                    $currentBrand = $brand;
                    break;
                }
            }
        }

        $this->view('client/products/index', [
            'title' => 'Sản phẩm - Watch Store',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'search' => $search,
            'selectedCategory' => $categoryId,
            'selectedBrand' => $brandId,
            'selectedSort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'currentCategory' => $currentCategory,
            'currentBrand' => $currentBrand,
            'layout' => 'client'
        ]);
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id) {
        $product = $this->productModel->findById($id);

        if (!$product) {
            header('Location: ' . BASE_URL . '/products');
            exit;
        }

        // Lấy sản phẩm liên quan (cùng category đầu tiên tìm thấy)
        $relatedProducts = [];
        if (!empty($product['category_ids'])) {
             $firstCategoryId = $product['category_ids'][0];
             $relatedProducts = $this->productModel->getByCategory($firstCategoryId, 4);
        }

        // Loại bỏ sản phẩm hiện tại khỏi danh sách related
        $relatedProducts = array_filter($relatedProducts, function($p) use ($id) {
            return $p['id'] != $id;
        });

        // Lấy đánh giá sản phẩm
        $reviewModel = $this->model('ReviewModel');
        $reviews = $reviewModel->getByProductId($id);

        // Kiểm tra xem user có thể đánh giá sản phẩm này không
        $canReview = false;
        $hasReviewed = false;
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            // Tạm thời tắt kiểm tra mua hàng để test đánh giá
            // $canReview = $this->hasUserPurchasedProduct($userId, $id) && !$this->hasUserReviewedProduct($userId, $id);
            $canReview = !$this->hasUserReviewedProduct($userId, $id);

            // Check if user already reviewed
            foreach ($reviews as $review) {
                if ($review['user_id'] == $userId) {
                    $hasReviewed = true;
                    break;
                }
            }
        }

        $this->view('client/products/show', [
            'title' => $product['name'] . ' - Watch Store',
            'product' => $product,
            'relatedProducts' => array_slice($relatedProducts, 0, 4),
            'reviews' => $reviews,
            'canReview' => $canReview,
            'hasReviewed' => $hasReviewed,
            'layout' => 'client'
        ]);
    }

    // Helper: Kiểm tra user đã mua sản phẩm chưa
    private function hasUserPurchasedProduct($userId, $productId) {
        $orderModel = $this->model('OrderModel');
        return $orderModel->hasUserPurchasedProduct($userId, $productId);
    }

    // Helper: Kiểm tra user đã đánh giá sản phẩm chưa
    private function hasUserReviewedProduct($userId, $productId) {
        $reviewModel = $this->model('ReviewModel');
        $reviews = $reviewModel->getByUserId($userId);
        foreach ($reviews as $review) {
            if ($review['product_id'] == $productId) {
                return true;
            }
        }
        return false;
    }

    // API tìm kiếm autocomplete (cho AJAX)
    public function search() {
        header('Content-Type: application/json');

        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($query) < 2) {
            echo json_encode([]);
            exit;
        }

        // Tìm sản phẩm theo tên
        $products = $this->productModel->getAll(1, 5, $query, null, null, 'default');

        $results = array_map(function($product) {
            return [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url'],
                'url' => BASE_URL . '/products/show/' . $product['id']
            ];
        }, $products);

        echo json_encode($results);
        exit;
    }
}
