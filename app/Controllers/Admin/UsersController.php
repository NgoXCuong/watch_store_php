<?php
namespace App\Controllers\Admin;

use Core\Controller;

class UsersController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }

    // Hiển thị danh sách users
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 10;

        $users = $this->userModel->getAll($page, $limit, $search);
        $total = $this->userModel->countAll($search);
        $totalPages = ceil($total / $limit);

        $this->view('admin/users/index', [
            'title' => 'Quản lý người dùng',
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'total' => $total,
            'layout' => 'admin'
        ]);
    }

    // Hiển thị form tạo user
    public function create() {
        $this->view('admin/users/create', [
            'title' => 'Thêm người dùng mới',
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý tạo user
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users/create');
            exit;
        }

        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? 'customer',
            'status' => $_POST['status'] ?? 'active'
        ];

        $errors = [];

        // Validation
        if (empty($data['username'])) {
            $errors['username'] = 'Tên đăng nhập không được để trống';
        } elseif ($this->userModel->findByUsername($data['username'])) {
            $errors['username'] = 'Tên đăng nhập đã tồn tại';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } elseif ($this->userModel->findByEmail($data['email'])) {
            $errors['email'] = 'Email đã tồn tại';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Mật khẩu không được để trống';
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Họ tên không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/users/create');
            exit;
        }

        // Tạo user
        $userId = $this->userModel->create($data);

        if ($userId) {
            $_SESSION['success'] = 'Thêm người dùng thành công!';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/users/create');
            exit;
        }
    }

    // Hiển thị form chỉnh sửa user
    public function edit($id) {
        $user = $this->userModel->findById($id);
        if (!$user) {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $this->view('admin/users/edit', [
            'title' => 'Chỉnh sửa người dùng',
            'user' => $user,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý cập nhật user
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users/edit/' . $id);
            exit;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'full_name' => trim($_POST['full_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'role' => $_POST['role'] ?? 'customer',
            'status' => $_POST['status'] ?? 'active'
        ];

        // Chỉ cập nhật password nếu có nhập
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
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
            $errors['email'] = 'Email đã tồn tại';
        }

        if (!empty($_POST['password']) && strlen($_POST['password']) < 6) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Họ tên không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/users/edit/' . $id);
            exit;
        }

        // Cập nhật user
        if ($this->userModel->update($id, $data)) {
            $_SESSION['success'] = 'Cập nhật người dùng thành công!';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/users/edit/' . $id);
            exit;
        }
    }

    // Xóa user
    public function delete($id) {
        $user = $this->userModel->findById($id);
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        // Không cho phép xóa admin hiện tại
        if ($user['id'] == $_SESSION['user']['id']) {
            $_SESSION['error'] = 'Không thể xóa tài khoản của chính mình!';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Xóa người dùng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa người dùng!';
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    // Cập nhật trạng thái user
    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $status = $_POST['status'] ?? '';

        $validStatuses = ['active', 'inactive', 'banned'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ!';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        if ($this->userModel->updateStatus($id, $status)) {
            $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật trạng thái!';
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    // Cập nhật role của user
    public function updateRole($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $role = $_POST['role'] ?? '';

        $validRoles = ['customer', 'admin', 'staff'];
        if (!in_array($role, $validRoles)) {
            $_SESSION['error'] = 'Vai trò không hợp lệ!';
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        if ($this->userModel->updateRole($id, $role)) {
            $_SESSION['success'] = 'Cập nhật vai trò thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật vai trò!';
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }
}
