<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-plus me-2"></i>Thêm danh mục mới</h1>
            <a href="<?php echo BASE_URL; ?>/admin/categories" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="<?php echo BASE_URL; ?>/admin/categories/store">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                                           id="name" name="name" value="<?php echo htmlspecialchars($old_input['name'] ?? ''); ?>" required>
                                    <?php if (isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo isset($errors['slug']) ? 'is-invalid' : ''; ?>"
                                           id="slug" name="slug" value="<?php echo htmlspecialchars($old_input['slug'] ?? ''); ?>" required>
                                    <?php if (isset($errors['slug'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['slug']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($old_input['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Danh mục cha</label>
                                <select class="form-select" id="parent_id" name="parent_id">
                                    <option value="">Không có danh mục cha (Danh mục gốc)</option>
                                    <?php
                                    $parentCategories = array_filter($categories, function($cat) {
                                        return $cat['parent_id'] === null;
                                    });
                                    ?>
                                    <?php foreach ($parentCategories as $parent): ?>
                                        <option value="<?php echo $parent['id']; ?>"
                                                <?php echo (isset($old_input['parent_id']) && $old_input['parent_id'] == $parent['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($parent['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">Chọn danh mục cha nếu đây là danh mục con</div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo BASE_URL; ?>/admin/categories" class="btn btn-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Thêm danh mục
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Hướng dẫn</h5>
                    </div>
                    <div class="card-body">
                        <h6>Quy tắc đặt tên:</h6>
                        <ul class="mb-3">
                            <li>Tên danh mục nên ngắn gọn, dễ hiểu</li>
                            <li>Slug sẽ tự động tạo từ tên (có thể chỉnh sửa)</li>
                            <li>Mô tả giúp khách hàng hiểu rõ hơn về danh mục</li>
                        </ul>

                        <h6>Cấu trúc danh mục:</h6>
                        <ul class="mb-3">
                            <li><strong>Danh mục gốc:</strong> Không có danh mục cha</li>
                            <li><strong>Danh mục con:</strong> Thuộc về một danh mục cha</li>
                        </ul>

                        <h6>Ví dụ:</h6>
                        <ul>
                            <li>Đồng hồ nam (danh mục gốc)</li>
                            <li>&nbsp;&nbsp;└── Đồng hồ cơ (danh mục con)</li>
                            <li>&nbsp;&nbsp;└── Đồng hồ điện tử (danh mục con)</li>
                        </ul>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Danh mục hiện có</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($categories)): ?>
                            <p class="text-muted">Chưa có danh mục nào</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($categories as $cat): ?>
                                    <li class="list-group-item px-0">
                                        <?php if ($cat['parent_id']): ?>
                                            <span class="text-muted">└── </span>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});
</script>
