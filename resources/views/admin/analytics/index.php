<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Phân tích & Báo cáo'; ?></title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .analytics-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .card-change {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .change-positive {
            color: #28a745;
        }

        .change-negative {
            color: #dc3545;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-top: 2rem;
        }

        .period-selector {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .period-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .period-btn {
            padding: 0.5rem 1rem;
            border: 2px solid #e9ecef;
            background: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .period-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .period-btn:hover {
            border-color: var(--primary-color);
        }

        .icon-large {
            font-size: 2rem;
            color: var(--primary-color);
            margin-right: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>

    <!-- Period Selector -->
    <div class="period-selector">
        <h5>Chọn khoảng thời gian:</h5>
        <div class="period-buttons">
            <button class="period-btn <?php echo ($data['period'] == '7') ? 'active' : ''; ?>" onclick="changePeriod(7)">7 ngày</button>
            <button class="period-btn <?php echo ($data['period'] == '30') ? 'active' : ''; ?>" onclick="changePeriod(30)">30 ngày</button>
            <button class="period-btn <?php echo ($data['period'] == '90') ? 'active' : ''; ?>" onclick="changePeriod(90)">90 ngày</button>
            <button class="period-btn <?php echo ($data['period'] == '365') ? 'active' : ''; ?>" onclick="changePeriod(365)">1 năm</button>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="analytics-grid">
        <!-- Revenue Card -->
        <div class="analytics-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-dollar-sign icon-large"></i>
                        Doanh thu
                    </h3>
                </div>
            </div>
            <div class="card-value">
                <?php echo number_format($data['revenue']['total'], 0, ',', '.'); ?> VND
            </div>
            <div class="card-change <?php echo ($data['revenue']['growth'] >= 0) ? 'change-positive' : 'change-negative'; ?>">
                <i class="fas fa-arrow-<?php echo ($data['revenue']['growth'] >= 0) ? 'up' : 'down'; ?>"></i>
                <?php echo abs($data['revenue']['growth']); ?>% so với kỳ trước
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?php echo number_format($data['revenue']['average'], 0, ',', '.'); ?> VND</span>
                    <div class="stat-label">Giá trị TB/đơn</div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="analytics-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-shopping-cart icon-large"></i>
                        Đơn hàng
                    </h3>
                </div>
            </div>
            <div class="card-value">
                <?php echo number_format($data['orders']['total'], 0, ',', '.'); ?>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['orders']['pending']; ?></span>
                    <div class="stat-label">Chờ xử lý</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['orders']['completed']; ?></span>
                    <div class="stat-label">Hoàn thành</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['orders']['cancelled']; ?></span>
                    <div class="stat-label">Đã hủy</div>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="analytics-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-box icon-large"></i>
                        Sản phẩm
                    </h3>
                </div>
            </div>
            <div class="card-value">
                <?php echo number_format($data['products']['total'], 0, ',', '.'); ?>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['products']['active']; ?></span>
                    <div class="stat-label">Đang bán</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['products']['low_stock']; ?></span>
                    <div class="stat-label">Sắp hết hàng</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['products']['featured']; ?></span>
                    <div class="stat-label">Nổi bật</div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="analytics-card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-users icon-large"></i>
                        Khách hàng
                    </h3>
                </div>
            </div>
            <div class="card-value">
                <?php echo number_format($data['customers']['total'], 0, ',', '.'); ?>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['customers']['new']; ?></span>
                    <div class="stat-label">Khách mới</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $data['customers']['active']; ?></span>
                    <div class="stat-label">Đang hoạt động</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="analytics-grid">
        <div class="analytics-card">
            <h3 class="card-title">Doanh thu theo tháng</h3>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="analytics-card">
            <h3 class="card-title">Trạng thái đơn hàng</h3>
            <div class="chart-container">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="analytics-card">
        <h3 class="card-title">Xuất báo cáo</h3>
        <div class="period-buttons" style="margin-top: 1rem;">
            <a href="<?php echo BASE_URL; ?>/admin/analytics/revenue" class="period-btn">
                <i class="fas fa-chart-line me-1"></i>Báo cáo doanh thu
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/analytics/products" class="period-btn">
                <i class="fas fa-box me-1"></i>Báo cáo sản phẩm
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/analytics/exportExcel?type=summary" class="period-btn">
                <i class="fas fa-file-excel me-1"></i>Xuất Excel
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/analytics/exportPdf?type=summary" class="period-btn">
                <i class="fas fa-file-pdf me-1"></i>Xuất PDF
            </a>
        </div>
    </div>

    <script>
        // Sample data for charts (you would replace this with real data from PHP)
        const monthlyRevenueData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Doanh thu (VND)',
                data: [12000000, 15000000, 18000000, 22000000, 25000000, 28000000, 30000000, 32000000, 29000000, 31000000, 35000000, 38000000],
                borderColor: 'rgb(102, 126, 234)',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        };

        const ordersStatusData = {
            labels: ['Chờ xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                data: [<?php echo $data['orders']['pending']; ?>, 15, <?php echo $data['orders']['completed']; ?>, <?php echo $data['orders']['cancelled']; ?>],
                backgroundColor: [
                    'rgb(255, 193, 7)',
                    'rgb(0, 123, 255)',
                    'rgb(40, 167, 69)',
                    'rgb(220, 53, 69)'
                ],
                borderWidth: 1
            }]
        };

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: monthlyRevenueData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000).toFixed(0) + 'M';
                            }
                        }
                    }
                }
            }
        });

        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'doughnut',
            data: ordersStatusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        function changePeriod(days) {
            window.location.href = `<?php echo BASE_URL; ?>/admin/analytics?period=${days}`;
        }
    </script>

</body>
</html>
