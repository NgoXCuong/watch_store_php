<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Lịch sử đơn hàng</h2>
        <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại trang cá nhân
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="card-title">Tổng đơn hàng</h6>
                    <h2 class="mb-0"><?php echo $data['total']; ?></h2>
                </div>
            </div>
        </div>
        <!-- Add more stats if available or static design -->
    </div>

    <!-- Filter Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?php echo $data['status'] === '' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/orders">
                        Tất cả
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $data['status'] === 'pending' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/orders?status=pending">
                        Chờ xử lý
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $data['status'] === 'confirmed' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/orders?status=confirmed">
                        Đã xác nhận
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $data['status'] === 'shipping' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/orders?status=shipping">
                        Đang giao
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $data['status'] === 'delivered' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/orders?status=delivered">
                        Đã giao
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $data['status'] === 'cancelled' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/orders?status=cancelled">
                        Đã hủy
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Orders List -->
    <?php if (!empty($data['orders'])): ?>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['orders'] as $order): ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold">#<?php echo $order['id']; ?></span>
                                    </td>
                                    <td>
                                        <?php echo date('H:i d/m/Y', strtotime($order['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;">
                                            <!-- Note: Order model might need to join to get product names or we show basic info -->
                                            <!-- Assuming current query doesn't return product names directly, we'll keep it simple -->
                                            Đơn hàng <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ
                                        </div>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        switch ($order['status']) {
                                            case 'pending':
                                                $statusClass = 'bg-warning';
                                                $statusText = 'Chờ xử lý';
                                                break;
                                            case 'confirmed':
                                                $statusClass = 'bg-primary';
                                                $statusText = 'Đã xác nhận';
                                                break;
                                            case 'shipping':
                                                $statusClass = 'bg-info';
                                                $statusText = 'Đang giao hàng';
                                                break;
                                            case 'delivered':
                                                $statusClass = 'bg-success';
                                                $statusText = 'Giao thành công';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'bg-danger';
                                                $statusText = 'Đã hủy';
                                                break;
                                            case 'returned':
                                                $statusClass = 'bg-danger';
                                                $statusText = 'Hoàn trả';
                                                break;
                                            default:
                                                $statusClass = 'bg-secondary';
                                                $statusText = $order['status'];
                                        }
                                        ?>
                                        <span class="badge <?php echo $statusClass; ?> rounded-pill">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo BASE_URL; ?>/orders/show/<?php echo $order['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>  
            
            <!-- Pagination -->
            <?php if ($data['totalPages'] > 1): ?>
                <div class="card-footer bg-white py-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($data['currentPage'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/orders?page=<?php echo $data['currentPage'] - 1; ?>&status=<?php echo $data['status']; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                                <li class="page-item <?php echo $i == $data['currentPage'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/orders?page=<?php echo $i; ?>&status=<?php echo $data['status']; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>/orders?page=<?php echo $data['currentPage'] + 1; ?>&status=<?php echo $data['status']; ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-box-open fa-4x text-muted"></i>
            </div>
            <h4>Chưa có đơn hàng nào</h4>
            <p class="text-muted">Bạn chưa mua sản phẩm nào từ cửa hàng chúng tôi.</p>
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-primary mt-3">
                <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
            </a>
        </div>
    <?php endif; ?>
</div>
