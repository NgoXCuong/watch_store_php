<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-eye me-2"></i>Chi tiết đánh giá</h1>
            <a href="<?php echo BASE_URL; ?>/admin/reviews" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <div class="row">
            <!-- Review Details -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2"></i>Đánh giá của <?php echo htmlspecialchars($data['review']['user_name']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Product Info -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <?php if (!empty($data['review']['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($data['review']['image_url']); ?>"
                                         alt="<?php echo htmlspecialchars($data['review']['product_name']); ?>"
                                         class="img-fluid rounded">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                         style="height: 100px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-9">
                                <h4><?php echo htmlspecialchars($data['review']['product_name']); ?></h4>
                                <p class="text-muted mb-2">ID sản phẩm: #<?php echo $data['review']['product_id']; ?></p>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-3">Đánh giá:</span>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $data['review']['rating'] ? 'text-warning' : 'text-muted'; ?> me-1"></i>
                                    <?php endfor; ?>
                                    <span class="ms-2 fw-bold"><?php echo $data['review']['rating']; ?>/5</span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Content -->
                        <div class="mb-4">
                            <h6>Nội dung đánh giá:</h6>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($data['review']['comment'])); ?></p>
                            </div>
                        </div>

                        <!-- Review Metadata -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Người đánh giá:</strong><br>
                                    <?php echo htmlspecialchars($data['review']['user_name']); ?>
                                </div>
                                <div class="mb-3">
                                    <strong>Thời gian:</strong><br>
                                    <?php echo date('d/m/Y \l\ú\c H:i:s', strtotime($data['review']['created_at'])); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Trạng thái:</strong><br>
                                    <?php if ($data['review']['is_approved']): ?>
                                        <span class="badge bg-success fs-6">Đã duyệt</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning fs-6">Chờ duyệt</span>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <strong>ID đánh giá:</strong><br>
                                    #<?php echo $data['review']['id']; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thao tác</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!$data['review']['is_approved']): ?>
                            <button type="button" class="btn btn-success btn-lg w-100 mb-3"
                                    onclick="approveReview(<?php echo $data['review']['id']; ?>)">
                                <i class="fas fa-check me-2"></i>Duyệt đánh giá
                            </button>

                            <button type="button" class="btn btn-warning btn-lg w-100 mb-3"
                                    onclick="rejectReview(<?php echo $data['review']['id']; ?>)">
                                <i class="fas fa-times me-2"></i>Từ chối đánh giá
                            </button>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>Đánh giá này đã được duyệt
                            </div>
                        <?php endif; ?>

                        <button type="button" class="btn btn-danger btn-lg w-100"
                                onclick="deleteReview(<?php echo $data['review']['id']; ?>)">
                            <i class="fas fa-trash me-2"></i>Xóa đánh giá
                        </button>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Thống kê nhanh</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h4 mb-1 text-primary"><?php echo $data['review']['rating']; ?></div>
                                <small class="text-muted">Sao</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 mb-1 text-info"><?php echo strlen($data['review']['comment']); ?></div>
                                <small class="text-muted">Ký tự</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Actions -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Liên kết</h6>
                    </div>
                    <div class="card-body">
                        <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo $data['review']['product_id']; ?>"
                           class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="fas fa-edit me-1"></i>Chỉnh sửa sản phẩm
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/users/edit/<?php echo $data['review']['user_id']; ?>"
                           class="btn btn-outline-info btn-sm w-100 mb-2">
                            <i class="fas fa-user me-1"></i>Xem thông tin khách hàng
                        </a>
                        <a href="<?php echo BASE_URL; ?>/admin/reviews"
                           class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-list me-1"></i>Xem tất cả đánh giá
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>
