<div class="container-fluid p-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
        <div>
            <h2 class="text-uppercase fw-bold text-dark mb-1" style=" letter-spacing: 1px;">
                Tổng Quan Hoạt Động
            </h2>
            <p class="text-muted mb-0">Theo dõi hiệu suất kinh doanh của bạn</p>
        </div>
        
        <!-- Period Selector -->
        <div class="bg-white p-1 rounded-pill shadow-sm d-flex align-items-center border">
            <?php 
            $periods = [
                '7' => '7 ngày',
                '30' => '30 ngày', 
                '90' => '90 ngày',
                '365' => '1 năm'
            ];
            foreach($periods as $val => $label): 
            ?>
            <a href="<?php echo BASE_URL; ?>/admin/analytics?period=<?php echo $val; ?>" 
               class="btn btn-sm rounded-pill px-3 <?php echo ($data['period'] == $val) ? 'btn-dark' : 'btn-light text-muted bg-transparent border-0'; ?> fw-semibold transition-300">
                <?php echo $label; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        <!-- Revenue Card -->
        <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.1s;">
            <div class="card border-0 shadow-hover h-100 overflow-hidden text-white" 
                 style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border-radius: 20px;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-0 end-0 p-3 opacity-10">
                        <i class="fas fa-dollar-sign fa-5x"></i>
                    </div>
                    <div class="d-flex flex-column h-100 position-relative z-1">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center bg-white bg-opacity-25 rounded-pill px-2 py-1 text-xs">
                                <i class="fas fa-wallet me-1"></i> Doanh thu
                            </div>
                        </div>
                        <h3 class="mb-1 fw-bold"><?php echo number_format($data['revenue']['total'], 0, ',', '.'); ?> ₫</h3>
                        <div class="mt-auto d-flex align-items-center text-sm">
                            <span class="<?php echo ($data['revenue']['growth'] >= 0) ? 'text-success' : 'text-danger'; ?> bg-white bg-opacity-10 px-2 py-1 rounded me-2">
                                <i class="fas fa-arrow-<?php echo ($data['revenue']['growth'] >= 0) ? 'up' : 'down'; ?>"></i>
                                <?php echo abs(round($data['revenue']['growth'], 1)); ?>%
                            </span>
                            <span class="opacity-75">so với kỳ trước</span>
                        </div>
                         <div class="mt-3 pt-3 border-top border-white border-opacity-10">
                            <div class="d-flex justify-content-between text-sm opacity-90">
                                <span>TB đơn hàng:</span>
                                <span class="fw-bold text-warning"><?php echo number_format($data['revenue']['average'], 0, ',', '.'); ?> ₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.2s;">
            <div class="card border-0 shadow-hover h-100" style="background: white; border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="text-uppercase text-muted text-xs fw-bold tracking-wider mb-1">Đơn hàng</p>
                            <h3 class="fw-bold text-dark mb-0"><?php echo number_format($data['orders']['total']); ?></h3>
                        </div>
                        <div class="icon-shape bg-light text-primary rounded-circle p-3">
                            <i class="fas fa-shopping-bag fa-lg"></i>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-auto">
                        <div class="flex-fill text-center p-2 rounded bg-warning bg-opacity-10">
                            <div class="text-warning fw-bold h5 mb-0"><?php echo $data['orders']['pending']; ?></div>
                            <div class="text-xs text-muted text-uppercase">Chờ xử lý</div>
                        </div>
                        <div class="flex-fill text-center p-2 rounded bg-success bg-opacity-10">
                            <div class="text-success fw-bold h5 mb-0"><?php echo $data['orders']['completed']; ?></div>
                            <div class="text-xs text-muted text-uppercase">Hoàn thành</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.3s;">
            <div class="card border-0 shadow-hover h-100" style="background: white; border-radius: 20px;">
                <div class="card-body p-4">
                     <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="text-uppercase text-muted text-xs fw-bold tracking-wider mb-1">Khách hàng</p>
                            <h3 class="fw-bold text-dark mb-0"><?php echo number_format($data['customers']['total']); ?></h3>
                        </div>
                        <div class="icon-shape bg-light text-info rounded-circle p-3">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <div class="d-flex justify-content-between text-sm mb-1">
                            <span class="text-muted">Khách mới</span>
                            <span class="fw-bold text-dark">+<?php echo number_format($data['customers']['new']); ?></span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 70%"></div>
                        </div>
                    </div>
                    <div class="mb-0">
                         <div class="d-flex justify-content-between text-sm mb-1">
                            <span class="text-muted">Đang hoạt động</span>
                            <span class="fw-bold text-dark"><?php echo number_format($data['customers']['active']); ?></span>
                        </div>
                         <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="col-xl-3 col-md-6 fade-in-up" style="animation-delay: 0.4s;">
            <div class="card border-0 shadow-hover h-100" style="background: white; border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                         <div>
                            <p class="text-uppercase text-muted text-xs fw-bold tracking-wider mb-1">Sản phẩm</p>
                            <h3 class="fw-bold text-dark mb-0"><?php echo number_format($data['products']['total']); ?></h3>
                        </div>
                        <div class="icon-shape bg-light text-danger rounded-circle p-3">
                            <i class="fas fa-box-open fa-lg"></i>
                        </div>
                    </div>
                    
                    <div class="row g-2 text-center">
                        <div class="col-6">
                            <div class="border rounded p-2 border-dashed">
                                <div class="text-success fw-bold"><?php echo $data['products']['active']; ?></div>
                                <div class="text-xs text-muted">Đang bán</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2 border-danger bg-danger bg-opacity-10">
                                <div class="text-danger fw-bold"><?php echo $data['products']['low_stock']; ?></div>
                                <div class="text-xs text-danger">Sắp hết</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-5">
        <div class="col-lg-8 fade-in-up" style="animation-delay: 0.5s;">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Biểu đồ doanh thu</h5>
                    <a href="<?php echo BASE_URL; ?>/admin/analytics/revenue" class="btn btn-sm btn-outline-primary rounded-pill">
                        Chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 350px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 fade-in-up" style="animation-delay: 0.6s;">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">Trạng thái đơn hàng</h5>
                </div>
                 <div class="card-body px-4 pb-4 d-flex flex-column justify-content-center">
                    <div style="height: 250px; position: relative;">
                         <canvas id="ordersChart"></canvas>
                         <div class="position-absolute top-50 start-50 translate-middle text-center pointer-events-none">
                             <div class="text-muted text-xs text-uppercase">Tổng đơn</div>
                             <div class="fw-bold h4 mb-0"><?php echo $data['orders']['total']; ?></div>
                         </div>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                             <span class="text-sm"><i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i>Chờ xử lý</span>
                             <span class="fw-bold"><?php echo $data['orders']['pending']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                             <span class="text-sm"><i class="fas fa-circle text-success me-2" style="font-size: 8px;"></i>Hoàn thành</span>
                             <span class="fw-bold"><?php echo $data['orders']['completed']; ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                             <span class="text-sm"><i class="fas fa-circle text-danger me-2" style="font-size: 8px;"></i>Đã hủy</span>
                             <span class="fw-bold"><?php echo $data['orders']['cancelled']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card border-0 shadow-sm fade-in-up" style="border-radius: 20px; animation-delay: 0.7s;">
        <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h5 class="fw-bold text-dark mb-1">Xuất báo cáo</h5>
                <p class="text-muted text-sm mb-0">Tải xuống dữ liệu phân tích để lưu trữ hoặc in ấn.</p>
            </div>
            <div class="d-flex gap-2">
                 <a href="<?php echo BASE_URL; ?>/admin/analytics/exportExcel?type=summary" class="btn btn-outline-success rounded-pill px-4">
                    <i class="fas fa-file-excel me-2"></i>Excel
                </a>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Config Defaults
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    
    // Gradient Generator
    function createGradient(ctx, colorStart, colorEnd) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueGradient = createGradient(revenueCtx, 'rgba(59, 130, 246, 0.2)', 'rgba(59, 130, 246, 0)');
    
    // Mock Data for Chart (Ideally this should come from PHP)
    // Real Data from PHP
    const monthlyData = <?php echo json_encode($data['monthlyRevenue']); ?>;
    const labels = monthlyData.map(item => {
        // Format YYYY-MM to Month/Year or just Month
        const [year, month] = item.month.split('-');
        return `Tháng ${month}`;
    });
    const dataPoints = monthlyData.map(item => item.revenue);

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: dataPoints,
                borderColor: '#c9a961',
                backgroundColor: revenueGradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#c9a961',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13 },
                    bodyFont: { size: 13 },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    grid: { borderDash: [5, 5], color: '#f1f5f9' },
                    ticks: {
                        callback: function(value) {
                            return (value / 1000000) + 'M';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Orders Chart (Doughnut)
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'doughnut',
        data: {
            labels: ['Chờ xử lý', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                data: [
                    <?php echo $data['orders']['pending']; ?>, 
                    <?php echo $data['orders']['completed']; ?>, 
                    <?php echo $data['orders']['cancelled']; ?>
                ],
                backgroundColor: [
                    '#fbbf24', // Warning
                    '#22c55e', // Success
                    '#ef4444'  // Danger
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

<style>
    .shadow-hover {
        transition: all 0.3s ease;
    }
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .text-xs {
        font-size: 0.75rem;
    }
    .transition-300 {
        transition: all 0.3s ease;
    }
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
