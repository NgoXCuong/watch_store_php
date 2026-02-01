<div class="container py-5" style="max-width: 1400px;">
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h1 class="display-6 fw-bold mb-0 text-uppercase" style="font-family: var(--font-heading);">Lịch sử đơn hàng</h1>
            <div class="mt-3" style="width: 50px; height: 3px; background-color: var(--secondary-color);"></div>
        </div>
        <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-outline-dark rounded-0 px-4 text-uppercase letter-spacing-1 small fw-bold">
            <i class="fas fa-arrow-left me-2"></i>Trở về hồ sơ
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-5 overflow-auto">
        <div class="btn-group rounded-0 w-100 w-md-auto" role="group">
            <a href="<?php echo BASE_URL; ?>/orders" class="btn btn-outline-dark rounded-0 px-4 py-3 text-uppercase letter-spacing-1 small <?php echo $data['status'] === '' ? 'active bg-dark text-white' : ''; ?>">
                Tất cả
            </a>
            <a href="<?php echo BASE_URL; ?>/orders?status=pending" class="btn btn-outline-dark rounded-0 px-4 py-3 text-uppercase letter-spacing-1 small <?php echo $data['status'] === 'pending' ? 'active bg-dark text-white' : ''; ?>">
                Chờ xử lý
            </a>
            <a href="<?php echo BASE_URL; ?>/orders?status=confirmed" class="btn btn-outline-dark rounded-0 px-4 py-3 text-uppercase letter-spacing-1 small <?php echo $data['status'] === 'confirmed' ? 'active bg-dark text-white' : ''; ?>">
                Đã xác nhận
            </a>
            <a href="<?php echo BASE_URL; ?>/orders?status=shipping" class="btn btn-outline-dark rounded-0 px-4 py-3 text-uppercase letter-spacing-1 small <?php echo $data['status'] === 'shipping' ? 'active bg-dark text-white' : ''; ?>">
                Đang giao
            </a>
            <a href="<?php echo BASE_URL; ?>/orders?status=delivered" class="btn btn-outline-dark rounded-0 px-4 py-3 text-uppercase letter-spacing-1 small <?php echo $data['status'] === 'delivered' ? 'active bg-dark text-white' : ''; ?>">
                Đã giao
            </a>
            <a href="<?php echo BASE_URL; ?>/orders?status=cancelled" class="btn btn-outline-dark rounded-0 px-4 py-3 text-uppercase letter-spacing-1 small <?php echo $data['status'] === 'cancelled' ? 'active bg-dark text-white' : ''; ?>">
                Đã hủy
            </a>
        </div>
    </div>

    <!-- Orders List -->
    <?php if (!empty($data['orders'])): ?>
        <div class="card border-0 shadow-sm rounded-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted letter-spacing-1">
                        <tr>
                            <th class="ps-4 py-3 border-0">Mã đơn hàng</th>
                            <th class="py-3 border-0">Ngày đặt</th>
                            <th class="py-3 border-0">Tổng tiền</th>
                            <th class="py-3 border-0">Trạng thái</th>
                            <th class="text-end pe-4 py-3 border-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['orders'] as $order): ?>
                            <tr>
                                <td class="ps-4 py-4">
                                    <span class="fw-bold text-dark">#<?php echo $order['id']; ?></span>
                                </td>
                                <td class="text-muted small">
                                    <?php echo date('H:i d/m/Y', strtotime($order['created_at'])); ?>
                                </td>
                                <td class="fw-bold fs-5 text-dark">
                                    <?php echo number_format($order['total_amount'], 0, ',', '.'); ?>đ
                                </td>
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
                                        'delivered' => 'Giao thành công',
                                        'cancelled' => 'Đã hủy',
                                        'returned' => 'Hoàn trả'
                                    ];
                                    ?>
                                    <span class="badge rounded-0 px-3 py-2 fw-normal text-uppercase x-small letter-spacing-1 <?php echo $statusClasses[$order['status']] ?? 'bg-secondary'; ?>">
                                        <?php echo $statusLabels[$order['status']] ?? $order['status']; ?>
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo BASE_URL; ?>/orders/show/<?php echo $order['id']; ?>" 
                                       class="btn btn-outline-dark btn-sm rounded-0 text-uppercase letter-spacing-1 small">
                                        Chi tiết
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
            <div class="mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mb-0 gap-2">
                        <?php if ($data['currentPage'] > 1): ?>
                            <li class="page-item">
                                <a class="page-link border-0 text-dark rounded-0" href="<?php echo BASE_URL; ?>/orders?page=<?php echo $data['currentPage'] - 1; ?>&status=<?php echo $data['status']; ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
                            <li class="page-item">
                                <a class="page-link border-0 rounded-0 <?php echo $i == $data['currentPage'] ? 'bg-dark text-white fw-bold' : 'text-dark'; ?>" href="<?php echo BASE_URL; ?>/orders?page=<?php echo $i; ?>&status=<?php echo $data['status']; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($data['currentPage'] < $data['totalPages']): ?>
                            <li class="page-item">
                                <a class="page-link border-0 text-dark rounded-0" href="<?php echo BASE_URL; ?>/orders?page=<?php echo $data['currentPage'] + 1; ?>&status=<?php echo $data['status']; ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-5 bg-light">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-4 opacity-50"></i>
            <h3 class="fw-light text-uppercase letter-spacing-2 mb-3">Chưa có đơn hàng nào</h3>
            <p class="text-muted mb-5">Bạn chưa mua sản phẩm nào từ bộ sưu tập của chúng tôi.</p>
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-dark rounded-0 px-5 py-3 text-uppercase letter-spacing-2 fw-bold">
                Mua sắm ngay
            </a>
        </div>
    <?php endif; ?>
</div>
