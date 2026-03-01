<?php
namespace App\Controllers\Client;

use Core\Controller;

class UserAddressController extends Controller {
    private $userAddressModel;

    public function __construct() {
        $this->userAddressModel = $this->model('UserAddressModel');
    }

    // Hiển thị danh sách địa chỉ
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $addresses = $this->userAddressModel->getByUserId($userId);

        $this->view('client/profile/addresses/index', [
            'title' => 'Sổ địa chỉ - Watch Store',
            'addresses' => $addresses,
            'layout' => 'client'
        ]);
    }

    // Form thêm địa chỉ mới
    public function create() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $this->view('client/profile/addresses/create', [
            'title' => 'Thêm địa chỉ mới - Watch Store',
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'client'
        ]);

        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý thêm địa chỉ mới
    public function store() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile/addresses');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        
        $data = [
            'user_id' => $userId,
            'recipient_name' => trim($_POST['recipient_name'] ?? ''),
            'recipient_phone' => trim($_POST['recipient_phone'] ?? ''),
            'address_line' => trim($_POST['address_line'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'district' => trim($_POST['district'] ?? ''),
            'is_default' => isset($_POST['is_default']) ? 1 : 0
        ];

        $errors = $this->validateAddressData($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/profile/addresses/create');
            exit;
        }

        if ($this->userAddressModel->create($data)) {
            $_SESSION['success'] = 'Thêm địa chỉ thành công!';
            header('Location: ' . BASE_URL . '/profile/addresses');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/profile/addresses/create');
            exit;
        }
    }

    // Form sửa địa chỉ
    public function edit($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $address = $this->userAddressModel->findByIdAndUserId($id, $userId);

        if (!$address) {
            $_SESSION['error'] = 'Không tìm thấy địa chỉ này';
            header('Location: ' . BASE_URL . '/profile/addresses');
            exit;
        }

        $this->view('client/profile/addresses/edit', [
            'title' => 'Chỉnh sửa địa chỉ - Watch Store',
            'address' => $address,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'client'
        ]);

        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý sửa địa chỉ
    public function update($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile/addresses');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $address = $this->userAddressModel->findByIdAndUserId($id, $userId);

        if (!$address) {
            $_SESSION['error'] = 'Không tìm thấy địa chỉ này';
            header('Location: ' . BASE_URL . '/profile/addresses');
            exit;
        }

        $data = [
            'recipient_name' => trim($_POST['recipient_name'] ?? ''),
            'recipient_phone' => trim($_POST['recipient_phone'] ?? ''),
            'address_line' => trim($_POST['address_line'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'district' => trim($_POST['district'] ?? ''),
            'is_default' => isset($_POST['is_default']) ? 1 : 0
        ];

        $errors = $this->validateAddressData($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/profile/addresses/edit/' . $id);
            exit;
        }

        if ($this->userAddressModel->update($id, $userId, $data)) {
            $_SESSION['success'] = 'Cập nhật địa chỉ thành công!';
            header('Location: ' . BASE_URL . '/profile/addresses');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/profile/addresses/edit/' . $id);
            exit;
        }
    }

    // Xóa địa chỉ
    public function delete($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];
            
            if ($this->userAddressModel->delete($id, $userId)) {
                $_SESSION['success'] = 'Xóa địa chỉ thành công!';
            } else {
                $_SESSION['error'] = 'Không thể xóa địa chỉ này hoặc địa chỉ không tồn tại';
            }
        }
        
        header('Location: ' . BASE_URL . '/profile/addresses');
        exit;
    }

    // Set địa chỉ mặc định
    public function setDefault($id) {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];
            
            if ($this->userAddressModel->setDefault($id, $userId)) {
                $_SESSION['success'] = 'Đã đặt làm địa chỉ mặc định!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            }
        }
        
        header('Location: ' . BASE_URL . '/profile/addresses');
        exit;
    }

    // Validate dữ liệu
    private function validateAddressData($data) {
        $errors = [];

        if (empty($data['recipient_name'])) {
            $errors['recipient_name'] = 'Họ tên người nhận không được để trống';
        }

        if (empty($data['recipient_phone'])) {
            $errors['recipient_phone'] = 'Số điện thoại không được để trống';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $data['recipient_phone'])) {
            $errors['recipient_phone'] = 'Số điện thoại không hợp lệ';
        }

        if (empty($data['city'])) {
            $errors['city'] = 'Tỉnh/Thành phố không được để trống';
        }

        if (empty($data['district'])) {
            $errors['district'] = 'Quận/Huyện không được để trống';
        }
        
        if (empty($data['address_line'])) {
            $errors['address_line'] = 'Địa chỉ chi tiết không được để trống';
        }

        return $errors;
    }
}
