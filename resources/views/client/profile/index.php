<div class="container py-5" style="max-width: 1400px;">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-0 overflow-hidden h-100">
                <div class="card-body text-center p-5 bg-light">
                    <div class="position-relative d-inline-block mb-4">
                        <?php if ($data['user']['avatar_url']): ?>
                            <img src="<?php echo htmlspecialchars($data['user']['avatar_url']); ?>"
                                 alt="Avatar" class="rounded-circle shadow-sm object-fit-cover border border-3 border-white" style="width: 120px; height: 120px;">
                        <?php else: ?>
                            <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm border border-3 border-white"
                                 style="width: 120px; height: 120px; font-size: 2.5rem;">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h5 class="fw-bold mb-1 text-uppercase letter-spacing-1"><?php echo htmlspecialchars($data['user']['full_name']); ?></h5>
                    <p class="text-muted mb-4 small">@<?php echo htmlspecialchars($data['user']['username']); ?></p>

                    <div class="d-grid gap-3">
                        <a href="<?php echo BASE_URL; ?>/profile/edit" class="btn btn-outline-dark rounded-0 text-uppercase letter-spacing-1 small fw-bold py-2">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa hồ sơ
                        </a>
                        <a href="<?php echo BASE_URL; ?>/profile/change-password" class="btn btn-outline-secondary rounded-0 text-uppercase letter-spacing-1 small fw-bold py-2">
                            <i class="fas fa-key me-2"></i>Đổi mật khẩu
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Account Information -->
            <div class="card border-0 shadow-sm rounded-0 mb-5">
                <div class="card-header bg-white border-bottom py-4">
                    <h5 class="mb-0 fw-bold text-uppercase letter-spacing-2" style="font-family: var(--font-heading);">
                        <i class="fas fa-info-circle me-2 text-warning"></i>Thông tin tài khoản
                    </h5>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <div class="row g-4">
                        <div class="col-md-6 border-end-md">
                            <div class="d-flex align-items-center mb-4">
                                <label class="text-muted x-small letter-spacing-1 mb-0 me-3 flex-shrink-0" style="width: 130px;">Họ tên</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($data['user']['full_name']); ?></div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <label class="text-muted x-small letter-spacing-1 mb-0 me-3 flex-shrink-0" style="width: 130px;">Tên đăng nhập</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($data['user']['username']); ?></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <label class="text-muted x-small letter-spacing-1 mb-0 me-3 flex-shrink-0" style="width: 130px;">Email</label>
                                <div class="fw-bold text-break"><?php echo htmlspecialchars($data['user']['email']); ?></div>
                            </div>
                        </div>

                        <div class="col-md-6 ps-md-5">
                            <div class="d-flex align-items-center mb-4">
                                <label class="text-muted x-small letter-spacing-1 mb-0 me-3 flex-shrink-0" style="width: 130px;">Số điện thoại</label>
                                <div class="fw-bold"><?php echo htmlspecialchars($data['user']['phone'] ?: 'Chưa cập nhật'); ?></div>
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                <label class="text-muted x-small letter-spacing-1 mb-0 me-3 flex-shrink-0" style="width: 130px;">Ngày tham gia</label>
                                <div class="fw-bold"><?php echo date('d/m/Y', strtotime($data['user']['created_at'])); ?></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <label class="text-muted x-small letter-spacing-1 mb-0 me-3 flex-shrink-0" style="width: 130px;">Vai trò</label>
                                <span class="badge bg-dark rounded-0 px-3 py-2 text-uppercase letter-spacing-1">
                                    <?php echo $data['user']['role'] === 'admin' ? 'Admin' : ($data['user']['role'] === 'staff' ? 'Nhân viên' : 'Khách hàng'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats & Quick Actions -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient-primary text-white overflow-hidden position-relative group-hover-parent">
                        <div class="position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="fas fa-shopping-bag fa-4x transform-scale-12"></i>
                        </div>
                        <div class="card-body p-4 position-relative z-index-1">
                            <h2 class="display-5 fw-bold mb-0"><?php echo $data['orderStats']['total_orders']; ?></h2>
                            <p class="text-white-50 text-uppercase letter-spacing-1 small mb-4">Đơn hàng</p>
                            <a href="<?php echo BASE_URL; ?>/orders" class="btn btn-light bg-opacity-20 text-white border-0 rounded-pill px-4 btn-hover-light stretched-link small fw-bold">
                                Xem lịch sử <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient-warning text-white overflow-hidden position-relative group-hover-parent">
                        <div class="position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="fas fa-star fa-4x transform-scale-12"></i>
                        </div>
                        <div class="card-body p-4 position-relative z-index-1">
                            <h2 class="display-5 fw-bold mb-0">
                                <?php 
                                    $reviewModel = new \App\Models\ReviewModel();
                                    $reviews = $reviewModel->getByUserId($data['user']['id']);
                                    echo count($reviews);
                                ?>
                            </h2>
                            <p class="text-white-50 text-uppercase letter-spacing-1 small mb-4">Đánh giá</p>
                            <a href="<?php echo BASE_URL; ?>/reviews/my-reviews" class="btn btn-light bg-opacity-20 text-white border-0 rounded-pill px-4 btn-hover-light stretched-link small fw-bold">
                                Quản lý <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-gradient-success text-white overflow-hidden position-relative group-hover-parent">
                        <div class="position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="fas fa-wallet fa-4x transform-scale-12"></i>
                        </div>
                        <div class="card-body p-4 position-relative z-index-1">
                            <h3 class="display-6 fw-bold mb-0 text-truncate" title="<?php echo number_format($data['orderStats']['total_spent'], 0, ',', '.'); ?>đ">
                                <?php echo number_format($data['orderStats']['total_spent'], 0, ',', '.'); ?>đ
                            </h3>
                            <p class="text-white-50 text-uppercase letter-spacing-1 small mb-4">Tổng chi tiêu</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-header bg-white border-bottom py-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-uppercase letter-spacing-2" style="font-family: var(--font-heading);">
                        <i class="fas fa-history me-2 text-warning"></i>Đơn hàng gần đây
                    </h5>
                    <a href="<?php echo BASE_URL; ?>/orders" class="btn btn-link text-dark text-decoration-none text-uppercase letter-spacing-1 small fw-bold">
                        Xem tất cả <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($data['recentOrders'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-uppercase small text-muted letter-spacing-1">
                                    <tr>
                                        <th class="ps-4 py-3 border-0">Mã đơn</th>
                                        <th class="py-3 border-0">Ngày đặt</th>
                                        <th class="py-3 border-0">Tổng tiền</th>
                                        <th class="py-3 border-0">Trạng thái</th>
                                        <th class="text-end pe-4 py-3 border-0"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['recentOrders'] as $order): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold">#<?php echo $order['id']; ?></td>
                                            <td class="text-muted small"><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                            <td class="fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                                            <td>
                                                <?php
                                                    $statusClasses = [
                                                        'pending' => 'bg-warning text-dark',
                                                        'confirmed' => 'bg-primary text-white',
                                                        'shipping' => 'bg-info text-white',
                                                        'delivered' => 'bg-success text-white',
                                                        'cancelled' => 'bg-danger text-white',
                                                        'returned' => 'bg-secondary text-white'
                                                    ];
                                                    $statusLabels = [
                                                        'pending' => 'Chờ xử lý',
                                                        'confirmed' => 'Đã xác nhận',
                                                        'shipping' => 'Đang giao',
                                                        'delivered' => 'Đã giao',
                                                        'cancelled' => 'Đã hủy',
                                                        'returned' => 'Hoàn trả'
                                                    ];
                                                ?>
                                                <span class="badge rounded-0 px-2 py-1 fw-normal text-uppercase x-small letter-spacing-1 <?php echo $statusClasses[$order['status']] ?? 'bg-secondary'; ?>">
                                                    <?php echo $statusLabels[$order['status']] ?? $order['status']; ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="<?php echo BASE_URL; ?>/orders/show/<?php echo $order['id']; ?>" class="btn btn-outline-dark btn-sm rounded-0">
                                                    Chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted">Bạn chưa có đơn hàng nào</p>
                            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-dark rounded-0 px-4 text-uppercase letter-spacing-1 small">Mua sắm ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-end-md {
    border-right: 1px solid #dee2e6;
}
@media (max-width: 768px) {
    .border-end-md {
        border-right: none;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 1.5rem;
    }
}
.transform-scale-12 {
    transform: scale(1.2);
}
.group-hover-parent:hover .transform-scale-12 {
    transform: scale(1.4);
    transition: transform 0.5s ease;
}

/* Custom Gradients */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%) !important;
    background: linear-gradient(135deg, #f6d365 0%, #fda085 100%) !important;
}
.bg-gradient-success {
    background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%) !important;
}
.rounded-4 {
    border-radius: 1rem !important;
}
.bg-opacity-20 {
    background-color: rgba(255, 255, 255, 0.2) !important;
}
.btn-hover-light:hover {
    background-color: rgba(255, 255, 255, 0.3) !important;
}
</style>
