<?php
namespace App\Controllers\Admin;

use Core\Controller;

class CategoriesController extends Controller {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = $this->model('CategoryModel');
    }

    // Hiển thị danh sách danh mục
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 8; // User requested 8 items per page

        $categories = $this->categoryModel->getAll();

        // Tổ chức danh mục theo cấp
        $organizedCategories = $this->organizeCategories($categories);

        // Pagination Logic for Array
        $total = count($organizedCategories);
        $totalPages = ceil($total / $limit);
        
        // Ensure page validity
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;

        $offset = ($page - 1) * $limit;
        
        // Slice the array for current page
        $pagedCategories = array_slice($organizedCategories, $offset, $limit);

        $this->view('admin/categories/index', [
            'title' => 'Quản lý danh mục',
            'categories' => $pagedCategories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'layout' => 'admin'
        ]);
    }

    // Hiển thị form tạo danh mục
    public function create() {
        $categories = $this->categoryModel->getAll();

        $this->view('admin/categories/create', [
            'title' => 'Thêm danh mục mới',
            'categories' => $categories,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý tạo danh mục
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/categories/create');
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null
        ];

        $errors = [];

        // Validation
        if (empty($data['name'])) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }

        if (empty($data['slug'])) {
            $errors['slug'] = 'Slug không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/categories/create');
            exit;
        }

        // Tạo danh mục
        $categoryId = $this->categoryModel->create($data);

        if ($categoryId) {
            $_SESSION['success'] = 'Thêm danh mục thành công!';
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/categories/create');
            exit;
        }
    }

    // Hiển thị form chỉnh sửa danh mục
    public function edit($id) {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        }

        $categories = $this->categoryModel->getAll();

        $this->view('admin/categories/edit', [
            'title' => 'Chỉnh sửa danh mục',
            'category' => $category,
            'categories' => $categories,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý cập nhật danh mục
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/categories/edit/' . $id);
            exit;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'parent_id' => !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null
        ];

        $errors = [];

        // Validation
        if (empty($data['name'])) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }

        if (empty($data['slug'])) {
            $errors['slug'] = 'Slug không được để trống';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/categories/edit/' . $id);
            exit;
        }

        // Cập nhật danh mục
        if ($this->categoryModel->update($id, $data)) {
            $_SESSION['success'] = 'Cập nhật danh mục thành công!';
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/categories/edit/' . $id);
            exit;
        }
    }

    // Xóa danh mục
    public function delete($id) {
        $category = $this->categoryModel->findById($id);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        }

        // Kiểm tra xem danh mục có sản phẩm không
        if ($this->categoryModel->hasProducts($id)) {
            $_SESSION['error'] = 'Không thể xóa danh mục này vì còn sản phẩm!';
            header('Location: ' . BASE_URL . '/admin/categories');
            exit;
        }

        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = 'Xóa danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa danh mục!';
        }

        header('Location: ' . BASE_URL . '/admin/categories');
        exit;
    }

    // Hàm tổ chức danh mục theo cấp
    private function organizeCategories($categories) {
        $organized = [];
        $parents = [];
        $children = [];

        // Phân loại danh mục cha và con
        foreach ($categories as $category) {
            if ($category['parent_id'] === null) {
                $parents[] = $category;
            } else {
                $children[$category['parent_id']][] = $category;
            }
        }

        // Tổ chức theo cấp
        foreach ($parents as $parent) {
            $organized[] = $parent;
            if (isset($children[$parent['id']])) {
                foreach ($children[$parent['id']] as $child) {
                    $child['name'] = '  └── ' . $child['name']; // Thêm indent
                    $organized[] = $child;
                }
            }
        }

        return $organized;
    }
}
