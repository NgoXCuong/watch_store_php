<?php
namespace App\Controllers\Admin;

use Core\Controller;

class ReviewsController extends Controller {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = $this->model('ReviewModel');
    }

    // Hiển thị danh sách đánh giá
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = isset($_GET['status']) ? $_GET['status'] : 'all'; // all, approved, pending
        $limit = 10;

        // Convert status to boolean for model
        $approved = null;
        if ($status === 'approved') {
            $approved = 1;
        } elseif ($status === 'pending') {
            $approved = 0;
        }

        $reviews = $this->reviewModel->getAll($page, $limit, $approved);
        $total = $this->reviewModel->countAll($approved);
        $totalPages = ceil($total / $limit);

        // Lấy thống kê
        $statistics = $this->reviewModel->getStatistics();

        $this->view('admin/reviews/index', [
            'title' => 'Quản lý đánh giá',
            'reviews' => $reviews,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'status' => $status,
            'total' => $total,
            'statistics' => $statistics,
            'layout' => 'admin'
        ]);
    }

    // Duyệt đánh giá
    public function approve($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $review = $this->reviewModel->findById($id);
        if (!$review) {
            $_SESSION['error'] = 'Đánh giá không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        if ($this->reviewModel->approve($id)) {
            $_SESSION['success'] = 'Đã duyệt đánh giá thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi duyệt đánh giá!';
        }

        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }

    // Từ chối đánh giá
    public function reject($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $review = $this->reviewModel->findById($id);
        if (!$review) {
            $_SESSION['error'] = 'Đánh giá không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        if ($this->reviewModel->reject($id)) {
            $_SESSION['success'] = 'Đã từ chối đánh giá!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi từ chối đánh giá!';
        }

        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }

    // Xóa đánh giá
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $review = $this->reviewModel->findById($id);
        if (!$review) {
            $_SESSION['error'] = 'Đánh giá không tồn tại!';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        if ($this->reviewModel->delete($id)) {
            $_SESSION['success'] = 'Xóa đánh giá thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa đánh giá!';
        }

        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }

    // Hiển thị chi tiết đánh giá
    public function show($id) {
        $review = $this->reviewModel->findById($id);
        if (!$review) {
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $this->view('admin/reviews/show', [
            'title' => 'Chi tiết đánh giá',
            'review' => $review,
            'layout' => 'admin'
        ]);
    }

    // Duyệt hàng loạt
    public function bulkApprove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $reviewIds = $_POST['review_ids'] ?? [];
        if (empty($reviewIds)) {
            $_SESSION['error'] = 'Vui lòng chọn đánh giá cần duyệt!';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $successCount = 0;
        foreach ($reviewIds as $id) {
            if ($this->reviewModel->approve($id)) {
                $successCount++;
            }
        }

        if ($successCount > 0) {
            $_SESSION['success'] = 'Đã duyệt ' . $successCount . ' đánh giá thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi duyệt đánh giá!';
        }

        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }

    // Từ chối hàng loạt
    public function bulkReject() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $reviewIds = $_POST['review_ids'] ?? [];
        if (empty($reviewIds)) {
            $_SESSION['error'] = 'Vui lòng chọn đánh giá cần từ chối!';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $successCount = 0;
        foreach ($reviewIds as $id) {
            if ($this->reviewModel->reject($id)) {
                $successCount++;
            }
        }

        if ($successCount > 0) {
            $_SESSION['success'] = 'Đã từ chối ' . $successCount . ' đánh giá!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi từ chối đánh giá!';
        }

        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }

    // Xóa hàng loạt
    public function bulkDelete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $reviewIds = $_POST['review_ids'] ?? [];
        if (empty($reviewIds)) {
            $_SESSION['error'] = 'Vui lòng chọn đánh giá cần xóa!';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }

        $successCount = 0;
        foreach ($reviewIds as $id) {
            if ($this->reviewModel->delete($id)) {
                $successCount++;
            }
        }

        if ($successCount > 0) {
            $_SESSION['success'] = 'Đã xóa ' . $successCount . ' đánh giá thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa đánh giá!';
        }

        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }
}
