<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Báo cáo doanh thu'; ?></title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .report-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .date-filters {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .date-input {
            padding: 0.5rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .btn-filter {
            padding: 0.5rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-filter:hover {
            background: var(--secondary-color);
        }

        .export-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .btn-export {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--primary-color);
            background: white;
            color: var(--primary-color);
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            background: var(--primary-color);
            color: white;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .chart-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .chart-item {
            height: 400px;
        }

        .data-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .table-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem;
            font-weight: 600;
        }

        .table-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .table-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            align-items: center;
        }

        .table-row:hover {
            background: #f8f9fa;
        }

        .table-cell {
            text-align: center;
        }

        .revenue-amount {
            font-weight: 600;
            color: var(--success-color);
        }

        @media (max-width: 768px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }

            .date-filters {
                flex-direction: column;
                align-items: stretch;
            }

            .export-buttons {
                flex-direction: column;
            }

            .table-row {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                text-align: left;
            }
        }
    </style>
</head>
<body>

    <!-- Report Header -->
    <div class="report-header">
        <h2><i class="fas fa-chart-line me-2"></i>Báo cáo doanh thu</h2>
        <p class="text-muted">Phân tích chi tiết doanh thu theo thời gian</p>

        <!-- Date Filters -->
        <form method="GET" class="date-filters">
            <label for="start_date">Từ ngày:</label>
            <input type="date" id="start_date" name="start_date" class="date-input"
                   value="<?php echo $data['startDate']; ?>">

            <label for="end_date">Đến ngày:</label>
            <input type="date" id="end_date" name="end_date" class="date-input"
                   value="<?php echo $data['endDate']; ?>">

            <button type="submit" class="btn-filter">
                <i class="fas fa-filter me-1"></i>Lọc
            </button>
        </form>

        <!-- Export Buttons -->
        <div class="export-buttons">
            <a href="<?php echo BASE_URL; ?>/admin/analytics/exportExcel?type=revenue&start_date=<?php echo $data['startDate']; ?>&end_date=<?php echo $data['endDate']; ?>"
               class="btn-export">
                <i class="fas fa-file-excel me-1"></i>Xuất Excel
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/analytics/exportPdf?type=revenue&start_date=<?php echo $data['startDate']; ?>&end_date=<?php echo $data['endDate']; ?>"
               class="btn-export">
                <i class="fas fa-file-pdf me-1"></i>Xuất PDF
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-value">
                <?php echo number_format($data['revenueData'], 0, ',', '.'); ?> VND
            </div>
            <div class="stat-label">Tổng doanh thu</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">
                <?php echo count($data['dailyRevenue']); ?>
            </div>
            <div class="stat-label">Số ngày có doanh thu</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">
                <?php
                $totalOrders = array_sum(array_column($data['dailyRevenue'], 'orders'));
                echo number_format($totalOrders, 0, ',', '.');
                ?>
            </div>
            <div class="stat-label">Tổng đơn hàng</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">
                <?php
                $avgOrderValue = $totalOrders > 0 ? $data['revenueData'] / $totalOrders : 0;
                echo number_format($avgOrderValue, 0, ',', '.');
                ?> VND
            </div>
            <div class="stat-label">Giá trị TB/đơn</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-container">
        <h4 class="mb-4">Biểu đồ doanh thu</h4>
        <div class="chart-grid">
            <div class="chart-item">
                <h5>Doanh thu theo tháng</h5>
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
            <div class="chart-item">
                <h5>Doanh thu theo ngày</h5>
                <canvas id="dailyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="data-table">
        <div class="table-header">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr;">
                <div>Ngày</div>
                <div>Doanh thu</div>
                <div>Số đơn hàng</div>
            </div>
        </div>
        <div class="table-body">
            <?php foreach ($data['dailyRevenue'] as $row): ?>
                <div class="table-row">
                    <div class="table-cell"><?php echo date('d/m/Y', strtotime($row['date'])); ?></div>
                    <div class="table-cell revenue-amount">
                        <?php echo number_format($row['revenue'], 0, ',', '.'); ?> VND
                    </div>
                    <div class="table-cell"><?php echo $row['orders']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Monthly Revenue Data
        const monthlyData = <?php echo json_encode($data['monthlyRevenue']); ?>;

        const monthlyLabels = monthlyData.map(item => item.month);
        const monthlyRevenue = monthlyData.map(item => item.revenue);

        // Daily Revenue Data
        const dailyData = <?php echo json_encode($data['dailyRevenue']); ?>;

        const dailyLabels = dailyData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('vi-VN');
        });
        const dailyRevenue = dailyData.map(item => item.revenue);

        // Monthly Revenue Chart
        const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: monthlyRevenue,
                    backgroundColor: 'rgba(102, 126, 234, 0.6)',
                    borderColor: 'rgb(102, 126, 234)',
                    borderWidth: 1
                }]
            },
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

        // Daily Revenue Chart
        const dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: dailyRevenue,
                    borderColor: 'rgb(40, 167, 69)',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
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
    </script>

</body>
</html>
