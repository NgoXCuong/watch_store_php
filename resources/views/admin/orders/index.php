<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-shopping-cart me-2"></i>Quản lý đơn hàng</h1>
        </div>

        <!-- Search and Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control"
                               placeholder="Tìm kiếm theo tên, số điện thoại hoặc mã đơn..."
                               value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" <?php echo ($selectedStatus ?? '') === 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                            <option value="confirmed" <?php echo ($selectedStatus ?? '') === 'confirmed' ? 'selected' : ''; ?>>Đã xác nhận</option>
                            <option value="shipping" <?php echo ($selectedStatus ?? '') === 'shipping' ? 'selected' : ''; ?>>Đang giao</option>
                            <option value="delivered" <?php echo ($selectedStatus ?? '') === 'delivered' ? 'selected' : ''; ?>>Đã giao</option>
                            <option value="cancelled" <?php echo ($selectedStatus ?? '') === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
                            <option value="returned" <?php echo ($selectedStatus ?? '') === 'returned' ? 'selected' : ''; ?>>Trả hàng</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-outline-primary flex-fill">
                                <i class="fas fa-search me-2"></i>Tìm kiếm
                            </button>
                            <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                        <p class="text-muted">Các đơn hàng sẽ xuất hiện ở đây khi khách hàng đặt hàng.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="8%">Mã đơn</th>
                                    <th width="20%">Khách hàng</th>
                                    <th width="15%">Số điện thoại</th>
                                    <th width="12%">Tổng tiền</th>
                                    <th width="10%">Trạng thái</th>
                                    <th width="10%">Thanh toán</th>
                                    <th width="15%">Ngày đặt</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>
                                            <strong>#<?php echo $order['id']; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($order['full_name']); ?>
                                            <?php if ($order['user_name']): ?>
                                                <br><small class="text-muted">User: <?php echo htmlspecialchars($order['user_name']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($order['phone_number']); ?></td>
                                        <td>
                                            <strong class="text-primary">
                                                <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ
                                            </strong>
                                        </td>
                                        <td>
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
                                            <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                                        </td>
                                        <td>
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
                                            <span class="badge bg-<?php echo $paymentClass; ?>"><?php echo $paymentLabel; ?></span>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="viewOrderDetails(<?php echo $order['id']; ?>)">
                                                <i class="fas fa-eye"></i> Xem
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($selectedStatus) ? '&status=' . urlencode($selectedStatus) : ''; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                    <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($selectedStatus) ? '&status=' . urlencode($selectedStatus) : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?><?php echo !empty($selectedStatus) ? '&status=' . urlencode($selectedStatus) : ''; ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Chi tiết đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Order details will be loaded here -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <a href="#" id="viewFullDetailsBtn" class="btn btn-primary">Xem chi tiết đầy đủ</a>
            </div>
        </div>
    </div>
</div>

<script>
function viewOrderDetails(orderId) {
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    const content = document.getElementById('orderDetailsContent');
    const viewFullBtn = document.getElementById('viewFullDetailsBtn');

    // Show loading
    content.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Đang tải chi tiết đơn hàng...</p>
        </div>
    `;

    // Update modal title
    document.getElementById('orderDetailsModalLabel').textContent = `Chi tiết đơn hàng #${orderId}`;

    // Update view full details button
    viewFullBtn.href = `<?php echo BASE_URL; ?>/admin/orders/show/${orderId}`;

    // Fetch order details
    fetch(`<?php echo BASE_URL; ?>/admin/orders/getOrderDetails/${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>${data.error}
                    </div>
                `;
                return;
            }

            const order = data.order;
            const orderDetails = data.orderDetails;

            // Build order details HTML
            let html = `
                <div class="row">
                    <!-- Order Information -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Thông tin đơn hàng</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Mã đơn:</strong> #${order.id}</p>
                                <p><strong>Ngày đặt:</strong> ${new Date(order.created_at).toLocaleString('vi-VN')}</p>
                                <p><strong>Khách hàng:</strong> ${order.full_name}</p>
                                <p><strong>Số điện thoại:</strong> ${order.phone_number}</p>
                                <p><strong>Địa chỉ:</strong> ${order.shipping_address}</p>
                                <p><strong>Ghi chú:</strong> ${order.note || 'Không có'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Trạng thái</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Trạng thái đơn hàng:</strong>
                                    <span class="badge bg-${getStatusClass(order.status)} ms-2">${getStatusLabel(order.status)}</span>
                                </p>
                                <p><strong>Trạng thái thanh toán:</strong>
                                    <span class="badge bg-${getPaymentClass(order.payment_status)} ms-2">${getPaymentLabel(order.payment_status)}</span>
                                </p>
                                <p><strong>Phương thức thanh toán:</strong> ${getPaymentMethodLabel(order.payment_method)}</p>
                                <p><strong>Tổng tiền:</strong> <span class="text-primary fw-bold">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.total_amount)}</span></p>
                                ${order.discount_amount > 0 ? `<p><strong>Giảm giá:</strong> <span class="text-success">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(order.discount_amount)}</span></p>` : ''}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Chi tiết sản phẩm</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>`;

            orderDetails.forEach(item => {
                html += `
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                ${item.image_url ? `<img src="${item.image_url}" alt="${item.product_name}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">` : ''}
                                                <div>
                                                    <strong>${item.product_name}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">${item.quantity}</td>
                                        <td class="text-end">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.price)}</td>
                                        <td class="text-end"><strong>${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.subtotal)}</strong></td>
                                    </tr>`;
            });

            html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            content.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra khi tải chi tiết đơn hàng
                </div>
            `;
        });

    modal.show();
}

function getStatusClass(status) {
    const classes = {
        'pending': 'warning',
        'confirmed': 'info',
        'shipping': 'primary',
        'delivered': 'success',
        'cancelled': 'danger',
        'returned': 'secondary'
    };
    return classes[status] || 'secondary';
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Chờ xử lý',
        'confirmed': 'Đã xác nhận',
        'shipping': 'Đang giao',
        'delivered': 'Đã giao',
        'cancelled': 'Đã hủy',
        'returned': 'Trả hàng'
    };
    return labels[status] || status;
}

function getPaymentClass(status) {
    const classes = {
        'unpaid': 'danger',
        'paid': 'success',
        'refunded': 'warning'
    };
    return classes[status] || 'secondary';
}

function getPaymentLabel(status) {
    const labels = {
        'unpaid': 'Chưa thanh toán',
        'paid': 'Đã thanh toán',
        'refunded': 'Hoàn tiền'
    };
    return labels[status] || status;
}

function getPaymentMethodLabel(method) {
    const labels = {
        'cod': 'Thanh toán khi nhận hàng',
        'vnpay': 'VNPay',
        'momo': 'Momo',
        'banking': 'Chuyển khoản ngân hàng'
    };
    return labels[method] || method;
}
</script>
