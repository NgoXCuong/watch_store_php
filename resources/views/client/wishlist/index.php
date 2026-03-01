<div class="container my-5" style="max-width: 1400px; min-height: 60vh;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/profile" class="text-decoration-none text-muted">Tài khoản</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sản phẩm yêu thích</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm rounded-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="far fa-user fs-4 text-secondary"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Xin chào,</p>
                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></h6>
                        </div>
                    </div>

                    <div class="nav flex-column nav-pills custom-nav-pills">
                        <a href="<?php echo BASE_URL; ?>/profile" class="nav-link text-dark py-3 px-3 border-bottom d-flex align-items-center">
                            <i class="far fa-id-card me-3 text-muted" style="width: 20px;"></i>
                            Thông tin tài khoản
                        </a>
                        <a href="<?php echo BASE_URL; ?>/orders" class="nav-link text-dark py-3 px-3 border-bottom d-flex align-items-center">
                            <i class="fas fa-shopping-bag me-3 text-muted" style="width: 20px;"></i>
                            Đơn hàng của tôi
                        </a>
                        <a href="<?php echo BASE_URL; ?>/wishlist" class="nav-link active bg-warning text-dark fw-bold py-3 px-3 border-bottom d-flex align-items-center">
                            <i class="far fa-heart me-3" style="width: 20px;"></i>
                            Sản phẩm yêu thích
                        </a>
                        <a href="<?php echo BASE_URL; ?>/reviews/my-reviews" class="nav-link text-dark py-3 px-3 border-bottom d-flex align-items-center">
                            <i class="far fa-star me-3 text-muted" style="width: 20px;"></i>
                            Đánh giá của tôi
                        </a>
                        <a href="<?php echo BASE_URL; ?>/auth/logout" class="nav-link text-danger py-3 px-3 d-flex align-items-center">
                            <i class="fas fa-sign-out-alt me-3" style="width: 20px;"></i>
                            Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Wishlist -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-0 h-100">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="fw-bold mb-4 pb-3 border-bottom border-2 border-dark d-inline-block">SẢN PHẨM YÊU THÍCH</h4>

                    <?php if (empty($wishlistItems)): ?>
                        <div class="text-center py-5">
                            <i class="far fa-heart fs-1 text-muted mb-3 opacity-50" style="font-size: 4rem !important;"></i>
                            <h5>Danh sách yêu thích của bạn đang trống!</h5>
                            <p class="text-muted mb-4">Bạn chưa chọn sản phẩm nào vào danh sách yêu thích.</p>
                            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-dark rounded-0 px-4 py-2">
                                <i class="fas fa-shopping-cart me-2"></i>Tiếp tục mua sắm
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="row row-cols-1 row-cols-md-3 g-4">
                            <?php foreach ($wishlistItems as $item): ?>
                                <div class="col">
                                    <div class="card h-100 product-card border-0 shadow-sm text-center p-3 relative" style="transition: all 0.3s;">
                                        <!-- Wishlist Remove Button -->
                                        <a href="<?php echo BASE_URL; ?>/wishlist/remove/<?php echo $item['product_id']; ?>" class="position-absolute top-0 end-0 p-2 text-danger" style="z-index: 10;" title="Xóa khỏi yêu thích" onclick="return confirm('Bạn muốn xóa sản phẩm này khỏi yêu thích?');">
                                            <i class="fas fa-times fs-5"></i>
                                        </a>

                                        <?php if ($item['old_price'] && $item['old_price'] > $item['price']): ?>
                                            <div class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small fw-bold" style="z-index: 10;">
                                                -<?php echo round((($item['old_price'] - $item['price']) / $item['old_price']) * 100); ?>%
                                            </div>
                                        <?php endif; ?>

                                        <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $item['product_id']; ?>" class="d-block mb-3" style="overflow:-hidden; height: 200px; display:flex; align-items:center; justify-content:center;">
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="max-height: 100%; width: auto; object-fit: contain;">
                                        </a>

                                        <div class="card-body p-0 d-flex flex-column">
                                            <!-- Category/Brand -->
                                            <div class="text-secondary small fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">
                                                <?php echo htmlspecialchars($item['brand_name'] ?? $item['category_name'] ?? 'Đồng hồ'); ?>
                                            </div>

                                            <!-- Name -->
                                            <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 1rem;">
                                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $item['product_id']; ?>" class="text-dark text-decoration-none">
                                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                                </a>
                                            </h5>

                                            <!-- Price -->
                                            <div class="mt-auto">
                                                <span class="text-danger fw-bold fs-5 d-block"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</span>
                                                <?php if ($item['old_price'] && $item['old_price'] > $item['price']): ?>
                                                    <small class="text-muted text-decoration-line-through"><?php echo number_format($item['old_price'], 0, ',', '.'); ?>đ</small>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Add to Cart (Direct post here or link to product) -->
                                            <div class="mt-3">
                                                 <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $item['product_id']; ?>" class="btn btn-outline-dark rounded-0 w-100 fw-bold border-2">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom pill nav styles for consistency with profile section */
.custom-nav-pills .nav-link {
    border-radius: 0;
    transition: all 0.3s;
}
.custom-nav-pills .nav-link:hover:not(.active) {
    background-color: #f8f9fa;
    padding-left: 1.5rem !important;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
