<?php
namespace App\Controllers\Client;

use Core\Controller;

class WishlistController extends Controller {
    private $wishlistModel;

    public function __construct() {
        $this->wishlistModel = $this->model('WishlistModel');
    }

    // Hiển thị danh sách yêu thích
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $wishlistItems = $this->wishlistModel->getUserWishlist($userId);

        $this->view('client/wishlist/index', [
            'title' => 'Sản phẩm yêu thích - Watch Store',
            'wishlistItems' => $wishlistItems,
            'layout' => 'client'
        ]);
    }

    // Thêm / Bỏ yêu thích (Hoạt động cho AJAX)
    public function toggle() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng tính năng này.',
                'redirect' => BASE_URL . '/auth/login'
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $productId = (int)($_POST['product_id'] ?? 0);

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ.']);
            exit;
        }

        $isLiked = $this->wishlistModel->check($userId, $productId);

        if ($isLiked) {
            // Remove
            if ($this->wishlistModel->remove($userId, $productId)) {
                $count = $this->wishlistModel->countByUser($userId);
                echo json_encode([
                    'success' => true, 
                    'action' => 'removed',
                    'message' => 'Đã bỏ yêu thích.',
                    'count' => $count
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi bỏ yêu thích.']);
            }
        } else {
            // Add
            if ($this->wishlistModel->add($userId, $productId)) {
                $count = $this->wishlistModel->countByUser($userId);
                echo json_encode([
                    'success' => true, 
                    'action' => 'added',
                    'message' => 'Đã thêm vào yêu thích.',
                    'count' => $count
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi thêm yêu thích.']);
            }
        }
        exit;
    }

    // Xóa sản phẩm khỏi yêu thích (Từ trang wishlist)
    public function remove($productId) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $productId = (int)$productId;

        if ($this->wishlistModel->remove($userId, $productId)) {
            $_SESSION['success'] = 'Đã xóa sản phẩm khỏi danh sách yêu thích.';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra.';
        }

        header('Location: ' . BASE_URL . '/wishlist');
        exit;
    }

    // API endpoint đếm số lượng wishlist (để cập nhật header)
    public function count() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $count = $this->wishlistModel->countByUser($userId);

        echo json_encode(['count' => $count]);
        exit;
    }
}
