<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-file-invoice me-2"></i>Chi tiết đơn hàng #<?php echo $order['id']; ?></h1>
            <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>

        <div class="row">
            <!-- Order Information -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mã đơn hàng:</strong> #<?php echo $order['id']; ?></p>
                                <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i:s', strtotime($order['created_at'])); ?></p>
                                <p><strong>Cập nhật:</strong> <?php echo date('d/m/Y H:i:s', strtotime($order['updated_at'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
                                <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['phone_number']); ?></p>
                                <?php if ($order['user_name']): ?>
                                    <p><strong>Tài khoản:</strong> <?php echo htmlspecialchars($order['user_name']); ?> (<?php echo htmlspecialchars($order['user_email']); ?>)</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Địa chỉ giao hàng:</strong><br>
                                <?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Ghi chú:</strong><br>
                                <?php echo $order['note'] ? nl2br(htmlspecialchars($order['note'])) : '<em class="text-muted">Không có ghi chú</em>'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Chi tiết sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderDetails as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($item['image_url']): ?>
                                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                                             alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                                             class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                                            <td class="text-end"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                            <td class="text-end"><strong><?php echo number_format($item['subtotal'], 0, ',', '.'); ?>đ</strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tổng tiền hàng:</strong></td>
                                        <td class="text-end"><strong><?php echo number_format($order['total_amount'] - $order['shipping_fee'] + $order['discount_amount'], 0, ',', '.'); ?>đ</strong></td>
                                    </tr>
                                    <?php if ($order['discount_amount'] > 0): ?>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Giảm giá:</strong></td>
                                            <td class="text-end text-success"><strong>-<?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ</strong></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                        <td class="text-end"><strong><?php echo number_format($order['shipping_fee'], 0, ',', '.'); ?>đ</strong></td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-end"><strong class="text-primary">Tổng cộng:</strong></td>
                                        <td class="text-end"><strong class="text-primary"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status & Actions -->
            <div class="col-md-4">
                <!-- Order Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Trạng thái đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Trạng thái hiện tại:</strong>
                            <?php
                            $statusClasses = [
                                'pending' => 'warning',
                                'confirmed' => 'info',
                                'shipping' => 'primary',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                'returned' => 'secondary'
                            ];
                            $statusLabels = [
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Đã hủy',
                                'returned' => 'Trả hàng'
                            ];
                            $statusClass = $statusClasses[$order['status']] ?? 'secondary';
                            $statusLabel = $statusLabels[$order['status']] ?? $order['status'];
                            ?>
                            <span class="badge bg-<?php echo $statusClass; ?> float-end"><?php echo $statusLabel; ?></span>
                        </div>

                        <form action="<?php echo BASE_URL; ?>/admin/orders/updateStatus/<?php echo $order['id']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="status" class="form-label">Cập nhật trạng thái:</label>
                                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                                    <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>Đã xác nhận</option>
                                    <option value="shipping" <?php echo $order['status'] === 'shipping' ? 'selected' : ''; ?>>Đang giao</option>
                                    <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Đã giao</option>
                                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                                    <option value="returned" <?php echo $order['status'] === 'returned' ? 'selected' : ''; ?>>Trả hàng</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Trạng thái thanh toán</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Trạng thái hiện tại:</strong>
                            <?php
                            $paymentClasses = [
                                'unpaid' => 'danger',
                                'paid' => 'success',
                                'refunded' => 'warning'
                            ];
                            $paymentLabels = [
                                'unpaid' => 'Chưa thanh toán',
                                'paid' => 'Đã thanh toán',
                                'refunded' => 'Hoàn tiền'
                            ];
                            $paymentClass = $paymentClasses[$order['payment_status']] ?? 'secondary';
                            $paymentLabel = $paymentLabels[$order['payment_status']] ?? $order['payment_status'];
                            ?>
                            <span class="badge bg-<?php echo $paymentClass; ?> float-end"><?php echo $paymentLabel; ?></span>
                        </div>

                        <div class="mb-3">
                            <strong>Phương thức thanh toán:</strong>
                            <span class="float-end">
                                <?php
                                $paymentMethodLabels = [
                                    'cod' => 'Thanh toán khi nhận hàng',
                                    'vnpay' => 'VNPay',
                                    'momo' => 'Momo',
                                    'banking' => 'Chuyển khoản ngân hàng'
                                ];
                                echo $paymentMethodLabels[$order['payment_method']] ?? $order['payment_method'];
                                ?>
                            </span>
                        </div>

                        <form action="<?php echo BASE_URL; ?>/admin/orders/updatePaymentStatus/<?php echo $order['id']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Cập nhật trạng thái thanh toán:</label>
                                <select name="payment_status" id="payment_status" class="form-select" onchange="this.form.submit()">
                                    <option value="unpaid" <?php echo $order['payment_status'] === 'unpaid' ? 'selected' : ''; ?>>Chưa thanh toán</option>
                                    <option value="paid" <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Đã thanh toán</option>
                                    <option value="refunded" <?php echo $order['payment_status'] === 'refunded' ? 'selected' : ''; ?>>Hoàn tiền</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Voucher Info -->
                <?php if ($order['voucher_id']): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Mã giảm giá</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Mã voucher:</strong> <?php echo htmlspecialchars($order['voucher_id']); ?></p>
                            <p><strong>Giảm giá:</strong> <?php echo number_format($order['discount_amount'], 0, ',', '.'); ?>đ</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
