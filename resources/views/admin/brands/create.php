<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-plus me-2"></i>Thêm thương hiệu mới</h1>
            <a href="<?php echo BASE_URL; ?>/admin/brands" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>/admin/brands/store" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Tên thương hiệu <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>"
                                       id="name" name="name"
                                       value="<?php echo htmlspecialchars($old_input['name'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug" class="form-label">
                                    Slug <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control <?php echo isset($errors['slug']) ? 'is-invalid' : ''; ?>"
                                       id="slug" name="slug"
                                       value="<?php echo htmlspecialchars($old_input['slug'] ?? ''); ?>"
                                       required>
                                <?php if (isset($errors['slug'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['slug']; ?></div>
                                <?php endif; ?>
                                <div class="form-text">Slug sẽ được sử dụng trong URL. Chỉ chứa chữ cái, số và dấu gạch ngang.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($old_input['description'] ?? ''); ?></textarea>
                        <div class="form-text">Mô tả ngắn gọn về thương hiệu (tùy chọn).</div>
                    </div>

                    <div class="mb-3">
                        <label for="logo_file" class="form-label">Logo thương hiệu</label>
                        <input type="file" class="form-control" id="logo_file" name="logo_file"
                               accept="image/*" onchange="previewLogo(event)">
                        <div class="form-text">Chọn file hình ảnh logo (JPG, PNG, GIF, WebP - tối đa 2MB).</div>
                        <?php if (isset($errors['logo_file'])): ?>
                            <div class="text-danger small"><?php echo $errors['logo_file']; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Hidden input for logo URL -->
                    <input type="hidden" id="logo_url" name="logo_url" value="<?php echo htmlspecialchars($old_input['logo_url'] ?? ''); ?>">

                    <!-- Logo Preview -->
                    <div class="mb-3">
                        <label class="form-label">Preview Logo</label>
                        <div class="border rounded p-3 text-center" style="min-height: 120px;">
                            <img id="logo-preview-img" src="" alt="Logo Preview" class="logo-preview" style="display: none;">
                            <div id="no-logo" class="text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p>Chưa có logo</p>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $errors['general']; ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Thêm thương hiệu
                        </button>
                        <a href="<?php echo BASE_URL; ?>/admin/brands" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-')
                     .replace(/-+/g, '-')
                     .trim('-');
    document.getElementById('slug').value = slug;
});

// Preview logo from file input
function previewLogo(event) {
    const file = event.target.files[0];
    const previewImg = document.getElementById('logo-preview-img');
    const noLogo = document.getElementById('no-logo');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            noLogo.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.style.display = 'none';
        noLogo.style.display = 'block';
    }
}
</script>

<style>
.logo-preview {
    max-width: 150px;
    max-height: 150px;
    border: 2px solid #dee2e6;
    border-radius: 8px;
}
</style>
