<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Báo cáo sản phẩm'; ?></title>

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

        .period-selector {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2rem;
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

        .export-buttons {
            display: flex;
            gap: 1rem;
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

        .data-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
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
            max-height: 300px;
            overflow-y: auto;
        }

        .table-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            align-items: center;
        }

        .table-row:hover {
            background: #f8f9fa;
        }

        .product-name {
            font-weight: 600;
            color: #333;
        }

        .product-sales {
            text-align: center;
            font-weight: 600;
            color: var(--success-color);
        }

        .low-stock-item {
            display: grid;
            grid-template-columns: 1fr auto;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            align-items: center;
        }

        .low-stock-item:hover {
            background: #fff5f5;
        }

        .stock-badge {
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .category-item {
            display: grid;
            grid-template-columns: 2fr 1fr;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            align-items: center;
        }

        .category-name {
            font-weight: 600;
            color: #333;
        }

        .category-count {
            text-align: center;
            font-weight: 600;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .chart-grid {
                grid-template-columns: 1fr;
            }

            .data-section {
                grid-template-columns: 1fr;
            }

            .period-selector {
                flex-direction: column;
                align-items: stretch;
            }

            .export-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Report Header -->
    <div class="report-header">
        <h2><i class="fas fa-box me-2"></i>Báo cáo sản phẩm</h2>
        <p class="text-muted">Phân tích hiệu suất sản phẩm và tồn kho</p>

        <!-- Period Selector -->
        <div class="period-selector">
            <span>Chọn khoảng thời gian:</span>
            <button class="period-btn <?php echo ($data['period'] == '7') ? 'active' : ''; ?>"
                    onclick="changePeriod(7)">7 ngày</button>
            <button class="period-btn <?php echo ($data['period'] == '30') ? 'active' : ''; ?>"
                    onclick="changePeriod(30)">30 ngày</button>
            <button class="period-btn <?php echo ($data['period'] == '90') ? 'active' : ''; ?>"
                    onclick="changePeriod(90)">90 ngày</button>
            <button class="period-btn <?php echo ($data['period'] == '365') ? 'active' : ''; ?>"
                    onclick="changePeriod(365)">1 năm</button>
        </div>

        <!-- Export Buttons -->
        <div class="export-buttons">
            <a href="<?php echo BASE_URL; ?>/admin/analytics/exportExcel?type=products&period=<?php echo $data['period']; ?>"
               class="btn-export">
                <i class="fas fa-file-excel me-1"></i>Xuất Excel
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/analytics/exportPdf?type=products&period=<?php echo $data['period']; ?>"
               class="btn-export">
                <i class="fas fa-file-pdf me-1"></i>Xuất PDF
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-value">
                <?php echo number_format(count($data['topProducts']), 0, ',', '.'); ?>
            </div>
            <div class="stat-label">Sản phẩm bán chạy</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">
                <?php echo number_format(count($data['lowStockProducts']), 0, ',', '.'); ?>
            </div>
            <div class="stat-label">Sản phẩm sắp hết hàng</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">
                <?php
                $totalCategories = count($data['productCategories']);
                echo number_format($totalCategories, 0, ',', '.');
                ?>
            </div>
            <div class="stat-label">Danh mục có sản phẩm</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">
                <?php
                $totalSales = array_sum(array_column($data['topProducts'], 'total_quantity'));
                echo number_format($totalSales, 0, ',', '.');
                ?>
            </div>
            <div class="stat-label">Tổng sản phẩm đã bán</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-container">
        <h4 class="mb-4">Biểu đồ phân tích sản phẩm</h4>
        <div class="chart-grid">
            <div class="chart-item">
                <h5>Top sản phẩm bán chạy</h5>
                <canvas id="topProductsChart"></canvas>
            </div>
            <div class="chart-item">
                <h5>Phân bố theo danh mục</h5>
                <canvas id="categoriesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="data-section">
        <!-- Top Products Table -->
        <div class="data-table">
            <div class="table-header">
                <i class="fas fa-trophy me-2"></i>Top sản phẩm bán chạy
            </div>
            <div class="table-body">
                <?php foreach ($data['topProducts'] as $product): ?>
                    <div class="table-row">
                        <div class="product-name">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </div>
                        <div class="product-sales">
                            <?php echo number_format($product['total_quantity'], 0, ',', '.'); ?> cái
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($data['topProducts'])): ?>
                    <div class="table-row">
                        <div colspan="2" style="text-align: center; color: #666; padding: 2rem;">
                            Chưa có dữ liệu bán hàng trong khoảng thời gian này
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Low Stock Products Table -->
        <div class="data-table">
            <div class="table-header">
                <i class="fas fa-exclamation-triangle me-2"></i>Sản phẩm sắp hết hàng
            </div>
            <div class="table-body">
                <?php foreach ($data['lowStockProducts'] as $product): ?>
                    <div class="low-stock-item">
                        <div class="product-name">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </div>
                        <div class="stock-badge">
                            <?php echo $product['stock']; ?> còn lại
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($data['lowStockProducts'])): ?>
                    <div class="low-stock-item">
                        <div colspan="2" style="text-align: center; color: #666; padding: 2rem;">
                            Không có sản phẩm nào sắp hết hàng
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="data-table">
        <div class="table-header">
            <i class="fas fa-tags me-2"></i>Phân bố sản phẩm theo danh mục
        </div>
        <div class="table-body">
            <?php foreach ($data['productCategories'] as $category): ?>
                <div class="category-item">
                    <div class="category-name">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </div>
                    <div class="category-count">
                        <?php echo number_format($category['product_count'], 0, ',', '.'); ?> sản phẩm
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($data['productCategories'])): ?>
                <div class="category-item">
                    <div colspan="2" style="text-align: center; color: #666; padding: 2rem;">
                        Chưa có dữ liệu danh mục
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Top Products Data
        const topProductsData = <?php echo json_encode($data['topProducts']); ?>;

        const productLabels = topProductsData.map(item => item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name);
        const productSales = topProductsData.map(item => item.total_quantity);

        // Categories Data
        const categoriesData = <?php echo json_encode($data['productCategories']); ?>;

        const categoryLabels = categoriesData.map(item => item.name);
        const categoryCounts = categoriesData.map(item => item.product_count);

        // Top Products Chart
        const productsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(productsCtx, {
            type: 'horizontalBar',
            data: {
                labels: productLabels,
                datasets: [{
                    label: 'Số lượng bán',
                    data: productSales,
                    backgroundColor: 'rgba(40, 167, 69, 0.6)',
                    borderColor: 'rgb(40, 167, 69)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Categories Chart
        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        new Chart(categoriesCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryCounts,
                    backgroundColor: [
                        'rgb(102, 126, 234)',
                        'rgb(40, 167, 69)',
                        'rgb(255, 193, 7)',
                        'rgb(220, 53, 69)',
                        'rgb(23, 162, 184)',
                        'rgb(108, 117, 125)'
                    ],
                    borderWidth: 1
                }]
            },
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
            window.location.href = `<?php echo BASE_URL; ?>/admin/analytics/products?period=${days}`;
        }
    </script>

</body>
</html>
