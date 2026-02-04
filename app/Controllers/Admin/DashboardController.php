<?php
namespace App\Controllers\Admin;

use Core\Controller;

class DashboardController extends Controller {
    private $productModel;
    private $orderModel;
    private $userModel;
    private $reviewModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel');
        $this->orderModel = $this->model('OrderModel');
        $this->userModel = $this->model('UserModel');
        $this->reviewModel = $this->model('ReviewModel');
    }

    public function index() {
        $period = isset($_GET['period']) ? (int)$_GET['period'] : 30;

        // Lấy thống kê thực tế
        $stats = $this->getDashboardStats();

        // Lấy dữ liệu analytics theo period
        $revenue = $this->getRevenueAnalytics($period);
        $orders = $this->getOrderAnalytics($period);
        $products = $this->getProductAnalytics($period);
        $customers = $this->getCustomerAnalytics($period);

        // Lấy dữ liệu cho biểu đồ (đơn hàng theo tháng)
        $monthlyOrders = $this->getMonthlyOrders();

        // Lấy sản phẩm bán chạy
        $topProducts = $this->getTopProducts();

        // Lấy đơn hàng gần đây
        $recentOrders = $this->getRecentOrders();

        $this->view('admin/dashboard', [
            'title' => 'Trang quản trị Watch Store',
            'layout' => 'admin',
            'stats' => $stats,
            'period' => $period,
            'revenue' => $revenue,
            'orders' => $orders,
            'products' => $products,
            'customers' => $customers,
            'monthlyOrders' => $monthlyOrders,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders
        ]);
    }

    private function getDashboardStats() {
        // Thống kê tổng quan
        $totalProducts = $this->productModel->countAll();
        $totalOrders = $this->orderModel->countAll();
        $totalUsers = $this->userModel->countAll();
        $totalRevenue = $this->getTotalRevenue();

        // Thống kê theo trạng thái
        $pendingOrders = $this->orderModel->countAll('', 'pending');
        $lowStockProducts = $this->getLowStockProducts();
        $pendingReviews = $this->reviewModel->countAll(0); // is_approved = 0

        return [
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'pendingOrders' => $pendingOrders,
            'lowStockProducts' => $lowStockProducts,
            'pendingReviews' => $pendingReviews
        ];
    }

    private function getTotalRevenue() {
        return $this->orderModel->getTotalRevenue();
    }

    private function getLowStockProducts() {
        return $this->productModel->getLowStockCount();
    }

    private function getMonthlyOrders() {
        return $this->orderModel->getMonthlyOrders();
    }

    private function getTopProducts() {
        return $this->productModel->getTopSellingProducts(5);
    }

    private function getRecentOrders() {
        return $this->orderModel->getRecentOrders(3);
    }

    // Analytics methods for dashboard
    private function getRevenueAnalytics($days) {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        return [
            'total' => $this->orderModel->getRevenueByDateRange($startDate, $endDate),
            'growth' => $this->calculateGrowth($days),
            'average' => $this->orderModel->getAverageOrderValue($startDate, $endDate)
        ];
    }

    private function getOrderAnalytics($days) {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        return [
            'total' => $this->orderModel->countOrdersByDateRange($startDate, $endDate),
            'pending' => $this->orderModel->countOrdersByStatus('pending', $startDate, $endDate),
            'completed' => $this->orderModel->countOrdersByStatus('delivered', $startDate, $endDate),
            'cancelled' => $this->orderModel->countOrdersByStatus('cancelled', $startDate, $endDate)
        ];
    }

    private function getProductAnalytics($days) {
        return [
            'total' => $this->productModel->countAll(),
            'active' => $this->productModel->countByStatus('active'),
            'inactive' => $this->productModel->countByStatus('inactive'),
            'low_stock' => $this->productModel->getLowStockCount(),
            'featured' => $this->productModel->countFeatured()
        ];
    }

    private function getCustomerAnalytics($days) {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-{$days} days"));

        return [
            'total' => $this->userModel->countAll(),
            'new' => $this->userModel->countNewUsers($startDate, $endDate),
            'active' => $this->userModel->countActiveUsers($days)
        ];
    }

    private function calculateGrowth($days) {
        $currentPeriod = $this->orderModel->getRevenueByDateRange(
            date('Y-m-d', strtotime("-{$days} days")),
            date('Y-m-d')
        );

        $previousPeriod = $this->orderModel->getRevenueByDateRange(
            date('Y-m-d', strtotime("-" . ($days * 2) . " days")),
            date('Y-m-d', strtotime("-{$days} days"))
        );

        if ($previousPeriod == 0) return 0;

        return (($currentPeriod - $previousPeriod) / $previousPeriod) * 100;
    }
}
