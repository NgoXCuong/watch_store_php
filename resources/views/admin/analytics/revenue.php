<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
        <div>
            <h2 class="text-uppercase fw-bold text-dark mb-1" style="font-family: 'Playfair Display', serif;">
                Báo cáo doanh thu
            </h2>
             <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin/analytics" class="text-decoration-none text-muted">Analytics</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Doanh thu</li>
                </ol>
            </nav>
        </div>
        
        <!-- Date Filter Form -->
        <form action="" method="GET" class="d-flex gap-2 bg-white p-2 rounded-pill shadow-sm border">
            <input type="date" name="start_date" value="<?php echo $data['startDate']; ?>" 
                   class="form-control border-0 bg-transparent rounded-pill px-3 shadow-none" style="max-width: 150px;">
            <span class="align-self-center text-muted">-</span>
            <input type="date" name="end_date" value="<?php echo $data['endDate']; ?>" 
                   class="form-control border-0 bg-transparent rounded-pill px-3 shadow-none" style="max-width: 150px;">
            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-semibold">
                <i class="fas fa-filter me-1"></i> Lọc
            </button>
        </form>
    </div>

    <!-- Summary Card -->
    <div class="row mb-4 fade-in-up" style="animation-delay: 0.1s;">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(to right, #1e293b, #0f172a); color: white; border-radius: 15px;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-white-50 text-uppercase text-xs fw-bold tracking-wider mb-1">Tổng doanh thu trong kỳ</div>
                        <h2 class="display-5 fw-bold mb-0"><?php echo number_format($data['revenueData'], 0, ',', '.'); ?> ₫</h2>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-circle p-4">
                        <i class="fas fa-coins fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="card border-0 shadow-sm mb-4 fade-in-up" style="border-radius: 20px; animation-delay: 0.2s;">
        <div class="card-body p-4">
            <h5 class="card-title fw-bold text-dark mb-4">Biểu đồ xu hướng</h5>
            <div style="height: 400px;">
                <canvas id="revenueDetailChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card border-0 shadow-sm fade-in-up" style="border-radius: 20px; animation-delay: 0.3s;">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">Chi tiết theo ngày</h5>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle rounded-pill" type="button" id="exportMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-1"></i> Xuất dữ liệu
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow" aria-labelledby="exportMenu">
                    <li>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/analytics/exportExcel?type=revenue&start_date=<?php echo $data['startDate']; ?>&end_date=<?php echo $data['endDate']; ?>">
                            <i class="fas fa-file-excel text-success me-2"></i> Xuất Excel
                        </a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary text-xs text-uppercase fw-bold border-0">Ngày</th>
                        <th class="py-3 text-secondary text-xs text-uppercase fw-bold border-0 text-end">Số đơn hàng</th>
                        <th class="pe-4 py-3 text-secondary text-xs text-uppercase fw-bold border-0 text-end">Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['dailyRevenue'])): ?>
                        <tr><td colspan="3" class="text-center py-4 text-muted">Không có dữ liệu trong khoảng thời gian này</td></tr>
                    <?php else: ?>
                        <?php foreach($data['dailyRevenue'] as $day): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-dark"><?php echo date('d/m/Y', strtotime($day['date'])); ?></td>
                            <td class="text-end">
                                <span class="badge bg-light text-dark border"><?php echo $day['orders']; ?></span>
                            </td>
                            <td class="pe-4 text-end fw-bold text-primary">
                                <?php echo number_format($day['revenue'], 0, ',', '.'); ?> ₫
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    const ctx = document.getElementById('revenueDetailChart').getContext('2d');
    
    // Prepare Data
    const rawData = <?php echo json_encode($data['dailyRevenue']); ?>;
    const labels = rawData.map(item => {
        const d = new Date(item.date);
        return `${d.getDate()}/${d.getMonth()+1}`;
    });
    const revenues = rawData.map(item => item.revenue);
    const orders = rawData.map(item => item.orders);

    // Gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Doanh thu',
                    data: revenues,
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Đơn hàng',
                    data: orders,
                    borderColor: '#fbbf24',
                    backgroundColor: 'rgba(251, 191, 36, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.3,
                    type: 'line',
                    yAxisID: 'y1'
                }
            ]
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
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                if (context.datasetIndex === 0) {
                                     label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                                } else {
                                     label += context.parsed.y;
                                }
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: { borderDash: [5, 5], color: '#f1f5f9' },
                    ticks: {
                        callback: function(value) {
                            return (value / 1000000) + 'M';
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: { display: false },
                    min: 0
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>

<style>
    .text-xs { font-size: 0.75rem; }
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
