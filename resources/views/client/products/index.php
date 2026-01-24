<!-- Breadcrumb -->
<section class="breadcrumb-section bg-light py-3 border-bottom mb-5">
    <div class="container" style="max-width: 1400px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 small text-uppercase" style="letter-spacing: 1px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bộ sưu tập</li>
                <?php if ($data['currentCategory']): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($data['currentCategory']['name']); ?></li>
                <?php endif; ?>
                <?php if ($data['currentBrand']): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($data['currentBrand']['name']); ?></li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
</section>

<section class="products-section pb-5">
    <div class="container" style="max-width: 1400px;">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 pe-lg-5">
                <div class="sidebar mb-5 mb-lg-0 sticky-top" style="top: 100px; z-index: 900;">
                    <!-- Search -->
                    <div class="mb-5">
                        <form method="GET" class="position-relative">
                            <input type="text" name="search" class="form-control rounded-0 border-0 border-bottom shadow-none ps-0" 
                                   placeholder="Tìm kiếm..." value="<?php echo htmlspecialchars($data['search']); ?>"
                                   style="background: transparent;">
                            <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y border-0 p-0 text-muted">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Categories -->
                    <div class="mb-5">
                        <h5 class="text-uppercase letter-spacing-2 mb-3 fs-6 fw-bold">Danh mục</h5>
                        <div class="d-flex flex-column gap-2">
                            <a href="<?php echo BASE_URL; ?>/products" class="text-decoration-none <?php echo !$data['selectedCategory'] ? 'text-primary fw-bold' : 'text-muted'; ?>">
                                Tất cả
                            </a>
                            <?php foreach ($data['categories'] as $category): ?>
                                <a href="?category=<?php echo $category['id']; ?>&brand=<?php echo $data['selectedBrand']; ?>&sort=<?php echo $data['selectedSort']; ?>" 
                                   class="text-decoration-none <?php echo $data['selectedCategory'] == $category['id'] ? 'text-primary fw-bold' : 'text-muted'; ?> d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="mb-5">
                        <h5 class="text-uppercase letter-spacing-2 mb-3 fs-6 fw-bold">Thương hiệu</h5>
                        <div class="d-flex flex-column gap-2">
                            <a href="?category=<?php echo $data['selectedCategory']; ?>&sort=<?php echo $data['selectedSort']; ?>" class="text-decoration-none <?php echo !$data['selectedBrand'] ? 'text-primary fw-bold' : 'text-muted'; ?>">
                                Tất cả
                            </a>
                            <?php foreach ($data['brands'] as $brand): ?>
                                <a href="?brand=<?php echo $brand['id']; ?>&category=<?php echo $data['selectedCategory']; ?>&sort=<?php echo $data['selectedSort']; ?>" 
                                   class="text-decoration-none <?php echo $data['selectedBrand'] == $brand['id'] ? 'text-primary fw-bold' : 'text-muted'; ?> d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($brand['name']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                    <span class="text-muted small text-uppercase letter-spacing-1">
                        Hiển thị <?php echo count($data['products']); ?> kết quả
                    </span>
                    
                    <div class="d-flex align-items-center gap-3">
                        <form method="GET" id="sortForm">
                            <?php
                            // Keep existing filters
                            foreach ($_GET as $key => $value) {
                                if ($key !== 'sort') {
                                    echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                                }
                            }
                            ?>
                            <select name="sort" class="form-select form-select-sm border-0 bg-transparent text-end fw-bold" 
                                    style="box-shadow: none; cursor: pointer;"
                                    onchange="this.form.submit()">
                                <option value="default" <?php echo $data['selectedSort'] === 'default' ? 'selected' : ''; ?>>Mặc định</option>
                                <option value="latest" <?php echo $data['selectedSort'] === 'latest' ? 'selected' : ''; ?>>Mới nhất</option>
                                <option value="price_asc" <?php echo $data['selectedSort'] === 'price_asc' ? 'selected' : ''; ?>>Giá: Thấp - Cao</option>
                                <option value="price_desc" <?php echo $data['selectedSort'] === 'price_desc' ? 'selected' : ''; ?>>Giá: Cao - Thấp</option>
                                <option value="name" <?php echo $data['selectedSort'] === 'name' ? 'selected' : ''; ?>>Tên: A - Z</option>
                            </select>
                        </form>
                    </div>
                </div>

                <?php if (empty($data['products'])): ?>
                    <div class="text-center py-5">
                        <i class="far fa-sad-tear fa-3x text-muted mb-3"></i>
                        <h3 class="fw-light">Không tìm thấy sản phẩm</h3>
                        <p class="text-muted">Vui lòng thử lại với từ khóa hoặc bộ lọc khác.</p>
                        <a href="<?php echo BASE_URL; ?>/products" class="btn btn-outline-dark rounded-0 px-4 mt-3 text-uppercase" style="letter-spacing: 1px;">
                            Xóa bộ lọc
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                        <?php foreach ($data['products'] as $product): ?>
                            <div class="col">
                                <div class="product-card h-100">
                                    <div class="product-image-wrapper">
                                        <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                                            <div class="position-absolute top-0 start-0 m-3 badge bg-dark rounded-0 fw-normal small z-2">
                                                -<?php echo round((1 - $product['price'] / $product['old_price']) * 100); ?>%
                                            </div>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>">
                                            <?php if ($product['image_url']): ?>
                                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                                     class="product-image">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                    <i class="far fa-image fa-2x text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </a>

                                        <!-- Quick Actions Overlay -->
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex gap-2 justify-content-center opacity-0 product-actions-overlay" style="transition: opacity 0.3s;">
                                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px; padding: 0; line-height: 40px;">
                                                <i class="far fa-eye"></i>
                                            </a>
                                            <?php if (isset($_SESSION['user'])): ?>
                                                <form action="<?php echo BASE_URL; ?>/cart/add" method="POST">
                                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-dark rounded-circle shadow-sm" style="width: 40px; height: 40px; padding: 0; line-height: 40px;">
                                                        <i class="fas fa-shopping-bag"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="product-info mt-3 text-center">
                                        <div class="product-category text-muted x-small text-uppercase letter-spacing-2 mb-2">
                                            <?php echo htmlspecialchars($product['category_name'] ?? ''); ?>
                                        </div>
                                        <h3 class="h6 mb-2">
                                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($product['name']); ?>
                                            </a>
                                        </h3>
                                        <div class="product-price">
                                            <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                                                <span class="text-decoration-line-through text-muted small me-2">
                                                    <?php echo number_format($product['old_price'], 0, ',', '.'); ?>đ
                                                </span>
                                            <?php endif; ?>
                                            <span class="fw-bold fs-5">
                                                <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($data['totalPages'] > 1): ?>
                        <div class="mt-5 pt-4 border-top">
                            <nav aria-label="Product pagination">
                                <ul class="pagination justify-content-center rounded-0">
                                    <?php if ($data['currentPage'] > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link border-0 text-dark" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $data['currentPage'] - 1])); ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php
                                    $start = max(1, $data['currentPage'] - 2);
                                    $end = min($data['totalPages'], $data['currentPage'] + 2);
                                    for ($i = $start; $i <= $end; $i++):
                                    ?>
                                        <li class="page-item <?php echo $i === $data['currentPage'] ? 'active' : ''; ?>">
                                            <a class="page-link border-0 <?php echo $i === $data['currentPage'] ? 'bg-dark text-white' : 'text-dark'; ?> rounded-circle mx-1" 
                                               style="width: 35px; height: 35px; text-align: center; line-height: 20px;"
                                               href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                        <li class="page-item">
                                            <a class="page-link border-0 text-dark" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $data['currentPage'] + 1])); ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
/* Additional style for this view specifically to handle hover effect that isn't global */
.product-card:hover .product-actions-overlay {
    opacity: 1 !important;
}
</style>
