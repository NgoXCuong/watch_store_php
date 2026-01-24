<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-edit me-2"></i>Chỉnh sửa voucher</h1>
            <a href="<?php echo BASE_URL; ?>/admin/vouchers" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>/admin/vouchers/update/<?php echo $data['voucher']['id']; ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã voucher <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-uppercase <?php echo isset($data['errors']['code']) ? 'is-invalid' : ''; ?>"
                                       id="code" name="code"
                                       value="<?php echo htmlspecialchars($data['old_input']['code'] ?? $data['voucher']['code']); ?>" required>
                                <?php if (isset($data['errors']['code'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['code']; ?></div>
                                <?php endif; ?>
                                <div class="form-text">Mã voucher sẽ được chuyển thành chữ hoa</div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                                <textarea class="form-control <?php echo isset($data['errors']['description']) ? 'is-invalid' : ''; ?>"
                                          id="description" name="description" rows="3" required><?php echo htmlspecialchars($data['old_input']['description'] ?? $data['voucher']['description']); ?></textarea>
                                <?php if (isset($data['errors']['description'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['description']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="discount_type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                                <select class="form-select" id="discount_type" name="discount_type" required onchange="toggleDiscountFields()">
                                    <option value="fixed" <?php echo ($data['old_input']['discount_type'] ?? $data['voucher']['discount_type']) === 'fixed' ? 'selected' : ''; ?>>Giảm giá cố định (VNĐ)</option>
                                    <option value="percent" <?php echo ($data['old_input']['discount_type'] ?? $data['voucher']['discount_type']) === 'percent' ? 'selected' : ''; ?>>Giảm giá theo phần trăm (%)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="discount_value" class="form-label">Giá trị giảm giá <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php echo isset($data['errors']['discount_value']) ? 'is-invalid' : ''; ?>"
                                       id="discount_value" name="discount_value" min="0" step="0.01"
                                       value="<?php echo htmlspecialchars($data['old_input']['discount_value'] ?? $data['voucher']['discount_value']); ?>" required>
                                <?php if (isset($data['errors']['discount_value'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['discount_value']; ?></div>
                                <?php endif; ?>
                                <div class="form-text" id="discount-help">Nhập số tiền giảm (VNĐ)</div>
                            </div>

                            <div class="mb-3" id="max_discount_container" style="<?php echo (($data['old_input']['discount_type'] ?? $data['voucher']['discount_type']) === 'percent') ? 'display: block;' : 'display: none;'; ?>">
                                <label for="max_discount_amount" class="form-label">Giảm tối đa (VNĐ)</label>
                                <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount" min="0" step="0.01"
                                       value="<?php echo htmlspecialchars($data['old_input']['max_discount_amount'] ?? $data['voucher']['max_discount_amount']); ?>">
                                <div class="form-text">Giới hạn số tiền giảm tối đa khi dùng % (tùy chọn)</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="min_order_value" class="form-label">Giá trị đơn hàng tối thiểu <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php echo isset($data['errors']['min_order_value']) ? 'is-invalid' : ''; ?>"
                                       id="min_order_value" name="min_order_value" min="0" step="0.01"
                                       value="<?php echo htmlspecialchars($data['old_input']['min_order_value'] ?? $data['voucher']['min_order_value']); ?>" required>
                                <?php if (isset($data['errors']['min_order_value'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['min_order_value']; ?></div>
                                <?php endif; ?>
                                <div class="form-text">Đơn hàng phải đạt giá trị tối thiểu để dùng voucher</div>
                            </div>

                            <div class="mb-3">
                                <label for="usage_limit" class="form-label">Số lần sử dụng tối đa <span class="text-danger">*</span></label>
                                <input type="number" class="form-control <?php echo isset($data['errors']['usage_limit']) ? 'is-invalid' : ''; ?>"
                                       id="usage_limit" name="usage_limit" min="1"
                                       value="<?php echo htmlspecialchars($data['old_input']['usage_limit'] ?? $data['voucher']['usage_limit']); ?>" required>
                                <?php if (isset($data['errors']['usage_limit'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['usage_limit']; ?></div>
                                <?php endif; ?>
                                <div class="form-text">Số lần voucher có thể được sử dụng</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                        <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                               value="<?php echo htmlspecialchars($data['old_input']['start_date'] ?? ($data['voucher']['start_date'] ? date('Y-m-d\TH:i', strtotime($data['voucher']['start_date'])) : '')); ?>">
                                        <div class="form-text">Để trống nếu không giới hạn</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                                        <input type="datetime-local" class="form-control <?php echo isset($data['errors']['end_date']) ? 'is-invalid' : ''; ?>"
                                               id="end_date" name="end_date"
                                               value="<?php echo htmlspecialchars($data['old_input']['end_date'] ?? ($data['voucher']['end_date'] ? date('Y-m-d\TH:i', strtotime($data['voucher']['end_date'])) : '')); ?>">
                                        <?php if (isset($data['errors']['end_date'])): ?>
                                            <div class="invalid-feedback"><?php echo $data['errors']['end_date']; ?></div>
                                        <?php endif; ?>
                                        <div class="form-text">Để trống nếu không giới hạn</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                           <?php echo ($data['old_input']['is_active'] ?? $data['voucher']['is_active']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">
                                        Kích hoạt voucher
                                    </label>
                                </div>
                                <div class="form-text">Voucher chỉ hoạt động khi được kích hoạt</div>
                            </div>

                            <!-- Voucher Statistics -->
                            <div class="mb-3">
                                <label class="form-label">Thống kê sử dụng</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="h5 mb-1"><?php echo $data['voucher']['usage_count']; ?></div>
                                            <small class="text-muted">Đã sử dụng</small>
                                        </div>
                                        <div class="col-6">
                                            <div class="h5 mb-1"><?php echo $data['voucher']['usage_limit'] - $data['voucher']['usage_count']; ?></div>
                                            <small class="text-muted">Còn lại</small>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: <?php echo min(100, ($data['voucher']['usage_count'] / $data['voucher']['usage_limit']) * 100); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($data['errors']['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $data['errors']['general']; ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật voucher
                        </button>
                        <a href="<?php echo BASE_URL; ?>/admin/vouchers" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <button type="button" class="btn btn-outline-danger ms-auto"
                                onclick="deleteVoucher(<?php echo $data['voucher']['id']; ?>, '<?php echo htmlspecialchars($data['voucher']['code']); ?>')">
                            <i class="fas fa-trash me-2"></i>Xóa voucher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDiscountFields() {
    const discountType = document.getElementById('discount_type').value;
    const discountHelp = document.getElementById('discount-help');
    const maxDiscountContainer = document.getElementById('max_discount_container');

    if (discountType === 'percent') {
        discountHelp.textContent = 'Nhập phần trăm giảm (0-100%)';
        maxDiscountContainer.style.display = 'block';
    } else {
        discountHelp.textContent = 'Nhập số tiền giảm (VNĐ)';
        maxDiscountContainer.style.display = 'none';
    }
}

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDiscountFields();
});
</script>
