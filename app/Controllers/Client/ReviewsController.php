<?php
namespace App\Controllers\Client;

use Core\Controller;

class ReviewsController extends Controller {
    private $reviewModel;
    private $productModel;
    private $orderModel;

    public function __construct() {
        try {
            $this->reviewModel = $this->model('ReviewModel');
            $this->productModel = $this->model('ProductModel');
            $this->orderModel = $this->model('OrderModel');
        } catch (\Exception $e) {
            error_log('ReviewsController constructor error: ' . $e->getMessage());
            die('Controller initialization error');
        }
    }

    // Hiển thị form đánh giá sản phẩm
    public function create($productId) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $productId = (int)$productId;

        // Kiểm tra sản phẩm tồn tại
        $product = $this->productModel->findById($productId);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ' . BASE_URL . '/products');
            exit;
        }

        // Kiểm tra user đã mua sản phẩm này chưa
        if (!$this->hasUserPurchasedProduct($userId, $productId)) {
            $_SESSION['error'] = 'Bạn chỉ có thể đánh giá sản phẩm đã mua';
            header('Location: ' . BASE_URL . '/products/show/' . $productId);
            exit;
        }

        // Kiểm tra đã đánh giá sản phẩm này trong đơn hàng chưa
        if ($this->hasUserReviewedProductInOrder($userId, $productId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này trong đơn hàng rồi';
            header('Location: ' . BASE_URL . '/products/show/' . $productId);
            exit;
        }

        $this->view('client/reviews/create', [
            'title' => 'Đánh giá sản phẩm - Watch Store',
            'product' => $product,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'client'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý tạo đánh giá
    public function store() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để đánh giá';
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Phương thức không hợp lệ';
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $productId = (int)($_POST['product_id'] ?? 0);

        // Validate dữ liệu
        $data = [
            'rating' => (int)($_POST['rating'] ?? 0),
            'comment' => trim($_POST['comment'] ?? '')
        ];

        $errors = [];

        // Validation
        if ($data['rating'] < 1 || $data['rating'] > 5) {
            $errors['rating'] = 'Vui lòng chọn số sao hợp lệ (1-5)';
        }

        if (empty($data['comment'])) {
            $errors['comment'] = 'Nội dung đánh giá không được để trống';
        } elseif (strlen($data['comment']) < 10) {
            $errors['comment'] = 'Nội dung đánh giá phải có ít nhất 10 ký tự';
        } elseif ($this->containsBadWords($data['comment'])) {
            $errors['comment'] = 'Nội dung đánh giá chứa từ ngữ không phù hợp';
        }

        // Kiểm tra sản phẩm
        $product = $this->productModel->findById($productId);
        if (!$product) {
            $errors['general'] = 'Sản phẩm không tồn tại';
        }

        // Kiểm tra đã đánh giá chưa
        if ($this->hasUserReviewedProduct($userId, $productId)) {
            $errors['general'] = 'Bạn đã đánh giá sản phẩm này rồi';
        }

        if (!empty($errors)) {
            // Lưu lỗi và dữ liệu cũ vào session
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/reviews/create/' . $productId);
            exit;
        }

        // Tạo đánh giá
        $reviewData = [
            'user_id' => $userId,
            'product_id' => $productId,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'is_approved' => 1 // Tự động duyệt để test
        ];

        try {
            $result = $this->reviewModel->create($reviewData);

            if ($result) {
                $_SESSION['success'] = 'Cảm ơn bạn đã đánh giá sản phẩm!';
                header('Location: ' . BASE_URL . '/products/show/' . $productId);
                exit;
            } else {
                // Check if user has already reviewed this product
                if ($this->hasUserReviewedProduct($_SESSION['user']['id'], $productId)) {
                    $_SESSION['error'] = 'Bạn đã đánh giá sản phẩm này rồi!';
                    header('Location: ' . BASE_URL . '/products/show/' . $productId);
                    exit;
                }

                // Generic error message
                $_SESSION['error'] = 'Có lỗi xảy ra khi lưu đánh giá. Vui lòng thử lại.';
                // Lưu dữ liệu cũ
                $_SESSION['old_input'] = $data;
                header('Location: ' . BASE_URL . '/reviews/create/' . $productId);
                exit;
            }
        } catch (\Exception $e) {
            error_log('Review creation error: ' . $e->getMessage());
            $_SESSION['error'] = 'Lỗi hệ thống: ' . $e->getMessage();
            // Lưu dữ liệu cũ
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/reviews/create/' . $productId);
            exit;
        }
    }

    // Hiển thị đánh giá của user
    public function myReviews() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        // Lấy đánh giá của user
        $reviews = $this->reviewModel->getByUserId($userId);

        $this->view('client/reviews/my_reviews', [
            'title' => 'Đánh giá của tôi - Watch Store',
            'reviews' => $reviews,
            'layout' => 'client'
        ]);
    }

    // Helper: Kiểm tra user đã mua sản phẩm chưa
    private function hasUserPurchasedProduct($userId, $productId) {
        return $this->orderModel->hasUserPurchasedProduct($userId, $productId);
    }

    // Helper: Kiểm tra user đã đánh giá sản phẩm trong đơn hàng chưa
    private function hasUserReviewedProductInOrder($userId, $productId) {
        // Since we limit 1 review per product per user, we can just check if they reviewed this product
        return $this->hasUserReviewedProduct($userId, $productId);
    }

    // Helper: Kiểm tra user đã đánh giá sản phẩm chưa
    private function hasUserReviewedProduct($userId, $productId) {
        $reviews = $this->reviewModel->getByUserId($userId);
        foreach ($reviews as $review) {
            if ($review['product_id'] == $productId) {
                return true;
            }
        }
        return false;
    }

    // Helper: Kiểm tra nội dung có chứa từ cấm không
    private function containsBadWords($text) {
        $badWords = [
            'đồ ngu', 'ngu', 'đần', 'đồ đần', 'khốn nạn', 'đồ khốn', 'cặc', 'lồn', 'địt', 'đụ',
            'fuck', 'shit', 'damn', 'bitch', 'asshole', 'bastard', 'motherfucker',
            'dumb', 'stupid', 'idiot', 'moron', 'retard'
        ];

        $text = mb_strtolower($text, 'UTF-8');

        foreach ($badWords as $badWord) {
            if (strpos($text, $badWord) !== false) {
                return true;
            }
        }

        return false;
    }

    // Helper: Đếm số user trong database
    private function checkUserCount() {
        try {
            $userModel = $this->model('UserModel');
            $stmt = $userModel->conn->query("SELECT COUNT(*) as count FROM users");
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }

    // Helper: Đếm số sản phẩm trong database
    private function checkProductCount() {
        try {
            $stmt = $this->productModel->conn->query("SELECT COUNT(*) as count FROM products");
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            return (int)($result['count'] ?? 0);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
