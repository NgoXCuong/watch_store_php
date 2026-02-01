<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
        </div>

        <!-- Period Selector -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Chọn khoảng thời gian phân tích:</h5>
                    <div class="btn-group" role="group">
                        <input type="radio" class="btn-check" name="period" id="period7" autocomplete="off" <?php echo ($data['period'] ?? 30) == 7 ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary btn-sm" for="period7" onclick="changePeriod(7)">7 ngày</label>

                        <input type="radio" class="btn-check" name="period" id="period30" autocomplete="off" <?php echo ($data['period'] ?? 30) == 30 ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary btn-sm" for="period30" onclick="changePeriod(30)">30 ngày</label>

                        <input type="radio" class="btn-check" name="period" id="period90" autocomplete="off" <?php echo ($data['period'] ?? 30) == 90 ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary btn-sm" for="period90" onclick="changePeriod(90)">90 ngày</label>

                        <input type="radio" class="btn-check" name="period" id="period365" autocomplete="off" <?php echo ($data['period'] ?? 30) == 365 ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-primary btn-sm" for="period365" onclick="changePeriod(365)">1 năm</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Cards -->
        <div class="row mb-4">
            <!-- Revenue Card -->
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white hover-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Doanh thu</h6>
                                <h4 class="mb-1"><?php echo number_format($data['revenue']['total'] ?? $data['stats']['totalRevenue'], 0, ',', '.'); ?>đ</h4>
                                <?php if (isset($data['revenue']['growth'])): ?>
                                    <p class="text-white <?php echo ($data['revenue']['growth'] >= 0) ? 'text-success' : 'text-warning'; ?>">
                                        <i class="fas fa-arrow-<?php echo ($data['revenue']['growth'] >= 0) ? 'up' : 'down'; ?>"></i>
                                        <?php echo abs($data['revenue']['growth']); ?>%
                                    </p>
                                <?php endif; ?>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Card -->
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white hover-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Đơn hàng</h6>
                                <h4 class="mb-1"><?php echo number_format($data['orders']['total'] ?? $data['stats']['totalOrders'], 0, ',', '.'); ?></h4>
                                <div class="d-flex justify-content-between">
                                    <p>✓ Hoàn thành <?php echo $data['orders']['completed'] ?? 0; ?></p>
                                    <p>⏳Đang xử lý <?php echo $data['orders']['pending'] ?? $data['stats']['pendingOrders']; ?></p>
                                </div>
                            </div>
                            <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Card -->
            <div class="col-md-3 mb-3">
                <div class="card bg-warning text-white hover-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Sản phẩm</h6>
                                <h4 class="mb-1"><?php echo number_format($data['products']['total'] ?? $data['stats']['totalProducts'], 0, ',', '.'); ?></h4>
                                <div class="d-flex justify-content-between">
                                    <p>✓ Hoạt động <?php echo $data['products']['active'] ?? 0; ?></p>
                                    <p>⚠️Hết hàng <?php echo $data['products']['low_stock'] ?? $data['stats']['lowStockProducts']; ?></p>
                                </div>
                            </div>
                            <i class="fas fa-box fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white hover-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">Khách hàng</h6>
                                <h4 class="mb-1"><?php echo number_format($data['customers']['total'] ?? $data['stats']['totalUsers'], 0, ',', '.'); ?></h4>
                                <div class="d-flex justify-content-between">
                                    <p>🆕 Khách hàng mới <?php echo $data['customers']['new'] ?? 0; ?></p>
                                </div>
                            </div>
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Orders Chart
document.addEventListener('DOMContentLoaded', function() {
    const monthlyOrdersData = <?php echo json_encode($data['monthlyOrders']); ?>;

    if (monthlyOrdersData.length > 0) {
        const labels = monthlyOrdersData.map(item => {
            const date = new Date(item.month + '-01');
            return date.toLocaleDateString('vi-VN', { year: 'numeric', month: 'short' });
        });

        const ordersData = monthlyOrdersData.map(item => item.orders);
        const revenueData = monthlyOrdersData.map(item => item.revenue);

        const ctx = document.getElementById('monthlyOrdersChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số đơn hàng',
                    data: ordersData,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y'
                }, {
                    label: 'Doanh thu (VNĐ)',
                    data: revenueData,
                    borderColor: '#4ecdc4',
                    backgroundColor: 'rgba(78, 205, 196, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 1) {
                                    label += new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(context.parsed.y);
                                } else {
                                    label += context.parsed.y + ' đơn';
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Số đơn hàng'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + ' đơn';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Doanh thu (VNĐ)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND',
                                    notation: 'compact'
                                }).format(value);
                            }
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });
    } else {
        // No data available
        const ctx = document.getElementById('monthlyOrdersChart').getContext('2d');
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillStyle = '#666';
        ctx.fillText('Chưa có dữ liệu đơn hàng', ctx.canvas.width / 2, ctx.canvas.height / 2);
    }
});

