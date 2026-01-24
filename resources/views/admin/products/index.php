<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-box me-2"></i>Quản lý sản phẩm</h1>
            <a href="<?php echo BASE_URL; ?>/admin/products/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm sản phẩm..."
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="category">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                        <?php echo $selectedCategory == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="brand">
                            <option value="">Tất cả thương hiệu</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo $brand['id']; ?>"
                                        <?php echo $selectedBrand == $brand['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($brand['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-2"></i>Tìm
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Thương hiệu</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Không có sản phẩm nào</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['id']; ?></td>
                                        <td>
                                            <?php if ($product['image_url']): ?>
                                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px; border-radius: 5px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                                                <?php if ($product['is_featured']): ?>
                                                    <span class="badge bg-warning ms-1">Nổi bật</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
                                        <td>
                                            <div>
                                                <span class="fw-bold text-primary">
                                                    <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                                                </span>
                                                <?php if ($product['old_price']): ?>
                                                    <br><small class="text-muted text-decoration-line-through">
                                                        <?php echo number_format($product['old_price'], 0, ',', '.'); ?>đ
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $product['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $product['stock']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $product['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $product['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>/admin/products/show/<?php echo $product['id']; ?>"
                                                   class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo $product['id']; ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>')"
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
                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $selectedCategory; ?>&brand=<?php echo $selectedBrand; ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $selectedCategory; ?>&brand=<?php echo $selectedBrand; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $selectedCategory; ?>&brand=<?php echo $selectedBrand; ?>">
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
function deleteProduct(id, name) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm "' + name + '"?')) {
        // Create a form to submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/admin/products/delete/' + id;

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
