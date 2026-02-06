<?php
namespace App\Controllers\Admin;

use Core\Controller;

class AnalyticsController extends Controller {
    private $orderModel;
    private $productModel;
    private $userModel;

    public function __construct() {
        $this->orderModel = $this->model('OrderModel');
        $this->productModel = $this->model('ProductModel');
        $this->userModel = $this->model('UserModel');
    }

    // Trang tổng quan analytics
    public function index() {
        $period = isset($_GET['period']) ? $_GET['period'] : '30'; // days

        $data = [
            'revenue' => $this->getRevenueAnalytics($period),
            'orders' => $this->getOrderAnalytics($period),
            'products' => $this->getProductAnalytics($period),
            'customers' => $this->getCustomerAnalytics($period),
            'monthlyRevenue' => $this->getMonthlyRevenueData(),
            'period' => $period,
            'title' => 'Phân tích & Báo cáo',
            'layout' => 'admin'
        ];

        $this->view('admin/analytics/index', $data);
    }

    // Báo cáo doanh thu
    public function revenue() {
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $revenueData = $this->getRevenueReport($startDate, $endDate);
        $monthlyRevenue = $this->getMonthlyRevenueData();
        $dailyRevenue = $this->getDailyRevenueData($startDate, $endDate);

        $this->view('admin/analytics/revenue', [
            'title' => 'Báo cáo doanh thu',
            'revenueData' => $revenueData,
            'monthlyRevenue' => $monthlyRevenue,
            'dailyRevenue' => $dailyRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'layout' => 'admin'
        ]);
    }

    // Báo cáo sản phẩm
    public function products() {
        $period = isset($_GET['period']) ? $_GET['period'] : '30';

        $topProducts = $this->getTopSellingProducts($period);
        $lowStockProducts = $this->getLowStockProducts();
        $productCategories = $this->getProductCategoriesData();

        $this->view('admin/analytics/products', [
            'title' => 'Báo cáo sản phẩm',
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
            'productCategories' => $productCategories,
            'period' => $period,
            'layout' => 'admin'
        ]);
    }

    // Xuất báo cáo Excel
    public function exportExcel() {
        $type = isset($_GET['type']) ? $_GET['type'] : 'revenue';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report_' . $type . '_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');

        echo $this->generateExcelReport($type, $startDate, $endDate);
        exit;
    }

    // Xuất báo cáo PDF
    public function exportPdf() {
        $type = isset($_GET['type']) ? $_GET['type'] : 'revenue';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-30 days'));
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        // Set headers for PDF download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="report_' . $type . '_' . date('Y-m-d') . '.pdf"');
        header('Cache-Control: max-age=0');

        echo $this->generatePdfReport($type, $startDate, $endDate);
        exit;
    }

    // Private methods for data retrieval
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

    private function getRevenueReport($startDate, $endDate) {
        return $this->orderModel->getRevenueByDateRange($startDate, $endDate);
    }

    private function getMonthlyRevenueData() {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $startDate = date('Y-m-01', strtotime("-{$i} months"));
            $endDate = date('Y-m-t', strtotime("-{$i} months"));

            $data[] = [
                'month' => $month,
                'revenue' => $this->orderModel->getRevenueByDateRange($startDate, $endDate),
                'orders' => $this->orderModel->countOrdersByDateRange($startDate, $endDate)
            ];
        }
        return $data;
    }

    private function getDailyRevenueData($startDate, $endDate) {
        $data = [];
        $currentDate = strtotime($startDate);
        $endDateTime = strtotime($endDate);

        while ($currentDate <= $endDateTime) {
            $date = date('Y-m-d', $currentDate);
            $data[] = [
                'date' => $date,
                'revenue' => $this->orderModel->getRevenueByDate($date),
                'orders' => $this->orderModel->countOrdersByDate($date)
            ];
            $currentDate = strtotime('+1 day', $currentDate);
        }
        return $data;
    }

    private function getTopSellingProducts($days) {
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        return $this->productModel->getTopSellingProductsByDateRange($startDate, $endDate, 10);
    }

    private function getLowStockProducts() {
        return $this->productModel->getLowStockProducts(10);
    }

    private function getProductCategoriesData() {
        return $this->productModel->getProductsByCategories();
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

    private function generateExcelReport($type, $startDate, $endDate) {
        $output = "<table border='1'>";
        $output .= "<tr><th>Date</th><th>Revenue</th><th>Orders</th></tr>";

        $data = $this->getDailyRevenueData($startDate, $endDate);

        foreach ($data as $row) {
            $output .= "<tr>";
            $output .= "<td>{$row['date']}</td>";
            $output .= "<td>" . number_format($row['revenue'], 0, ',', '.') . " VND</td>";
            $output .= "<td>{$row['orders']}</td>";
            $output .= "</tr>";
        }

        $output .= "</table>";
        return $output;
    }


}
