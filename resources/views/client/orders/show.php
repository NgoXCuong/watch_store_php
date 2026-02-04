<div class="container py-4">
    <div class="mb-4">
        <a href="<?php echo BASE_URL; ?>/orders" class="text-decoration-none text-muted">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách đơn hàng
        </a>
    </div>

    <div class="row">
        <!-- Main Order Content -->
        <div class="col-lg-8">
            <!-- Order Status & Progress -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Đơn hàng #<?php echo $data['order']['id']; ?></h5>
                        <?php
                        $statusClass = '';
                        $statusText = '';
                        switch ($data['order']['status']) {
                            case 'pending': $statusClass = 'bg-warning'; $statusText = 'Chờ xử lý'; break;
                            case 'confirmed': $statusClass = 'bg-primary'; $statusText = 'Đã xác nhận'; break;
                            case 'shipping': $statusClass = 'bg-info'; $statusText = 'Đang giao hàng'; break;
                            case 'delivered': $statusClass = 'bg-success'; $statusText = 'Giao thành công'; break;
                            case 'cancelled': $statusClass = 'bg-danger'; $statusText = 'Đã hủy'; break;
                            case 'returned': $statusClass = 'bg-danger'; $statusText = 'Hoàn trả'; break;
                            default: $statusClass = 'bg-secondary'; $statusText = $data['order']['status'];
                        }
                        ?>
                        <span class="badge <?php echo $statusClass; ?> fs-6 px-3 py-2 rounded-pill">
                            <?php echo $statusText; ?>
                        </span>
                    </div>

                    <!-- Simple Progress Steps handled with CSS classes logic based on status priority -->
                    <?php if (!in_array($data['order']['status'], ['cancelled', 'returned'])): ?>
                    <div class="position-relative m-4">
                        <div class="progress" style="height: 2px;">
                            <?php
                                $progress = 0;
                                if ($data['order']['status'] == 'pending') $progress = 25;
                                if ($data['order']['status'] == 'confirmed') $progress = 50;
                                if ($data['order']['status'] == 'shipping') $progress = 75;
                                if ($data['order']['status'] == 'delivered') $progress = 100;
                            ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress; ?>%;"></div>
                        </div>
                        <div class="position-absolute w-100 top-0 start-0 translate-middle-y d-flex justify-content-between">
                            <div class="text-center bg-white px-2">
                                <i class="fas fa-file-invoice fs-5 text-success"></i>
                                <div class="small mt-1">Đã đặt</div>
                            </div>
                            <div class="text-center bg-white px-2">
                                <i class="fas fa-check-circle fs-5 <?php echo $progress >= 50 ? 'text-success' : 'text-muted'; ?>"></i>
                                <div class="small mt-1">Xác nhận</div>
                            </div>
                            <div class="text-center bg-white px-2">
                                <i class="fas fa-truck fs-5 <?php echo $progress >= 75 ? 'text-success' : 'text-muted'; ?>"></i>
                                <div class="small mt-1">Vận chuyển</div>
                            </div>
                            <div class="text-center bg-white px-2">
                                <i class="fas fa-box-open fs-5 <?php echo $progress >= 100 ? 'text-success' : 'text-muted'; ?>"></i>
                                <div class="small mt-1">Giao hàng</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="text-muted small mt-4">
                        Ngày đặt hàng: <?php echo date('H:i d/m/Y', strtotime($data['order']['created_at'])); ?>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Sản phẩm đã mua</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['orderDetails'] as $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image_url'])): ?>
                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                                         class="rounded me-3 border" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center border" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $item['product_id']; ?>" 
                                                       class="text-decoration-none text-dark fw-bold">
                                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                                    </a>
                                                    <?php if ($data['order']['status'] === 'delivered' || $data['order']['payment_status'] === 'paid'): ?>
                                                        <a href="<?php echo BASE_URL; ?>/reviews/create/<?php echo $item['product_id']; ?>" 
                                                           class="btn btn-sm btn-outline-warning mt-2">
                                                            <i class="fas fa-star me-1"></i>Viết đánh giá
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">x<?php echo $item['quantity']; ?></td>
                                        <td class="text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                        <td class="text-end pe-4 fw-bold">
                                            <?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?>đ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Info -->
        <div class="col-lg-4">
            <!-- Summary -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tổng quan đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span>
                            <?php 
                            $subtotal = 0;
                            foreach ($data['orderDetails'] as $item) {
                                $subtotal += $item['quantity'] * $item['price'];
                            }
                            echo number_format($subtotal, 0, ',', '.'); 
                            ?>đ
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span><?php echo number_format($data['order']['shipping_fee'], 0, ',', '.'); ?>đ</span>
                    </div>
                    <?php if ($data['order']['discount_amount'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Giảm giá:</span>
                        <span>-<?php echo number_format($data['order']['discount_amount'], 0, ',', '.'); ?>đ</span>
                    </div>
                    <?php endif; ?>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-5">Tổng cộng:</span>
                        <span class="fw-bold fs-5 text-primary"><?php echo number_format($data['order']['total_amount'], 0, ',', '.'); ?>đ</span>
                    </div>

                    <?php if (in_array($data['order']['status'], ['pending', 'confirmed'])): ?>
                        <form action="<?php echo BASE_URL; ?>/orders/cancel/<?php echo $data['order']['id']; ?>" 
                              method="POST" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Hành động này không thể hoàn tác.');">
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-times me-2"></i>Hủy đơn hàng
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($data['order']['status'] === 'delivered'): ?>
                        <a href="<?php echo BASE_URL; ?>/products" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-redo me-2"></i>Mua lại
                        </a>
                        <a href="<?php echo BASE_URL; ?>/reviews/myReviews" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-list me-2"></i>Xem đánh giá của tôi
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($data['order']['full_name']); ?></h6>
                    <p class="text-muted mb-3"><?php echo htmlspecialchars($data['order']['phone_number']); ?></p>
                    <p class="mb-0">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        <?php echo htmlspecialchars($data['order']['shipping_address']); ?>
                    </p>
                    
                    <hr class="my-3">
                    
                    <h6 class="fw-bold mb-2">Ghi chú:</h6>
                    <p class="text-muted fst-italic mb-0">
                        <?php echo !empty($data['order']['note']) ? nl2br(htmlspecialchars($data['order']['note'])) : 'Không có ghi chú'; ?>
                    </p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phương thức:</span>
                        <span class="fw-bold text-uppercase"><?php echo htmlspecialchars($data['order']['payment_method']); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Trạng thái:</span>
                        <?php if ($data['order']['payment_status'] === 'paid'): ?>
                            <span class="badge bg-success rounded-pill">Đã thanh toán</span>
                        <?php elseif ($data['order']['payment_status'] === 'refunded'): ?>
                            <span class="badge bg-info rounded-pill">Đã hoàn tiền</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark rounded-pill">Chưa thanh toán</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
