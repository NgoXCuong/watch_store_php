<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-star me-2"></i>Quản lý đánh giá</h1>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">Tổng đánh giá</h5>
                                <h3 class="mb-0"><?php echo $data['statistics']['total_reviews']; ?></h3>
                            </div>
                            <i class="fas fa-star fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">Đã duyệt</h5>
                                <h3 class="mb-0"><?php echo $data['statistics']['approved_reviews']; ?></h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">Chờ duyệt</h5>
                                <h3 class="mb-0"><?php echo $data['statistics']['pending_reviews']; ?></h3>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title mb-0">Đánh giá TB</h5>
                                <h3 class="mb-0"><?php echo number_format($data['statistics']['average_rating'], 1); ?>/5</h3>
                            </div>
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="card mb-4">
            <div class="card-body">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $data['status'] === 'all' ? 'active' : ''; ?>"
                           href="<?php echo BASE_URL; ?>/admin/reviews?status=all">
                            <i class="fas fa-list me-1"></i>Tất cả (<?php echo $data['statistics']['total_reviews']; ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $data['status'] === 'pending' ? 'active' : ''; ?>"
                           href="<?php echo BASE_URL; ?>/admin/reviews?status=pending">
                            <i class="fas fa-clock me-1"></i>Chờ duyệt (<?php echo $data['statistics']['pending_reviews']; ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $data['status'] === 'approved' ? 'active' : ''; ?>"
                           href="<?php echo BASE_URL; ?>/admin/reviews?status=approved">
                            <i class="fas fa-check-circle me-1"></i>Đã duyệt (<?php echo $data['statistics']['approved_reviews']; ?>)
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="card mb-4">
            <div class="card-body">
                <form id="bulkForm" method="POST">
                    <div class="d-flex gap-2 align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            <label class="form-check-label" for="selectAll">
                                Chọn tất cả
                            </label>
                        </div>

                        <div class="ms-auto">
                            <button type="submit" formaction="<?php echo BASE_URL; ?>/admin/reviews/bulkApprove"
                                    class="btn btn-success btn-sm" onclick="return confirmBulkAction('duyệt')">
                                <i class="fas fa-check me-1"></i>Duyệt hàng loạt
                            </button>
                            <button type="submit" formaction="<?php echo BASE_URL; ?>/admin/reviews/bulkReject"
                                    class="btn btn-warning btn-sm" onclick="return confirmBulkAction('từ chối')">
                                <i class="fas fa-times me-1"></i>Từ chối hàng loạt
                            </button>
                            <button type="submit" formaction="<?php echo BASE_URL; ?>/admin/reviews/bulkDelete"
                                    class="btn btn-danger btn-sm" onclick="return confirmBulkAction('xóa')">
                                <i class="fas fa-trash me-1"></i>Xóa hàng loạt
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" class="form-check-input" id="selectAllTable" onchange="toggleSelectAllTable()">
                                </th>
                                <th>Sản phẩm</th>
                                <th>Khách hàng</th>
                                <th>Đánh giá</th>
                                <th>Nội dung</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['reviews'])): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Không có đánh giá nào</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['reviews'] as $review): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input review-checkbox"
                                                   name="review_ids[]" value="<?php echo $review['id']; ?>"
                                                   form="bulkForm">
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($review['product_name']); ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($review['user_name']); ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                <?php endfor; ?>
                                                <span class="ms-2"><?php echo $review['rating']; ?>/5</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-width: 300px;">
                                                <?php echo htmlspecialchars(substr($review['comment'], 0, 100)); ?>
                                                <?php if (strlen($review['comment']) > 100): ?>...<?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></td>
                                        <td>
                                            <?php if ($review['is_approved']): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>/admin/reviews/show/<?php echo $review['id']; ?>"
                                                   class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if (!$review['is_approved']): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                            onclick="approveReview(<?php echo $review['id']; ?>)"
                                                            title="Duyệt đánh giá">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                                            onclick="rejectReview(<?php echo $review['id']; ?>)"
                                                            title="Từ chối đánh giá">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteReview(<?php echo $review['id']; ?>)"
                                                        title="Xóa đánh giá">
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
                    <nav aria-label="Review pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($data['currentPage'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $data['currentPage'] - 1; ?>&status=<?php echo $data['status']; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $data['currentPage'] - 2); $i <= min($data['totalPages'], $data['currentPage'] + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $data['currentPage'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&status=<?php echo $data['status']; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $data['currentPage'] + 1; ?>&status=<?php echo $data['status']; ?>">
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
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function toggleSelectAllTable() {
    const selectAllTable = document.getElementById('selectAllTable');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllTable.checked;
    });
    document.getElementById('selectAll').checked = selectAllTable.checked;
}

function approveReview(id) {
    if (confirm('Bạn có chắc chắn muốn duyệt đánh giá này?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/admin/reviews/approve/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectReview(id) {
    if (confirm('Bạn có chắc chắn muốn từ chối đánh giá này?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/admin/reviews/reject/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteReview(id) {
    if (confirm('Bạn có chắc chắn muốn xóa đánh giá này?\n\nLưu ý: Hành động này không thể hoàn tác.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/admin/reviews/delete/' + id;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'DELETE';

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmBulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Vui lòng chọn ít nhất một đánh giá để ' + action);
        return false;
    }

    return confirm('Bạn có chắc chắn muốn ' + action + ' ' + checkedBoxes.length + ' đánh giá đã chọn?');
}
</script>
