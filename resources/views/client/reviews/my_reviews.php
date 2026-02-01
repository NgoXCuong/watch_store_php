<div class="container py-5" style="max-width: 1400px;">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="display-6 fw-bold mb-0 text-uppercase" style="font-family: var(--font-heading);">Đánh giá của tôi</h1>
            <div class="mt-3" style="width: 50px; height: 3px; background-color: var(--secondary-color);"></div>
        </div>
        <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-outline-dark rounded-0 px-4 text-uppercase letter-spacing-1 small fw-bold">
            <i class="fas fa-arrow-left me-2"></i>Trở về hồ sơ
        </a>
    </div>

    <?php if (empty($data['reviews'])): ?>
        <div class="text-center py-5 bg-light">
            <i class="fas fa-star fa-3x text-muted mb-4 opacity-50"></i>
            <h3 class="fw-light text-uppercase letter-spacing-2 mb-3">Chưa có đánh giá nào</h3>
            <p class="text-muted mb-5">Hãy chia sẻ trải nghiệm của bạn về sản phẩm để giúp đỡ cộng đồng.</p>
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-dark rounded-0 px-5 py-3 text-uppercase letter-spacing-2 fw-bold">
                Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($data['reviews'] as $review): ?>
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow-sm rounded-0">
                        <div class="card-body p-4">
                            <div class="d-flex gap-4">
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $review['product_id']; ?>" class="flex-shrink-0">
                                    <?php if (isset($review['product_image']) && $review['product_image']): ?>
                                        <img src="<?php echo htmlspecialchars($review['product_image']); ?>"
                                             alt="<?php echo htmlspecialchars($review['product_name']); ?>"
                                             class="object-fit-cover" style="width: 100px; height: 100px;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                             style="width: 100px; height: 100px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0 text-uppercase letter-spacing-1">
                                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $review['product_id']; ?>" class="text-decoration-none text-dark fw-bold">
                                                <?php echo htmlspecialchars($review['product_name']); ?>
                                            </a>
                                        </h6>
                                        <?php if ($review['is_approved']): ?>
                                            <span class="badge bg-success rounded-0 text-uppercase x-small letter-spacing-1">Đã duyệt</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark rounded-0 text-uppercase x-small letter-spacing-1">Chờ duyệt</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="text-warning small mb-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $review['rating']): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <span class="text-muted ms-2 small fw-normal"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></span>
                                        </div>
                                    </div>

                                    <div class="bg-light p-3 position-relative">
                                        <i class="fas fa-quote-left text-muted opacity-25 position-absolute top-0 start-0 m-2"></i>
                                        <p class="card-text small text-muted mb-0 fst-italic ps-3"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Statistics -->
        <div class="card mt-5 border-0 shadow-sm rounded-0 bg-dark text-white">
            <div class="card-body p-4 p-lg-5">
                <h5 class="text-uppercase letter-spacing-2 text-white mb-4 text-center">Thống kê đánh giá của bạn</h5>
                <div class="row text-center g-4">
                    <div class="col-md-3 border-end border-secondary">
                        <div class="display-4 fw-bold mb-1"><?php echo count($data['reviews']); ?></div>
                        <div class="text-white-50 text-uppercase x-small letter-spacing-1">Tổng đánh giá</div>
                    </div>
                    <div class="col-md-3 border-end border-secondary">
                        <div class="display-4 fw-bold mb-1 text-success">
                            <?php
                            $approved = array_filter($data['reviews'], function($r) { return $r['is_approved']; });
                            echo count($approved);
                            ?>
                        </div>
                        <div class="text-white-50 text-uppercase x-small letter-spacing-1">Đã duyệt</div>
                    </div>
                    <div class="col-md-3 border-end border-secondary">
                        <div class="display-4 fw-bold mb-1 text-warning">
                            <?php
                            $pending = array_filter($data['reviews'], function($r) { return !$r['is_approved']; });
                            echo count($pending);
                            ?>
                        </div>
                        <div class="text-white-50 text-uppercase x-small letter-spacing-1">Chờ duyệt</div>
                    </div>
                    <div class="col-md-3">
                        <div class="display-4 fw-bold mb-1 text-primary">
                            <?php
                            $ratings = array_column($data['reviews'], 'rating');
                            echo $ratings ? round(array_sum($ratings) / count($ratings), 1) : 0;
                            ?>
                        </div>
                        <div class="text-white-50 text-uppercase x-small letter-spacing-1">Điểm trung bình</div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
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
