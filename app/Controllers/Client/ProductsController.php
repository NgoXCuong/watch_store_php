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
        // Lấy danh sách categories và brands trước để AI có data trích xuất
        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();
        
        // Lấy tham số từ URL
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['category']) && $_GET['category'] !== '' ? (int)$_GET['category'] : null;
        $brandId = isset($_GET['brand']) && $_GET['brand'] !== '' ? (int)$_GET['brand'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
        $minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
        $maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
        
        $isAiSearch = isset($_GET['ai_search']) && $_GET['ai_search'] == 1;
        $aiMessage = null;

        // Xử lý AI Search
        if ($isAiSearch && !empty($search)) {
            $aiService = new \App\Services\AIService();
            $aiData = $aiService->parseSearchQuery($search, $categories, $brands);
            
            if (isset($aiData['error']) && $aiData['error'] === 'API_QUOTA_EXCEEDED') {
                $aiMessage = "Rất tiếc, hệ thống AI đang bị quá tải hoặc hết hạn mức (Quota Exceeded). Vui lòng trở lại tìm kiếm thông thường hoặc liên hệ Admin cung cấp API Key mới.";
            } elseif (!empty($aiData)) {
                $search = $aiData['search'] ?? '';
                $categoryId = isset($aiData['category_id']) ? (int)$aiData['category_id'] : null;
                // If the prompt returned something for category_id, ensure valid
                if (isset($aiData['category_id']) && $aiData['category_id'] !== null) {
                    $categoryId = (int)$aiData['category_id'];
                }
                if (isset($aiData['brand_id']) && $aiData['brand_id'] !== null) {
                    $brandId = (int)$aiData['brand_id'];
                }
                if (isset($aiData['min_price']) && $aiData['min_price'] !== null) {
                    $minPrice = (float)$aiData['min_price'];
                }
                if (isset($aiData['max_price']) && $aiData['max_price'] !== null) {
                    $maxPrice = (float)$aiData['max_price'];
                }
                $aiMessage = "Đã áp dụng bộ lọc AI thông minh cho yêu cầu của bạn!";
            } else {
                $aiMessage = "AI không thể phân tích yêu cầu này, chuyển về tìm kiếm thông thường.";
            }
        }

        $perPage = 9; // Số sản phẩm mỗi trang

        // Lấy danh sách sản phẩm
        $products = $this->productModel->getAll($page, $perPage, $search, $categoryId, $brandId, $sort, $minPrice, $maxPrice);
        $total = $this->productModel->countAll($search, $categoryId, $brandId, $minPrice, $maxPrice);
        $totalPages = ceil($total / $perPage);

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
            'aiMessage' => $aiMessage,
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
