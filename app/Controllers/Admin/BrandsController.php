<?php
namespace App\Controllers\Admin;

use Core\Controller;

class BrandsController extends Controller {
    private $brandModel;

    public function __construct() {
        $this->brandModel = $this->model('BrandModel');
    }

    // Hiển thị danh sách thương hiệu
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 10;

        $brands = $this->brandModel->getAll($page, $limit, $search);
        $total = $this->brandModel->countAll($search);
        $totalPages = ceil($total / $limit);

        $this->view('admin/brands/index', [
            'title' => 'Quản lý thương hiệu',
            'brands' => $brands,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'layout' => 'admin'
        ]);
    }

    // Hiển thị form tạo thương hiệu
    public function create() {
        $this->view('admin/brands/create', [
            'title' => 'Thêm thương hiệu mới',
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý tạo thương hiệu
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/brands/create');
            exit;
        }

        // Handle file upload
        $logoUrl = '';

        // Upload logo if provided
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            try {
                $cloudinaryService = new \App\Services\CloudinaryService();
                $uploadResult = $cloudinaryService->uploadImage($_FILES['logo_file']['tmp_name'], [
                    'folder' => 'watch_store/brands',
                    'transformation' => [
                        ['width' => 200, 'height' => 200, 'crop' => 'limit'],
                        ['quality' => 'auto']
                    ]
                ]);
                
                if ($uploadResult && isset($uploadResult['url'])) {
                    $logoUrl = $uploadResult['url'];
                }
            } catch (\Exception $e) {
                error_log("Brand upload error: " . $e->getMessage());
                // Don't fallback, just log error. User will see no image.
            }
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'logo_url' => $logoUrl
        ];

        $errors = [];

        // Validation
        if (empty($data['name'])) {
            $errors['name'] = 'Tên thương hiệu không được để trống';
        }

        if (empty($data['slug'])) {
            $errors['slug'] = 'Slug không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/brands/create');
            exit;
        }

        // Tạo thương hiệu
        $brandId = $this->brandModel->create($data);

        if ($brandId) {
            $_SESSION['success'] = 'Thêm thương hiệu thành công!';
            header('Location: ' . BASE_URL . '/admin/brands');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/brands/create');
            exit;
        }
    }

    // Hiển thị form chỉnh sửa thương hiệu
    public function edit($id) {
        $brand = $this->brandModel->findById($id);
        if (!$brand) {
            header('Location: ' . BASE_URL . '/admin/brands');
            exit;
        }

        $this->view('admin/brands/edit', [
            'title' => 'Chỉnh sửa thương hiệu',
            'brand' => $brand,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý cập nhật thương hiệu
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/brands/edit/' . $id);
            exit;
        }

        // Handle file upload
        $logoUrl = trim($_POST['logo_url'] ?? '');

        // Upload new logo if provided
        if (isset($_FILES['logo_file']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
             try {
                $cloudinaryService = new \App\Services\CloudinaryService();
                $uploadResult = $cloudinaryService->uploadImage($_FILES['logo_file']['tmp_name'], [
                    'folder' => 'watch_store/brands',
                    'transformation' => [
                        ['width' => 200, 'height' => 200, 'crop' => 'limit'],
                        ['quality' => 'auto']
                    ]
                ]);
                
                if ($uploadResult && isset($uploadResult['url'])) {
                    $logoUrl = $uploadResult['url'];
                }
            } catch (\Exception $e) {
                error_log("Brand update upload error: " . $e->getMessage());
            }
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'logo_url' => $logoUrl
        ];

        $errors = [];

        // Validation
        if (empty($data['name'])) {
            $errors['name'] = 'Tên thương hiệu không được để trống';
        }

        if (empty($data['slug'])) {
            $errors['slug'] = 'Slug không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/brands/edit/' . $id);
            exit;
        }

        // Cập nhật thương hiệu
        if ($this->brandModel->update($id, $data)) {
            $_SESSION['success'] = 'Cập nhật thương hiệu thành công!';
            header('Location: ' . BASE_URL . '/admin/brands');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/brands/edit/' . $id);
            exit;
        }
    }

    // Xóa thương hiệu
    public function delete($id) {
        $brand = $this->brandModel->findById($id);
        if (!$brand) {
            $_SESSION['error'] = 'Thương hiệu không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/brands');
            exit;
        }

        // Kiểm tra xem thương hiệu có sản phẩm không
        if ($this->brandModel->hasProducts($id)) {
            $_SESSION['error'] = 'Không thể xóa thương hiệu này vì còn sản phẩm!';
            header('Location: ' . BASE_URL . '/admin/brands');
            exit;
        }

        if ($this->brandModel->delete($id)) {
            $_SESSION['success'] = 'Xóa thương hiệu thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa thương hiệu!';
        }

        header('Location: ' . BASE_URL . '/admin/brands');
        exit;
    }
}
