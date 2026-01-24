<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($data['user']['avatar_url']): ?>
                        <img src="<?php echo htmlspecialchars($data['user']['avatar_url']); ?>"
                             alt="Avatar" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>

                    <h5><?php echo htmlspecialchars($data['user']['full_name']); ?></h5>
                    <p class="text-muted mb-2">@<?php echo htmlspecialchars($data['user']['username']); ?></p>
                    <p class="text-muted small"><?php echo htmlspecialchars($data['user']['email']); ?></p>

                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>/profile/edit" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa hồ sơ
                        </a>
                        <a href="<?php echo BASE_URL; ?>/profile/change-password" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-key me-1"></i>Đổi mật khẩu
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Thống kê đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-1 text-primary"><?php echo $data['orderStats']['total_orders']; ?></div>
                            <small class="text-muted">Tổng đơn</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-1 text-success"><?php echo $data['orderStats']['completed_orders']; ?></div>
                            <small class="text-muted">Hoàn thành</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="h4 mb-1 text-warning"><?php echo $data['orderStats']['pending_orders']; ?></div>
                            <small class="text-muted">Đang xử lý</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-1 text-danger"><?php echo $data['orderStats']['cancelled_orders']; ?></div>
                            <small class="text-muted">Đã hủy</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <div class="h5 mb-1 text-info"><?php echo number_format($data['orderStats']['total_spent'], 0, ',', '.'); ?>đ</div>
                        <small class="text-muted">Tổng chi tiêu</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Account Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ tên:</label>
                                <p class="mb-0"><?php echo htmlspecialchars($data['user']['full_name']); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên đăng nhập:</label>
                                <p class="mb-0"><?php echo htmlspecialchars($data['user']['username']); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p class="mb-0"><?php echo htmlspecialchars($data['user']['email']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại:</label>
                                <p class="mb-0"><?php echo htmlspecialchars($data['user']['phone'] ?: 'Chưa cập nhật'); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Vai trò:</label>
                                <p class="mb-0">
                                    <span class="badge bg-<?php echo $data['user']['role'] === 'admin' ? 'danger' : ($data['user']['role'] === 'staff' ? 'warning' : 'secondary'); ?>">
                                        <?php echo $data['user']['role'] === 'admin' ? 'Admin' : ($data['user']['role'] === 'staff' ? 'Nhân viên' : 'Khách hàng'); ?>
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái:</label>
                                <p class="mb-0">
                                    <span class="badge bg-<?php echo $data['user']['status'] === 'active' ? 'success' : ($data['user']['status'] === 'inactive' ? 'warning' : 'danger'); ?>">
                                        <?php echo $data['user']['status'] === 'active' ? 'Hoạt động' : ($data['user']['status'] === 'inactive' ? 'Không hoạt động' : 'Đã khóa'); ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ngày tham gia:</label>
                        <p class="mb-0"><?php echo date('d/m/Y \l\ú\c H:i', strtotime($data['user']['created_at'])); ?></p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-bag fa-2x text-primary mb-3"></i>
                            <h6>Đơn hàng của tôi</h6>
                            <p class="text-muted small">Xem lịch sử đơn hàng</p>
                            <a href="<?php echo BASE_URL; ?>/orders" class="btn btn-primary btn-sm">Xem đơn hàng</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-star fa-2x text-warning mb-3"></i>
                            <h6>Đánh giá của tôi</h6>
                            <p class="text-muted small">Xem đánh giá đã gửi</p>
                            <a href="<?php echo BASE_URL; ?>/reviews/myReviews" class="btn btn-warning btn-sm">Xem đánh giá</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-2x text-success mb-3"></i>
                            <h6>Giỏ hàng</h6>
                            <p class="text-muted small">Xem giỏ hàng hiện tại</p>
                            <a href="<?php echo BASE_URL; ?>/cart" class="btn btn-success btn-sm">Xem giỏ hàng</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-heart fa-2x text-danger mb-3"></i>
                            <h6>Yêu thích</h6>
                            <p class="text-muted small">Sản phẩm yêu thích</p>
                            <a href="<?php echo BASE_URL; ?>/profile/wishlist" class="btn btn-danger btn-sm">Xem yêu thích</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đơn hàng gần đây</h5>
                    <a href="<?php echo BASE_URL; ?>/orders" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['recentOrders'])): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['recentOrders'] as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</td>
                                            <td>
                                                <span class="badge bg-<?php
                                                    echo $order['status'] === 'delivered' ? 'success' :
                                                         ($order['status'] === 'shipping' ? 'info' :
                                                         ($order['status'] === 'confirmed' ? 'primary' :
                                                         ($order['status'] === 'pending' ? 'warning' : 'danger')));
                                                ?>">
                                                    <?php
                                                    echo $order['status'] === 'delivered' ? 'Đã giao' :
                                                         ($order['status'] === 'shipping' ? 'Đang giao' :
                                                         ($order['status'] === 'confirmed' ? 'Đã xác nhận' :
                                                         ($order['status'] === 'pending' ? 'Chờ xử lý' : 'Đã hủy')));
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>/orders/show/<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bạn chưa có đơn hàng nào</p>
                            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-primary">Mua sắm ngay</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đánh giá gần đây</h5>
                    <a href="<?php echo BASE_URL; ?>/reviews/myReviews" class="btn btn-sm btn-outline-warning">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($data['recentReviews'])): ?>
                        <div class="row">
                            <?php foreach ($data['recentReviews'] as $review): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <?php if ($review['product_image']): ?>
                                                    <img src="<?php echo htmlspecialchars($review['product_image']); ?>"
                                                         alt="<?php echo htmlspecialchars($review['product_name']); ?>"
                                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $review['product_id']; ?>" class="text-decoration-none">
                                                            <?php echo htmlspecialchars($review['product_name']); ?>
                                                        </a>
                                                    </h6>
                                                    <div class="mb-2">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                        <?php endfor; ?>
                                                        <small class="text-muted ms-2"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></small>
                                                    </div>
                                                    <p class="text-muted small mb-0"><?php echo htmlspecialchars(substr($review['comment'], 0, 100)); ?><?php echo strlen($review['comment']) > 100 ? '...' : ''; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Bạn chưa có đánh giá nào</p>
                            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-warning">Đánh giá sản phẩm</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Account Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cài đặt tài khoản</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Thông tin cá nhân</h6>
                            <div class="d-grid gap-2">
                                <a href="<?php echo BASE_URL; ?>/profile/edit" class="btn btn-outline-primary">
                                    <i class="fas fa-user-edit me-2"></i>Chỉnh sửa thông tin
                                </a>
                                <a href="<?php echo BASE_URL; ?>/profile/change-password" class="btn btn-outline-secondary">
                                    <i class="fas fa-key me-2"></i>Đổi mật khẩu
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Hoạt động</h6>
                            <div class="d-grid gap-2">
                                <a href="<?php echo BASE_URL; ?>/orders" class="btn btn-outline-info">
                                    <i class="fas fa-history me-2"></i>Lịch sử đơn hàng
                                </a>
                                <a href="<?php echo BASE_URL; ?>/reviews/myReviews" class="btn btn-outline-warning">
                                    <i class="fas fa-comments me-2"></i>Quản lý đánh giá
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
