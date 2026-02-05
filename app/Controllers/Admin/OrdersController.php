<?php
namespace App\Controllers\Admin;

use Core\Controller;

class OrdersController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('OrderModel');
    }

    // Hiển thị danh sách đơn hàng
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $limit = 8;

        $orders = $this->orderModel->getAll($page, $limit, $search, $status);
        $total = $this->orderModel->countAll($search, $status);
        $totalPages = ceil($total / $limit);

        $this->view('admin/orders/index', [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'selectedStatus' => $status,
            'layout' => 'admin'
        ]);
    }

    // Hiển thị chi tiết đơn hàng
    public function show($id) {
        $order = $this->orderModel->findById($id);
        if (!$order) {
            header('Location: ' . BASE_URL . '/admin/orders');
            exit;
        }

        $orderDetails = $this->orderModel->getOrderDetails($id);

        $this->view('admin/orders/show', [
            'title' => 'Chi tiết đơn hàng #' . $id,
            'order' => $order,
            'orderDetails' => $orderDetails,
            'layout' => 'admin'
        ]);
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/orders');
            exit;
        }

        $status = $_POST['status'] ?? '';

        $validStatuses = ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled', 'returned'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ!';
            header('Location: ' . BASE_URL . '/admin/orders/show/' . $id);
            exit;
        }

        if ($this->orderModel->updateStatus($id, $status)) {
            $_SESSION['success'] = 'Cập nhật trạng thái đơn hàng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật trạng thái!';
        }

        header('Location: ' . BASE_URL . '/admin/orders/show/' . $id);
        exit;
    }

    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/orders');
            exit;
        }

        $paymentStatus = $_POST['payment_status'] ?? '';

        $validStatuses = ['unpaid', 'paid', 'refunded'];
        if (!in_array($paymentStatus, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái thanh toán không hợp lệ!';
            header('Location: ' . BASE_URL . '/admin/orders/show/' . $id);
            exit;
        }

        if ($this->orderModel->updatePaymentStatus($id, $paymentStatus)) {
            $_SESSION['success'] = 'Cập nhật trạng thái thanh toán thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật trạng thái thanh toán!';
        }

        header('Location: ' . BASE_URL . '/admin/orders/show/' . $id);
        exit;
    }

    // Lấy chi tiết đơn hàng cho modal
    public function getOrderDetails($id) {
        header('Content-Type: application/json');

        $order = $this->orderModel->findById($id);
        if (!$order) {
            echo json_encode(['error' => 'Đơn hàng không tồn tại']);
            exit;
        }

        $orderDetails = $this->orderModel->getOrderDetails($id);

        echo json_encode([
            'order' => $order,
            'orderDetails' => $orderDetails
        ]);
        exit;
    }
}
