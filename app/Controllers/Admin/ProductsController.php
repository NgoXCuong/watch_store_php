<?php
namespace App\Controllers\Admin;

use Core\Controller;

class ProductsController extends Controller {
    private $productModel;
    private $categoryModel;
    private $brandModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->categoryModel = $this->model('CategoryModel');
        $this->brandModel = $this->model('BrandModel');
    }

    // Hiển thị danh sách sản phẩm
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $brandId = isset($_GET['brand']) ? (int)$_GET['brand'] : null;
        $limit = 10;

        $products = $this->productModel->getAll($page, $limit, $search, $categoryId, $brandId);
        $total = $this->productModel->countAll($search, $categoryId, $brandId);
        $totalPages = ceil($total / $limit);

        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();

        $this->view('admin/products/index', [
            'title' => 'Quản lý sản phẩm',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'selectedCategory' => $categoryId,
            'selectedBrand' => $brandId,
            'layout' => 'admin'
        ]);
    }

    // Hiển thị form tạo sản phẩm
    public function create() {
        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();

        $this->view('admin/products/create', [
            'title' => 'Thêm sản phẩm mới',
            'categories' => $categories,
            'brands' => $brands,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý tạo sản phẩm
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/products/create');
            exit;
        }

        // Handle file uploads
        $imageUrl = '';
        $galleryUrls = '[]'; // Initialize as empty JSON array for database

        // Upload main image
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $cloudinaryService = new \App\Services\CloudinaryService();
            $uploadResult = $cloudinaryService->uploadImage($_FILES['image_file']['tmp_name']);
            if ($uploadResult) {
                $imageUrl = $uploadResult['url'];
            } else {
                $errors['image_file'] = 'Upload ảnh chính thất bại. Vui lòng thử lại.';
            }
        }

        // Upload gallery images
        if (isset($_FILES['gallery_files']) && is_array($_FILES['gallery_files']['error'])) {
            $cloudinaryService = new \App\Services\CloudinaryService();
            $uploadedUrls = [];

            foreach ($_FILES['gallery_files']['error'] as $key => $error) {
                if ($error === UPLOAD_ERR_OK) {
                    $uploadResult = $cloudinaryService->uploadImage($_FILES['gallery_files']['tmp_name'][$key]);
                    if ($uploadResult) {
                        $uploadedUrls[] = $uploadResult['url'];
                    } else {
                        $errors['gallery_files'] = 'Upload ảnh gallery thất bại. Vui lòng thử lại.';
                        break; // Stop on first failure
                    }
                }
            }

            if (!empty($uploadedUrls)) {
                $galleryUrls = json_encode($uploadedUrls);
            }
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'old_price' => !empty($_POST['old_price']) ? (float)$_POST['old_price'] : null,
            'stock' => (int)($_POST['stock'] ?? 0),
            'brand_id' => (int)($_POST['brand_id'] ?? 0),
            'category_ids' => $_POST['category_ids'] ?? [], // Array of IDs
        ];

        $data['specifications'] = !empty(trim($_POST['specifications'] ?? '')) ? trim($_POST['specifications']) : null;
        $data['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
        $data['status'] = $_POST['status'] ?? 'active';

        // Add file URLs to data
        if (!empty($imageUrl)) {
             $data['image_url'] = $imageUrl;
        }
        if (!empty($galleryUrls) && $galleryUrls != '[]') {
             $data['gallery_urls'] = $galleryUrls;
        }

        $errors = [];

        // Validation
        if (empty($data['name'])) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }

        if (empty($data['slug'])) {
            $errors['slug'] = 'Slug không được để trống';
        }

        if ($data['price'] <= 0) {
            $errors['price'] = 'Giá sản phẩm phải lớn hơn 0';
        }

        if ($data['brand_id'] <= 0) {
            $errors['brand_id'] = 'Vui lòng chọn thương hiệu';
        }

        if (empty($data['category_ids'])) {
            $errors['category_id'] = 'Vui lòng chọn ít nhất một danh mục';
        }

        // Tạo sản phẩm
        $productId = $this->productModel->create($data);

        if ($productId) {
            $_SESSION['success'] = 'Thêm sản phẩm thành công!';
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/products/create');
            exit;
        }
    }

    // Hiển thị chi tiết sản phẩm
    public function show($id) {
        $product = $this->productModel->findById($id);
        if (!$product) {
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        }

        $this->view('admin/products/show', [
            'title' => 'Chi tiết sản phẩm',
            'product' => $product,
            'layout' => 'admin'
        ]);
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id) {
        $product = $this->productModel->findById($id);
        if (!$product) {
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        $brands = $this->brandModel->getAll();

        $this->view('admin/products/edit', [
            'title' => 'Chỉnh sửa sản phẩm',
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'admin'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Xử lý cập nhật sản phẩm
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/products/edit/' . $id);
            exit;
        }

        // Handle file uploads
        $imageUrl = trim($_POST['image_url'] ?? '');
        $galleryUrls = trim($_POST['gallery_urls'] ?? '');

        // Upload new main image if provided
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $cloudinaryService = new \App\Services\CloudinaryService();
            $uploadResult = $cloudinaryService->uploadImage($_FILES['image_file']['tmp_name']);
            if ($uploadResult) {
                $imageUrl = $uploadResult['url'];
            }
        }

        // Upload new gallery images if provided
        if (isset($_FILES['gallery_files']) && is_array($_FILES['gallery_files']['error'])) {
            $cloudinaryService = new \App\Services\CloudinaryService();
            $uploadedUrls = [];

            foreach ($_FILES['gallery_files']['error'] as $key => $error) {
                if ($error === UPLOAD_ERR_OK) {
                    $uploadResult = $cloudinaryService->uploadImage($_FILES['gallery_files']['tmp_name'][$key]);
                    if ($uploadResult) {
                        $uploadedUrls[] = $uploadResult['url'];
                    }
                }
            }

            if (!empty($uploadedUrls)) {
                $galleryUrls = json_encode($uploadedUrls);
            }
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'old_price' => !empty($_POST['old_price']) ? (float)$_POST['old_price'] : null,
            'stock' => (int)($_POST['stock'] ?? 0),
            'brand_id' => (int)($_POST['brand_id'] ?? 0),
            'category_ids' => $_POST['category_ids'] ?? [], // Array
            'image_url' => $imageUrl,
            'gallery_urls' => $galleryUrls,
            'specifications' => !empty(trim($_POST['specifications'] ?? '')) ? trim($_POST['specifications']) : null,
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'status' => $_POST['status'] ?? 'active'
        ];

        $errors = [];

        // Validation
        if (empty($data['name'])) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }

        if (empty($data['slug'])) {
            $errors['slug'] = 'Slug không được để trống';
        }

        if ($data['price'] <= 0) {
            $errors['price'] = 'Giá sản phẩm phải lớn hơn 0';
        }

        if ($data['brand_id'] <= 0) {
            $errors['brand_id'] = 'Vui lòng chọn thương hiệu';
        }

        if (empty($data['category_ids'])) {
            $errors['category_id'] = 'Vui lòng chọn ít nhất một danh mục';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/products/edit/' . $id);
            exit;
        }

        // Cập nhật sản phẩm
        if ($this->productModel->update($id, $data)) {
            $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
            header('Location: ' . BASE_URL . '/admin/products');
            exit;
        } else {
            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra, vui lòng thử lại'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/admin/products/edit/' . $id);
            exit;
        }
    }

    // Xóa sản phẩm
    public function delete($id) {
        try {
            if ($this->productModel->delete($id)) {
                $_SESSION['success'] = 'Xóa sản phẩm thành công!';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa sản phẩm!';
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == '23000') {
                $_SESSION['error'] = 'Không thể xóa sản phẩm này vì đã có đơn hàng liên quan. Vui lòng chuyển trạng thái sang "Ngừng kinh doanh" thay vì xóa.';
            } else {
                $_SESSION['error'] = 'Lỗi cơ sở dữ liệu: ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/admin/products');
        exit;
    }
}
