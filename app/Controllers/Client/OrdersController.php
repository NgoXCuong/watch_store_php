<?php
namespace App\Controllers\Client;

use Core\Controller;

class OrdersController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('OrderModel');
    }

    // Hiển thị danh sách đơn hàng của user
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $limit = 10;

        $orders = $this->orderModel->getByUserId($userId, $page, $limit);
        $total = $this->orderModel->countAll('', $status ? $status : null, $userId);
        $totalPages = ceil($total / $limit);

        $this->view('client/orders/index', [
            'title' => 'Lịch sử đơn hàng - Watch Store',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'status' => $status,
            'total' => $total,
            'layout' => 'client'
        ]);
    }

    // Hiển thị chi tiết đơn hàng
    public function show($orderId) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = (int)$orderId;

        // Lấy thông tin đơn hàng
        $order = $this->orderModel->findById($orderId);

        // Kiểm tra quyền truy cập (chỉ xem đơn hàng của chính mình)
        if (!$order || $order['user_id'] != $userId) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng hoặc bạn không có quyền xem';
            header('Location: ' . BASE_URL . '/orders');
            exit;
        }

        // Lấy chi tiết đơn hàng
        $orderDetails = $this->orderModel->getOrderDetails($orderId);

        $this->view('client/orders/show', [
            'title' => 'Chi tiết đơn hàng #' . $orderId . ' - Watch Store',
            'order' => $order,
            'orderDetails' => $orderDetails,
            'layout' => 'client'
        ]);
    }

    // Hủy đơn hàng
    public function cancel($orderId) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/orders');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = (int)$orderId;

        // Lấy thông tin đơn hàng
        $order = $this->orderModel->findById($orderId);

        // Kiểm tra quyền truy cập
        if (!$order || $order['user_id'] != $userId) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            header('Location: ' . BASE_URL . '/orders');
            exit;
        }

        // Chỉ cho phép hủy đơn hàng ở trạng thái pending hoặc confirmed
        if (!in_array($order['status'], ['pending', 'confirmed'])) {
            $_SESSION['error'] = 'Không thể hủy đơn hàng này';
            header('Location: ' . BASE_URL . '/orders/show/' . $orderId);
            exit;
        }

        // Hủy đơn hàng
        if ($this->orderModel->updateStatus($orderId, 'cancelled')) {
            $_SESSION['success'] = 'Đã hủy đơn hàng thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi hủy đơn hàng';
        }

        header('Location: ' . BASE_URL . '/orders/show/' . $orderId);
        exit;
    }

    // Theo dõi trạng thái đơn hàng (AJAX endpoint)
    public function track($orderId) {
        header('Content-Type: application/json');

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = (int)$orderId;

        // Lấy thông tin đơn hàng
        $order = $this->orderModel->findById($orderId);

        // Kiểm tra quyền truy cập
        if (!$order || $order['user_id'] != $userId) {
            echo json_encode(['error' => 'Order not found']);
            exit;
        }

        // Trả về thông tin tracking
        $tracking = [
            'order_id' => $order['id'],
            'status' => $order['status'],
            'created_at' => $order['created_at'],
            'updated_at' => $order['updated_at']
        ];

        // Thêm các mốc thời gian dựa trên status
        $statusHistory = [];
        $statusHistory[] = [
            'status' => 'pending',
            'label' => 'Đặt hàng',
            'date' => $order['created_at'],
            'completed' => in_array($order['status'], ['pending', 'confirmed', 'shipping', 'delivered'])
        ];

        if (in_array($order['status'], ['confirmed', 'shipping', 'delivered'])) {
            $statusHistory[] = [
                'status' => 'confirmed',
                'label' => 'Xác nhận',
                'date' => $order['updated_at'],
                'completed' => in_array($order['status'], ['confirmed', 'shipping', 'delivered'])
            ];
        }

        if (in_array($order['status'], ['shipping', 'delivered'])) {
            $statusHistory[] = [
                'status' => 'shipping',
                'label' => 'Đang giao',
                'date' => $order['updated_at'],
                'completed' => in_array($order['status'], ['shipping', 'delivered'])
            ];
        }

        if ($order['status'] === 'delivered') {
            $statusHistory[] = [
                'status' => 'delivered',
                'label' => 'Đã giao',
                'date' => $order['updated_at'],
                'completed' => true
            ];
        }

        if ($order['status'] === 'cancelled') {
            $statusHistory[] = [
                'status' => 'cancelled',
                'label' => 'Đã hủy',
                'date' => $order['updated_at'],
                'completed' => true
            ];
        }

        $tracking['status_history'] = $statusHistory;

        echo json_encode($tracking);
        exit;
    }
}
