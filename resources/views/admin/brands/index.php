<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-tags me-2"></i>Quản lý thương hiệu</h1>
            <a href="<?php echo BASE_URL; ?>/admin/brands/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm thương hiệu
            </a>
        </div>

        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control"
                               placeholder="Tìm kiếm thương hiệu..."
                               value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="<?php echo BASE_URL; ?>/admin/brands" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-2"></i>Xóa lọc
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Brands Table -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($brands)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có thương hiệu nào</h5>
                        <a href="<?php echo BASE_URL; ?>/admin/brands/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Thêm thương hiệu đầu tiên
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">Logo</th>
                                    <th width="25%">Tên thương hiệu</th>
                                    <th width="30%">Mô tả</th>
                                    <th width="10%">Slug</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($brands as $brand): ?>
                                    <tr>
                                        <td><?php echo $brand['id']; ?></td>
                                        <td>
                                            <?php if ($brand['logo_url']): ?>
                                                <img src="<?php echo htmlspecialchars($brand['logo_url']); ?>"
                                                     alt="<?php echo htmlspecialchars($brand['name']); ?>"
                                                     class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($brand['name']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars(substr($brand['description'] ?? '', 0, 50)); ?>
                                            <?php if (strlen($brand['description'] ?? '') > 50): ?>
                                                ...
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <code><?php echo htmlspecialchars($brand['slug']); ?></code>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>/admin/brands/edit/<?php echo $brand['id']; ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="deleteBrand(<?php echo $brand['id']; ?>, '<?php echo htmlspecialchars($brand['name']); ?>')"
                                                        title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $currentPage - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                    <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $currentPage + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function deleteBrand(id, name) {
    if (confirm('Bạn có chắc chắn muốn xóa thương hiệu "' + name + '"?\n\nLưu ý: Không thể xóa thương hiệu đang có sản phẩm.')) {
        window.location.href = '<?php echo BASE_URL; ?>/admin/brands/delete/' + id;
    }
}
</script>
