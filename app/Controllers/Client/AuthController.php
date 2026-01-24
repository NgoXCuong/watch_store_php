<?php
namespace App\Controllers\Client;

use Core\Controller;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    // Hiển thị form đăng nhập
    public function login() {
        // Nếu đã đăng nhập, chuyển về trang chủ hoặc admin
        if (isset($_SESSION['user'])) {
            $redirectUrl = ($_SESSION['user']['role'] === 'admin') ? '/admin' : '/';
            header('Location: ' . BASE_URL . $redirectUrl);
            exit;
        }

        $data = [
            'title' => 'Đăng nhập - Watch Store',
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? []
        ];

        // Xóa session errors sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);

        $this->view('client/auth/login', $data);
    }

    // Xử lý đăng nhập
    public function loginProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $errors = [];

        // Validation
        if (empty($email)) {
            $errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        if (empty($password)) {
            $errors['password'] = 'Mật khẩu không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = ['email' => $email];
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        // Tìm user theo email
        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['errors'] = ['general' => 'Email hoặc mật khẩu không đúng'];
            $_SESSION['old_input'] = ['email' => $email];
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        // Kiểm tra trạng thái tài khoản
        if ($user['status'] !== 'active') {
            $_SESSION['errors'] = ['general' => 'Tài khoản của bạn đã bị khóa'];
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        // Đăng nhập thành công
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'full_name' => $user['full_name'],
            'role' => $user['role']
        ];

        // Chuyển hướng dựa trên vai trò
        $redirectUrl = ($user['role'] === 'admin') ? '/admin' : '/';
        header('Location: ' . BASE_URL . $redirectUrl);
        exit;
    }

    // Hiển thị form đăng ký
    public function register() {
        // Nếu đã đăng nhập, chuyển về trang chủ hoặc admin
        if (isset($_SESSION['user'])) {
            $redirectUrl = ($_SESSION['user']['role'] === 'admin') ? '/admin' : '/';
            header('Location: ' . BASE_URL . $redirectUrl);
            exit;
        }

        $data = [
            'title' => 'Đăng ký - Watch Store',
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? []
        ];

        // Xóa session errors sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);

        $this->view('client/auth/register', $data);
    }

    // Xử lý đăng ký
    public function registerProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/auth/register');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $errors = [];

        // Validation
        if (empty($username)) {
            $errors['username'] = 'Tên đăng nhập không được để trống';
        } elseif (strlen($username) < 3) {
            $errors['username'] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
        } elseif ($this->userModel->findByUsername($username)) {
            $errors['username'] = 'Tên đăng nhập đã tồn tại';
        }

        if (empty($email)) {
            $errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } elseif ($this->userModel->findByEmail($email)) {
            $errors['email'] = 'Email đã được sử dụng';
        }

        if (empty($password)) {
            $errors['password'] = 'Mật khẩu không được để trống';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
        }

        if (empty($fullName)) {
            $errors['full_name'] = 'Họ tên không được để trống';
        }

        if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
            $errors['phone'] = 'Số điện thoại không hợp lệ';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = [
                'username' => $username,
                'email' => $email,
                'full_name' => $fullName,
                'phone' => $phone
            ];
            header('Location: ' . BASE_URL . '/auth/register');
            exit;
        }

        // Tạo user mới
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName,
            'phone' => $phone,
            'role' => 'customer',
            'status' => 'active'
        ];

        $userId = $this->userModel->create($userData);

        if ($userId) {
            // Đăng ký thành công, tự động đăng nhập
            $user = $this->userModel->findById($userId);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'full_name' => $user['full_name'],
                'role' => $user['role']
            ];

            // Chuyển hướng về trang chủ
            header('Location: ' . BASE_URL . '/');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            header('Location: ' . BASE_URL . '/auth/register');
            exit;
        }
    }

    // Đăng xuất
    public function logout() {
        // Xóa session user
        unset($_SESSION['user']);

        // Chuyển hướng về trang chủ
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}
