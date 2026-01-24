<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-plus me-2"></i>Thêm sản phẩm mới</h1>
            <a href="<?php echo BASE_URL; ?>/admin/products" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/products/store" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Thông tin cơ bản -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin cơ bản</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
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

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>"
                                                   id="price" name="price" value="<?php echo htmlspecialchars($old_input['price'] ?? ''); ?>" min="0" step="0.01" required>
                                            <?php if (isset($errors['price'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="old_price" class="form-label">Giá cũ</label>
                                            <input type="number" class="form-control" id="old_price" name="old_price"
                                                   value="<?php echo htmlspecialchars($old_input['old_price'] ?? ''); ?>" min="0" step="0.01">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="stock" class="form-label">Tồn kho</label>
                                            <input type="number" class="form-control" id="stock" name="stock"
                                                   value="<?php echo htmlspecialchars($old_input['stock'] ?? '0'); ?>" min="0">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="brand_id" class="form-label">Thương hiệu <span class="text-danger">*</span></label>
                                            <select class="form-select <?php echo isset($errors['brand_id']) ? 'is-invalid' : ''; ?>" id="brand_id" name="brand_id" required>
                                                <option value="">Chọn thương hiệu</option>
                                                <?php foreach ($brands as $brand): ?>
                                                    <option value="<?php echo $brand['id']; ?>"
                                                            <?php echo (isset($old_input['brand_id']) && $old_input['brand_id'] == $brand['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($brand['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if (isset($errors['brand_id'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['brand_id']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                            <select class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
                                                <option value="">Chọn danh mục</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>"
                                                            <?php echo (isset($old_input['category_id']) && $old_input['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($category['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if (isset($errors['category_id'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['category_id']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin bổ sung -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Thông tin bổ sung</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Thông số kỹ thuật</label>
                                        <div id="specs-wrapper" class="border rounded p-3 bg-light">
                                            <div id="specs-container">
                                                <!-- Dynamic rows will be inserted here -->
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addSpecRow()">
                                                <i class="fas fa-plus me-1"></i>Thêm thông số
                                            </button>
                                        </div>
                                        <textarea class="d-none" id="specifications" name="specifications"><?php echo htmlspecialchars($old_input['specifications'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="image_file" class="form-label">Hình ảnh chính</label>
                                            <input type="file" class="form-control" id="image_file" name="image_file"
                                                   accept="image/*" onchange="previewMainImage(event)">
                                            <div class="form-text">Chọn file hình ảnh (JPG, PNG, GIF, WebP)</div>
                                            <?php if (isset($errors['image_file'])): ?>
                                                <div class="text-danger small"><?php echo $errors['image_file']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="gallery_files" class="form-label">Thư viện ảnh</label>
                                            <input type="file" class="form-control" id="gallery_files" name="gallery_files[]"
                                                   accept="image/*" multiple onchange="previewGalleryImages(event)">
                                            <div class="form-text">Chọn nhiều file hình ảnh (tối đa 5 ảnh)</div>
                                            <?php if (isset($errors['gallery_files'])): ?>
                                                <div class="text-danger small"><?php echo $errors['gallery_files']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Hidden inputs for URLs (will be populated by JavaScript) -->
                                    <input type="hidden" id="image_url" name="image_url" value="<?php echo htmlspecialchars($old_input['image_url'] ?? ''); ?>">
                                    <input type="hidden" id="gallery_urls" name="gallery_urls" value="<?php echo htmlspecialchars($old_input['gallery_urls'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Cài đặt -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Cài đặt</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active" <?php echo (isset($old_input['status']) && $old_input['status'] === 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                                            <option value="inactive" <?php echo (isset($old_input['status']) && $old_input['status'] === 'inactive') ? 'selected' : ''; ?>>Không hoạt động</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                                                   <?php echo (isset($old_input['is_featured']) && $old_input['is_featured']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="is_featured">
                                                Sản phẩm nổi bật
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview hình ảnh -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-image me-2"></i>Preview hình ảnh</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div id="image-preview" class="mb-3">
                                        <img id="preview-img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px; display: none;">
                                        <div id="no-image" class="text-muted">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p>Chưa có hình ảnh</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo BASE_URL; ?>/admin/products" class="btn btn-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Thêm sản phẩm
                        </button>
                    </div>
                </form>
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

// Preview main image from file input
function previewMainImage(event) {
    const file = event.target.files[0];
    const previewImg = document.getElementById('preview-img');
    const noImage = document.getElementById('no-image');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            noImage.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        previewImg.style.display = 'none';
        noImage.style.display = 'block';
    }
}

// Preview gallery images
function previewGalleryImages(event) {
    const files = event.target.files;
    const galleryPreview = document.getElementById('gallery-preview');

    // Clear previous previews
    galleryPreview.innerHTML = '';

    if (files.length > 0) {
        for (let i = 0; i < Math.min(files.length, 5); i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'gallery-img-container';
                imgContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Gallery ${i + 1}" class="gallery-img">
                `;
                galleryPreview.appendChild(imgContainer);
            };

            reader.readAsDataURL(file);
        }
    }
}

// --- SPECIFICATIONS MANAGEMENT ---
function addSpecRow(key = '', value = '') {
    const container = document.getElementById('specs-container');
    const rowId = 'spec-row-' + Date.now() + Math.random().toString(36).substr(2, 5);
    
    const row = document.createElement('div');
    row.className = 'row mb-2 spec-row align-items-center';
    row.id = rowId;
    
    row.innerHTML = `
        <div class="col-5">
            <input type="text" class="form-control form-control-sm spec-key" placeholder="Tên thông số (VD: Chất liệu)" value="${key}">
        </div>
        <div class="col-6">
            <input type="text" class="form-control form-control-sm spec-value" placeholder="Giá trị (VD: Thép không gỉ)" value="${value}">
        </div>
        <div class="col-1 text-end">
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSpecRow('${rowId}')" title="Xóa">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    container.appendChild(row);
}

function removeSpecRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) row.remove();
}

function initSpecs() {
    const specsJson = document.getElementById('specifications').value;
    try {
        if (specsJson && specsJson.trim() !== '') {
            const specs = JSON.parse(specsJson);
            for (const [key, value] of Object.entries(specs)) {
                addSpecRow(key, value);
            }
        } else {
            // Add one empty row by default if empty
            addSpecRow();
        }
    } catch (e) {
        console.error('Invalid JSON specs:', e);
        addSpecRow();
    }
}

// Bind form submit to serialize specs
document.querySelector('form').addEventListener('submit', function(e) {
    const rows = document.querySelectorAll('.spec-row');
    const specs = {};
    
    rows.forEach(row => {
        const key = row.querySelector('.spec-key').value.trim();
        const value = row.querySelector('.spec-value').value.trim();
        
        if (key && value) {
            specs[key] = value;
        }
    });

    // Handle empty specs
    if (Object.keys(specs).length > 0) {
        document.getElementById('specifications').value = JSON.stringify(specs);
    } else {
        document.getElementById('specifications').value = '';
    }
});

// Load preview on page load
document.addEventListener('DOMContentLoaded', function() {
    const imageUrl = document.getElementById('image_url').value;
    const previewImg = document.getElementById('preview-img');
    const noImage = document.getElementById('no-image');

    if (imageUrl && imageUrl.trim() !== '') {
        previewImg.style.display = 'block';
        noImage.style.display = 'none';
        console.log('Loading image from URL:', imageUrl);
    } else {
        previewImg.style.display = 'none';
        noImage.style.display = 'block';
        console.log('No image URL found');
    }

    // Add gallery preview container if it doesn't exist
    const previewCard = document.querySelector('.card-body.text-center');
    if (previewCard && !document.getElementById('gallery-preview')) {
        const galleryDiv = document.createElement('div');
        galleryDiv.id = 'gallery-preview';
        galleryDiv.className = 'gallery-preview mt-3';
        previewCard.appendChild(galleryDiv);
    }

    // Debug: Log current values
    console.log('Create page loaded with image_url:', imageUrl);
    console.log('Gallery URLs:', document.getElementById('gallery_urls').value);

    // Initialize Specs Builder
    initSpecs();
});
</script>

<style>
.gallery-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.gallery-img-container {
    position: relative;
}

.gallery-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #dee2e6;
}
</style>
