<?php
namespace App\Controllers\Client;

use Core\Controller;

class ProfileController extends Controller {
    private $userModel;
    private $orderModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
        $this->orderModel = $this->model('OrderModel');
    }

    // Hiển thị trang hồ sơ cá nhân
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy thông tin người dùng';
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        // Lấy thống kê đơn hàng
        $orderStats = $this->orderModel->getUserOrderStats($userId);

        // Lấy đơn hàng gần đây (5 đơn)
        $recentOrders = $this->orderModel->getByUserId($userId, 1, 5);

        // Lấy đánh giá gần đây (5 đánh giá)
        $reviewModel = $this->model('ReviewModel');
        $recentReviews = $reviewModel->getByUserId($userId, 1, 5);

        $this->view('client/profile/index', [
            'title' => 'Hồ sơ cá nhân - Watch Store',
            'user' => $user,
            'orderStats' => $orderStats,
            'recentOrders' => $recentOrders,
            'recentReviews' => $recentReviews,
            'layout' => 'client'
        ]);
    }

    // Hiển thị form chỉnh sửa hồ sơ
    public function edit() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy thông tin người dùng';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $this->view('client/profile/edit', [
            'title' => 'Chỉnh sửa hồ sơ - Watch Store',
            'user' => $user,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'client'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý cập nhật hồ sơ
    public function update() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile/edit');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy thông tin người dùng';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'avatar_url' => trim($_POST['avatar_url'] ?? '') // Keep old url if no new file
        ];

        // Handle File Upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $cloudinaryService = new \App\Services\CloudinaryService();
            $uploaded = $cloudinaryService->uploadImage($_FILES['avatar']['tmp_name'], [
               'folder' => 'watch_store/avatars'
            ]);
            
            if ($uploaded && isset($uploaded['url'])) {
                 $data['avatar_url'] = $uploaded['url'];
            }
        }

        $errors = [];

        // Validation
        if (empty($data['username'])) {
            $errors['username'] = 'Tên đăng nhập không được để trống';
        } elseif ($data['username'] !== $user['username'] && $this->userModel->findByUsername($data['username'])) {
            $errors['username'] = 'Tên đăng nhập đã tồn tại';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } elseif ($data['email'] !== $user['email'] && $this->userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Email đã được sử dụng';
        }

        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Họ tên không được để trống';
        }

        if (!empty($data['phone']) && !preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $errors['phone'] = 'Số điện thoại không hợp lệ';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/profile/edit');
            exit;
        }

        // Cập nhật thông tin
        if ($this->userModel->update($userId, $data)) {
            // Cập nhật session
            $_SESSION['user']['username'] = $data['username'];
            $_SESSION['user']['email'] = $data['email'];
            $_SESSION['user']['full_name'] = $data['full_name'];
            if (!empty($data['avatar_url'])) {
                $_SESSION['user']['avatar_url'] = $data['avatar_url'];
            }

            $_SESSION['success'] = 'Cập nhật hồ sơ thành công!';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/profile/edit');
            exit;
        }
    }

    // Hiển thị form đổi mật khẩu
    public function changePassword() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $this->view('client/profile/change_password', [
            'title' => 'Đổi mật khẩu - Watch Store',
            'errors' => $_SESSION['errors'] ?? [],
            'layout' => 'client'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors']);
    }

    // Xử lý đổi mật khẩu
    public function updatePassword() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile/change-password');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy thông tin người dùng';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        // Validation
        if (empty($currentPassword)) {
            $errors['current_password'] = 'Mật khẩu hiện tại không được để trống';
        } elseif (!password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = 'Mật khẩu hiện tại không đúng';
        }

        if (empty($newPassword)) {
            $errors['new_password'] = 'Mật khẩu mới không được để trống';
        } elseif (strlen($newPassword) < 6) {
            $errors['new_password'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }

        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . BASE_URL . '/profile/change-password');
            exit;
        }

        // Cập nhật mật khẩu
        $data = ['password' => $newPassword];
        if ($this->userModel->update($userId, $data)) {
            $_SESSION['success'] = 'Đổi mật khẩu thành công!';
            header('Location: ' . BASE_URL . '/profile');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            header('Location: ' . BASE_URL . '/profile/change-password');
            exit;
        }
    }

    // Hiển thị danh sách yêu thích (tính năng tương lai)
    public function wishlist() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $this->view('client/profile/wishlist', [
            'title' => 'Sản phẩm yêu thích - Watch Store',
            'layout' => 'client'
        ]);
    }


}