// Function to change period
function changePeriod(days) {
    const url = new URL(window.location);
    url.searchParams.set('period', days);
    window.location.href = url.toString();
}
</script>

        <!-- Statistics Cards -->
        
        <!-- Charts Section -->
        <div class="row mt-4">
            <!-- Monthly Orders Chart -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Thống kê đơn hàng theo tháng</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyOrdersChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Sản phẩm bán chạy</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($data['topProducts'])): ?>
                            <?php foreach ($data['topProducts'] as $index => $product): ?>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        <span class="badge bg-primary rounded-pill" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                            <?php echo $index + 1; ?>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-truncate" style="max-width: 150px;" title="<?php echo htmlspecialchars($product['name']); ?>">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo $product['total_sold']; ?> đã bán -
                                            <?php echo number_format($product['total_revenue'], 0, ',', '.'); ?>đ
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <p>Chưa có dữ liệu bán hàng</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders & Alerts -->
        <div class="row mt-4">
            <!-- Recent Orders -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Đơn hàng gần đây</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($data['recentOrders'])): ?>
                            <?php foreach ($data['recentOrders'] as $order): ?>
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">#<?php echo $order['id']; ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($order['customer_name']); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</div>
                                        <small class="text-muted"><?php echo date('d/m H:i', strtotime($order['created_at'])); ?></small>
                                    </div>
                                    <div class="ms-3">
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch ($order['status']) {
                                            case 'pending':
                                                $statusClass = 'badge bg-warning';
                                                $statusText = 'Chờ xử lý';
                                                break;
                                            case 'confirmed':
                                                $statusClass = 'badge bg-info';
                                                $statusText = 'Đã xác nhận';
                                                break;
                                            case 'shipping':
                                                $statusClass = 'badge bg-primary';
                                                $statusText = 'Đang giao';
                                                break;
                                            case 'delivered':
                                                $statusClass = 'badge bg-success';
                                                $statusText = 'Đã giao';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'badge bg-danger';
                                                $statusText = 'Đã hủy';
                                                break;
                                            default:
                                                $statusClass = 'badge bg-secondary';
                                                $statusText = 'Chưa xác định';
                                        }
                                        ?>
                                        <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="text-center mt-3">
                                <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-list me-1"></i>Xem tất cả đơn hàng
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <p>Chưa có đơn hàng nào</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Alerts & Quick Actions -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Cảnh báo & Thao tác</h5>
                    </div>
                    <div class="card-body">
                        <!-- Alerts -->
                        <?php if ($data['stats']['pendingOrders'] > 0): ?>
                            <div class="alert alert-warning d-flex align-items-center mb-3">
                                <i class="fas fa-clock me-2"></i>
                                <div>
                                    <strong><?php echo $data['stats']['pendingOrders']; ?> đơn hàng</strong> đang chờ xử lý
                                    <a href="<?php echo BASE_URL; ?>/admin/orders?status=pending" class="alert-link">Xem ngay</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($data['stats']['lowStockProducts'] > 0): ?>
                            <div class="alert alert-danger d-flex align-items-center mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <strong><?php echo $data['stats']['lowStockProducts']; ?> sản phẩm</strong> sắp hết hàng
                                    <a href="<?php echo BASE_URL; ?>/admin/products" class="alert-link">Kiểm tra</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($data['stats']['pendingReviews'] > 0): ?>
                            <div class="alert alert-info d-flex align-items-center mb-3">
                                <i class="fas fa-star me-2"></i>
                                <div>
                                    <strong><?php echo $data['stats']['pendingReviews']; ?> đánh giá</strong> chờ duyệt
                                    <a href="<?php echo BASE_URL; ?>/admin/reviews?status=pending" class="alert-link">Duyệt ngay</a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Quick Actions -->
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/admin/products/create" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-plus me-1"></i>Thêm sản phẩm
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/admin/users/create" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-user-plus me-1"></i>Thêm người dùng
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/admin/vouchers/create" class="btn btn-warning btn-sm w-100">
                                    <i class="fas fa-ticket-alt me-1"></i>Tạo voucher
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?php echo BASE_URL; ?>/admin/reviews" class="btn btn-info btn-sm w-100">
                                    <i class="fas fa-star me-1"></i>Quản lý đánh giá
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
