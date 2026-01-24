<?php
namespace App\Controllers\Admin;

use Core\Controller;

class VouchersController extends Controller {
    private $voucherModel;

    public function __construct() {
        $this->voucherModel = $this->model('VoucherModel');
    }

    // Hiển thị danh sách voucher
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 10;

        $vouchers = $this->voucherModel->getAll($page, $limit, $search);
        $total = $this->voucherModel->countAll($search);
        $totalPages = ceil($total / $limit);

        $this->view('admin/vouchers/index', [
            'title' => 'Quản lý voucher',
            'vouchers' => $vouchers,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'total' => $total,
            'layout' => 'admin'
        ]);
    }

    // Hiển thị form tạo voucher
    public function create() {
        $this->view('admin/vouchers/create', [
            'title' => 'Thêm voucher mới',
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý tạo voucher
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/vouchers/create');
            exit;
        }

        $data = [
            'code' => strtoupper(trim($_POST['code'] ?? '')),
            'description' => trim($_POST['description'] ?? ''),
            'discount_type' => $_POST['discount_type'] ?? 'fixed',
            'discount_value' => (float)($_POST['discount_value'] ?? 0),
            'max_discount_amount' => !empty($_POST['max_discount_amount']) ? (float)$_POST['max_discount_amount'] : null,
            'min_order_value' => (float)($_POST['min_order_value'] ?? 0),
            'usage_limit' => (int)($_POST['usage_limit'] ?? 0),
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $errors = [];

        // Validation
        if (empty($data['code'])) {
            $errors['code'] = 'Mã voucher không được để trống';
        } elseif ($this->voucherModel->findByCode($data['code'])) {
            $errors['code'] = 'Mã voucher đã tồn tại';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Mô tả voucher không được để trống';
        }

        if ($data['discount_value'] <= 0) {
            $errors['discount_value'] = 'Giá trị giảm giá phải lớn hơn 0';
        }

        if ($data['discount_type'] === 'percent' && $data['discount_value'] > 100) {
            $errors['discount_value'] = 'Phần trăm giảm giá không được vượt quá 100%';
        }

        if ($data['min_order_value'] < 0) {
            $errors['min_order_value'] = 'Giá trị đơn hàng tối thiểu không được âm';
        }

        if ($data['usage_limit'] <= 0) {
            $errors['usage_limit'] = 'Số lần sử dụng phải lớn hơn 0';
        }

        // Validate dates
        if ($data['start_date'] && $data['end_date'] && $data['start_date'] >= $data['end_date']) {
            $errors['end_date'] = 'Ngày kết thúc phải sau ngày bắt đầu';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/vouchers/create');
            exit;
        }

        // Tạo voucher
        $voucherId = $this->voucherModel->create($data);

        if ($voucherId) {
            $_SESSION['success'] = 'Thêm voucher thành công!';
            header('Location: ' . BASE_URL . '/admin/vouchers');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/vouchers/create');
            exit;
        }
    }

    // Hiển thị form chỉnh sửa voucher
    public function edit($id) {
        $voucher = $this->voucherModel->findById($id);
        if (!$voucher) {
            header('Location: ' . BASE_URL . '/admin/vouchers');
            exit;
        }

        $this->view('admin/vouchers/edit', [
            'title' => 'Chỉnh sửa voucher',
            'voucher' => $voucher,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý cập nhật voucher
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/vouchers/edit/' . $id);
            exit;
        }

        $voucher = $this->voucherModel->findById($id);
        if (!$voucher) {
            header('Location: ' . BASE_URL . '/admin/vouchers');
            exit;
        }

        $data = [
            'code' => strtoupper(trim($_POST['code'] ?? '')),
            'description' => trim($_POST['description'] ?? ''),
            'discount_type' => $_POST['discount_type'] ?? 'fixed',
            'discount_value' => (float)($_POST['discount_value'] ?? 0),
            'max_discount_amount' => !empty($_POST['max_discount_amount']) ? (float)$_POST['max_discount_amount'] : null,
            'min_order_value' => (float)($_POST['min_order_value'] ?? 0),
            'usage_limit' => (int)($_POST['usage_limit'] ?? 0),
            'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];

        $errors = [];

        // Validation
        if (empty($data['code'])) {
            $errors['code'] = 'Mã voucher không được để trống';
        } elseif ($data['code'] !== $voucher['code'] && $this->voucherModel->findByCode($data['code'])) {
            $errors['code'] = 'Mã voucher đã tồn tại';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Mô tả voucher không được để trống';
        }

        if ($data['discount_value'] <= 0) {
            $errors['discount_value'] = 'Giá trị giảm giá phải lớn hơn 0';
        }

        if ($data['discount_type'] === 'percent' && $data['discount_value'] > 100) {
            $errors['discount_value'] = 'Phần trăm giảm giá không được vượt quá 100%';
        }

        if ($data['min_order_value'] < 0) {
            $errors['min_order_value'] = 'Giá trị đơn hàng tối thiểu không được âm';
        }

        if ($data['usage_limit'] <= 0) {
            $errors['usage_limit'] = 'Số lần sử dụng phải lớn hơn 0';
        }

        // Validate dates
        if ($data['start_date'] && $data['end_date'] && $data['start_date'] >= $data['end_date']) {
            $errors['end_date'] = 'Ngày kết thúc phải sau ngày bắt đầu';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/vouchers/edit/' . $id);
            exit;
        }

        // Cập nhật voucher
        if ($this->voucherModel->update($id, $data)) {
            $_SESSION['success'] = 'Cập nhật voucher thành công!';
            header('Location: ' . BASE_URL . '/admin/vouchers');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/vouchers/edit/' . $id);
            exit;
        }
    }

    // Xóa voucher
    public function delete($id) {
        $voucher = $this->voucherModel->findById($id);
        if (!$voucher) {
            $_SESSION['error'] = 'Voucher không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/vouchers');
            exit;
        }

        if ($this->voucherModel->delete($id)) {
            $_SESSION['success'] = 'Xóa voucher thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa voucher!';
        }

        header('Location: ' . BASE_URL . '/admin/vouchers');
        exit;
    }

    // Cập nhật trạng thái voucher
    public function toggleStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/vouchers');
            exit;
        }

        $voucher = $this->voucherModel->findById($id);
        if (!$voucher) {
            $_SESSION['error'] = 'Voucher không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/vouchers');
            exit;
        }

        $newStatus = $voucher['is_active'] ? 0 : 1;
        $data = ['is_active' => $newStatus];

        if ($this->voucherModel->update($id, $data)) {
            $statusText = $newStatus ? 'kích hoạt' : 'vô hiệu hóa';
            $_SESSION['success'] = 'Đã ' . $statusText . ' voucher thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật trạng thái!';
        }

        header('Location: ' . BASE_URL . '/admin/vouchers');
        exit;
    }
}
