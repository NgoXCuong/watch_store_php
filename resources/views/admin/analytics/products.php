<div class="container-fluid p-0">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 fade-in-up">
        <div>
            <h2 class="text-uppercase fw-bold text-dark mb-1" style="font-family: 'Playfair Display', serif;">
                Báo cáo sản phẩm
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin/analytics" class="text-decoration-none text-muted">Analytics</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
                </ol>
            </nav>
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
            <a href="<?php echo BASE_URL; ?>/admin/analytics/products?period=<?php echo $val; ?>" 
               class="btn btn-sm rounded-pill px-3 <?php echo ($data['period'] == $val) ? 'btn-dark' : 'btn-light text-muted bg-transparent border-0'; ?> fw-semibold transition-300">
                <?php echo $label; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Top Selling Products -->
        <div class="col-lg-8 fade-in-up" style="animation-delay: 0.1s;">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <h5 class="fw-bold text-dark mb-0">Top sản phẩm bán chạy</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 text-secondary text-xs text-uppercase fw-bold border-0">Sản phẩm</th>
                                <th class="text-secondary text-xs text-uppercase fw-bold border-0 text-center">Đã bán</th>
                                <th class="pe-4 text-secondary text-xs text-uppercase fw-bold border-0 text-end">Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['topProducts'])): ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted">Chưa có dữ liệu</td></tr>
                            <?php else: ?>
                                <?php foreach($data['topProducts'] as $index => $product): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 position-relative">
                                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                                     alt="" class="rounded-3 shadow-sm object-fit-cover" width="48" height="48">
                                                <?php if($index < 3): ?>
                                                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-warning text-dark border border-white">
                                                    #<?php echo $index + 1; ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-dark text-truncate" style="max-width: 250px;">
                                                    <?php echo htmlspecialchars($product['name']); ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 px-3">
                                            <?php echo $product['total_quantity'] ?? $product['total_sold'] ?? 0; ?>
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end fw-bold text-dark">
                                        <?php echo number_format($product['total_revenue'], 0, ',', '.'); ?> ₫
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Category Chart -->
        <div class="col-lg-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h5 class="fw-bold text-dark mb-0">Phân bổ danh mục</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-center p-4">
                    <div style="height: 250px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="mt-4">
                        <?php foreach(array_slice($data['productCategories'], 0, 5) as $index => $cat): ?>
                         <div class="d-flex justify-content-between align-items-center mb-2">
                             <div class="d-flex align-items-center">
                                 <span class="d-inline-block rounded-circle me-2" style="width: 10px; height: 10px; background-color: <?php echo ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'][$index % 5]; ?>"></span>
                                 <span class="text-sm text-dark"><?php echo $cat['name']; ?></span>
                             </div>
                             <span class="text-muted text-sm fw-medium"><?php echo $cat['product_count']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="row fade-in-up" style="animation-delay: 0.3s;">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                 <div class="card-header bg-danger bg-opacity-10 py-3 px-4 border-bottom border-danger border-opacity-10">
                    <div class="d-flex align-items-center text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <h5 class="fw-bold mb-0">Cảnh báo tồn kho thấp</h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                         <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 text-uppercase text-xs text-muted border-0">Sản phẩm</th>
                                    <th class="text-uppercase text-xs text-muted border-0">Tồn kho hiện tại</th>
                                    <th class="pe-4 text-uppercase text-xs text-muted border-0 text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['lowStockProducts'])): ?>
                                    <tr><td colspan="3" class="text-center py-4 text-success fw-medium">Tất cả sản phẩm đều đủ tồn kho</td></tr>
                                <?php else: ?>
                                    <?php foreach($data['lowStockProducts'] as $product): ?>
                                    <tr>
                                        <td class="ps-4 fw-medium text-dark"><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td>
                                            <span class="badge bg-danger text-white px-3">
                                                Chỉ còn <?php echo $product['stock']; ?>
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-dark rounded-pill">
                                                Nhập hàng
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    const ctx = document.getElementById('categoryChart').getContext('2d');
    
    // Data Preparation
    const categories = <?php echo json_encode(array_column($data['productCategories'], 'name')); ?>;
    const counts = <?php echo json_encode(array_column($data['productCategories'], 'product_count')); ?>;
    const colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1'];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categories,
            datasets: [{
                data: counts,
                backgroundColor: colors,
                borderWidth: 0,
                hoverOffset: 15 // Enhance hover effect
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            },
            layout: {
                padding: 10
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
