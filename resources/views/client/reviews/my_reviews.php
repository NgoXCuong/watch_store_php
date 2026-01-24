<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-star me-2"></i>Đánh giá của tôi</h1>
                <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại hồ sơ
                </a>
            </div>

            <?php if (empty($data['reviews'])): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Bạn chưa có đánh giá nào</h5>
                        <p class="text-muted">Hãy mua hàng và đánh giá sản phẩm để giúp người khác có thêm thông tin!</p>
                        <a href="<?php echo BASE_URL; ?>/products" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Mua sắm ngay
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($data['reviews'] as $review): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">
                                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $review['product_id']; ?>" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($review['product_name']); ?>
                                                </a>
                                            </h6>
                                            <div class="rating-stars mb-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                <?php endfor; ?>
                                                <small class="text-muted ms-2">(<?php echo $review['rating']; ?>/5)</small>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?>
                                            </small>
                                        </div>
                                        <div class="ms-3">
                                            <?php if ($review['is_approved']): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <p class="card-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Statistics -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Thống kê đánh giá</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo count($data['reviews']); ?></div>
                                    <div class="stat-label">Tổng đánh giá</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <div class="stat-number">
                                        <?php
                                        $approved = array_filter($data['reviews'], function($r) { return $r['is_approved']; });
                                        echo count($approved);
                                        ?>
                                    </div>
                                    <div class="stat-label">Đã duyệt</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <div class="stat-number">
                                        <?php
                                        $pending = array_filter($data['reviews'], function($r) { return !$r['is_approved']; });
                                        echo count($pending);
                                        ?>
                                    </div>
                                    <div class="stat-label">Chờ duyệt</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-item">
                                    <div class="stat-number">
                                        <?php
                                        $ratings = array_column($data['reviews'], 'rating');
                                        echo $ratings ? round(array_sum($ratings) / count($ratings), 1) : 0;
                                        ?>
                                    </div>
                                    <div class="stat-label">Điểm trung bình</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.rating-stars {
    font-size: 0.9rem;
}

.stat-item {
    padding: 1rem;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}
</style>
