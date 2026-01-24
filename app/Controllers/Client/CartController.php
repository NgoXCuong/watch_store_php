<?php
namespace App\Controllers\Client;

use Core\Controller;

class CartController extends Controller {
    private $cartModel;
    private $productModel;

    public function __construct() {
        $this->cartModel = $this->model('CartModel');
        $this->productModel = $this->model('ProductModel');
    }

    // Hiển thị giỏ hàng
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $cartItems = $this->cartModel->getCartByUserId($userId);
        $cartTotal = $this->cartModel->getCartTotal($userId);
        $cartCount = $this->cartModel->getCartCount($userId);

        $this->view('client/cart/index', [
            'title' => 'Giỏ hàng - Watch Store',
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount,
            'layout' => 'client'
        ]);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng';
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);
        $userId = $_SESSION['user']['id'];

        // Validate
        if ($productId <= 0 || $quantity <= 0) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        // Kiểm tra sản phẩm
        $check = $this->cartModel->canAddToCart($productId, $quantity);
        if (!$check['success']) {
            $_SESSION['error'] = $check['message'];
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        // Thêm vào giỏ
        if ($this->cartModel->addToCart($userId, $productId, $quantity)) {
            $_SESSION['success'] = 'Đã thêm sản phẩm vào giỏ hàng';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        // Redirect về trang trước đó hoặc giỏ hàng
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . '/cart';
        header('Location: ' . $referer);
        exit;
    }

    // Cập nhật số lượng
    public function update() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $userId = $_SESSION['user']['id'];

        if ($productId <= 0) {
            $_SESSION['error'] = 'Sản phẩm không hợp lệ';
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        if ($quantity <= 0) {
            // Xóa sản phẩm khỏi giỏ
            if ($this->cartModel->removeFromCart($userId, $productId)) {
                $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa sản phẩm';
            }
        } else {
            // Cập nhật số lượng
            if ($this->cartModel->updateQuantity($userId, $productId, $quantity)) {
                $_SESSION['success'] = 'Đã cập nhật số lượng';
            } else {
                $_SESSION['error'] = 'Không thể cập nhật số lượng. Vui lòng kiểm tra tồn kho.';
            }
        }

        header('Location: ' . BASE_URL . '/cart');
        exit;
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove($productId) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $productId = (int)$productId;

        if ($this->cartModel->removeFromCart($userId, $productId)) {
            $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa sản phẩm';
        }

        header('Location: ' . BASE_URL . '/cart');
        exit;
    }

    // Xóa toàn bộ giỏ hàng
    public function clear() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        if ($this->cartModel->clearCart($userId)) {
            $_SESSION['success'] = 'Đã xóa toàn bộ giỏ hàng';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa giỏ hàng';
        }

        header('Location: ' . BASE_URL . '/cart');
        exit;
    }

    // API endpoint để lấy số lượng giỏ hàng (cho AJAX)
    public function count() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $count = $this->cartModel->getCartCount($userId);

        echo json_encode(['count' => $count]);
        exit;
    }
}
