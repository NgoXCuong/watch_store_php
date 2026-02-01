<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="display-5 fw-bold text-success mb-3">Đặt hàng thành công!</h1>
                <p class="lead text-muted mb-4">
                    Cảm ơn bạn đã tin tưởng và mua sắm tại Watch Store.
                    Đơn hàng của bạn đã được ghi nhận và đang được xử lý.
                </p>
                <div class="alert alert-success d-inline-block">
                    <i class="fas fa-info-circle me-2"></i>
                    Mã đơn hàng: <strong>#<?php echo $data['order']['id']; ?></strong>
                </div>
            </div>

            <!-- Order Details -->
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-receipt me-2 "></i>Chi tiết đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Order Info -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Thông tin đơn hàng</h6>
                            <div class="mb-2">
                                <strong>Mã đơn hàng:</strong> #<?php echo $data['order']['id']; ?>
                            </div>
                            <div class="mb-2">
                                <strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($data['order']['created_at'])); ?>
                            </div>
                            <div class="mb-2">
                                <strong>Trạng thái:</strong>
                                <span class="badge bg-warning">Đang xử lý</span>
                            </div>
                            <div class="mb-2">
                                <strong>Phương thức thanh toán:</strong>
                                <?php
                                $paymentMethod = '';
                                switch ($data['order']['payment_method']) {
                                    case 'cod':
                                        $paymentMethod = 'Thanh toán khi nhận hàng (COD)';
                                        break;
                                    case 'bank_transfer':
                                        $paymentMethod = 'Chuyển khoản ngân hàng';
                                        break;
                                    case 'momo':
                                        $paymentMethod = 'Ví MoMo';
                                        break;
                                    default:
                                        $paymentMethod = 'Chưa xác định';
                                }
                                echo $paymentMethod;
                                ?>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Thông tin giao hàng</h6>
                            <div class="mb-2">
                                <strong>Người nhận:</strong> <?php echo htmlspecialchars($data['order']['full_name']); ?>
                            </div>
                            <div class="mb-2">
                                <strong>Số điện thoại:</strong> <?php echo htmlspecialchars($data['order']['phone_number']); ?>
                            </div>
                            <div class="mb-2">
                                <strong>Địa chỉ giao hàng:</strong><br>
                                <?php echo htmlspecialchars($data['order']['shipping_address']); ?>
                            </div>
                            <?php if ($data['order']['note']): ?>
                                <div class="mb-2">
                                    <strong>Ghi chú:</strong> <?php echo htmlspecialchars($data['order']['note']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shopping-bag me-2"></i>Sản phẩm đã đặt
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get order details
                                $orderModel = new \App\Models\OrderModel();
                                $orderDetails = $orderModel->getOrderDetails($data['order']['id']);

                                foreach ($orderDetails as $item):
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <?php if ($item['image_url']): ?>
                                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                                             class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?php echo $item['quantity']; ?></td>
                                        <td class="text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                        <td class="text-end fw-bold"><?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?>đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Summary -->
                    <div class="row mt-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span><?php echo number_format($data['order']['total_amount'] + $data['order']['discount_amount'], 0, ',', '.'); ?>đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span class="text-success">Miễn phí</span>
                                </div>
                                <?php if ($data['order']['discount_amount'] > 0): ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Giảm giá:</span>
                                        <span class="text-success">-<?php echo number_format($data['order']['discount_amount'], 0, ',', '.'); ?>đ</span>
                                    </div>
                                <?php endif; ?>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <strong>Tổng cộng:</strong>
                                    <strong class="text-primary"><?php echo number_format($data['order']['total_amount'], 0, ',', '.'); ?>đ</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Các bước tiếp theo
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mb-2">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                            <h6>Xử lý đơn hàng</h6>
                            <p class="text-muted small">Chúng tôi sẽ xử lý đơn hàng trong vòng 24h</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mb-2">
                                <i class="fas fa-truck fa-2x text-primary"></i>
                            </div>
                            <h6>Giao hàng</h6>
                            <p class="text-muted small">Đơn hàng sẽ được giao trong 2-3 ngày</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mb-2">
                                <i class="fas fa-box-open fa-2x text-success"></i>
                            </div>
                            <h6>Nhận hàng</h6>
                            <p class="text-muted small">Kiểm tra và xác nhận nhận hàng</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="text-center">
                <div class="btn-group" role="group">
                    <a href="<?php echo BASE_URL; ?>/orders/show/<?php echo $data['order']['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>Theo dõi đơn hàng
                    </a>
                    <a href="<?php echo BASE_URL; ?>/orders" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Xem tất cả đơn hàng
                    </a>
                    <a href="<?php echo BASE_URL; ?>/" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Về trang chủ
                    </a>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="text-center mt-4">
                <p class="text-muted mb-2">
                    <i class="fas fa-headset me-1"></i>
                    Cần hỗ trợ? Liên hệ với chúng tôi:
                </p>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <i class="fas fa-phone me-1"></i> (028) 1234 5678
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-envelope me-1"></i> support@watchstore.com
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: checkmark 0.8s ease-in-out;
}

@keyframes checkmark {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.step-icon {
    opacity: 0.7;
}
</style>
