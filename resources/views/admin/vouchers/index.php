<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-ticket-alt me-2"></i>Quản lý voucher</h1>
            <a href="<?php echo BASE_URL; ?>/admin/vouchers/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm voucher
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm theo mã hoặc mô tả..."
                               value="<?php echo htmlspecialchars($data['search']); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-2"></i>Tìm
                        </button>
                    </div>
                    <div class="col-md-2">
                        <?php if ($data['search']): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/vouchers" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i>Xóa lọc
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vouchers Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Mã voucher</th>
                                <th>Mô tả</th>
                                <th>Loại giảm giá</th>
                                <th>Giá trị</th>
                                <th>Giới hạn sử dụng</th>
                                <th>Thời hạn</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['vouchers'])): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Không tìm thấy voucher nào</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['vouchers'] as $voucher): ?>
                                    <tr>
                                        <td><?php echo $voucher['id']; ?></td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded"><?php echo htmlspecialchars($voucher['code']); ?></code>
                                        </td>
                                        <td>
                                            <div style="max-width: 200px;">
                                                <?php echo htmlspecialchars(substr($voucher['description'], 0, 50)); ?>
                                                <?php if (strlen($voucher['description']) > 50): ?>...<?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($voucher['discount_type'] === 'fixed'): ?>
                                                <span class="badge bg-success">Cố định</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Phần trăm</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($voucher['discount_type'] === 'fixed'): ?>
                                                <?php echo number_format($voucher['discount_value'], 0, ',', '.'); ?>đ
                                            <?php else: ?>
                                                <?php echo $voucher['discount_value']; ?>%
                                                <?php if ($voucher['max_discount_amount']): ?>
                                                    <br><small class="text-muted">Tối đa: <?php echo number_format($voucher['max_discount_amount'], 0, ',', '.'); ?>đ</small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo $voucher['usage_count']; ?>/<?php echo $voucher['usage_limit']; ?></strong>
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                         style="width: <?php echo min(100, ($voucher['usage_count'] / $voucher['usage_limit']) * 100); ?>%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-size: 0.9rem;">
                                                <?php if ($voucher['start_date']): ?>
                                                    <div><i class="fas fa-play text-success me-1"></i><?php echo date('d/m/Y', strtotime($voucher['start_date'])); ?></div>
                                                <?php endif; ?>
                                                <?php if ($voucher['end_date']): ?>
                                                    <div><i class="fas fa-stop text-danger me-1"></i><?php echo date('d/m/Y', strtotime($voucher['end_date'])); ?></div>
                                                <?php endif; ?>
                                                <?php if (!$voucher['start_date'] && !$voucher['end_date']): ?>
                                                    <span class="text-muted">Không giới hạn</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $now = date('Y-m-d H:i:s');
                                            $isExpired = ($voucher['end_date'] && $now > $voucher['end_date']);
                                            $notStarted = ($voucher['start_date'] && $now < $voucher['start_date']);
                                            $isUsedUp = $voucher['usage_count'] >= $voucher['usage_limit'];

                                            if (!$voucher['is_active']) {
                                                echo '<span class="badge bg-secondary">Tạm dừng</span>';
                                            } elseif ($isExpired) {
                                                echo '<span class="badge bg-danger">Hết hạn</span>';
                                            } elseif ($notStarted) {
                                                echo '<span class="badge bg-warning">Chưa bắt đầu</span>';
                                            } elseif ($isUsedUp) {
                                                echo '<span class="badge bg-dark">Hết lượt</span>';
                                            } else {
                                                echo '<span class="badge bg-success">Hoạt động</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>/admin/vouchers/edit/<?php echo $voucher['id']; ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteVoucher(<?php echo $voucher['id']; ?>, '<?php echo htmlspecialchars($voucher['code']); ?>')"
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($data['totalPages'] > 1): ?>
                    <nav aria-label="Voucher pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($data['currentPage'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $data['currentPage'] - 1; ?>&search=<?php echo urlencode($data['search']); ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $data['currentPage'] - 2); $i <= min($data['totalPages'], $data['currentPage'] + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $data['currentPage'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($data['search']); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $data['currentPage'] + 1; ?>&search=<?php echo urlencode($data['search']); ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteVoucher(id, code) {
    if (confirm('Bạn có chắc chắn muốn xóa voucher "' + code + '"?\n\nLưu ý: Hành động này không thể hoàn tác.')) {
        // Create a form to submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/admin/vouchers/delete/' + id;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'DELETE';

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
