<?php
// Note: Global styles are inherited from layouts/client.php
// (Cinzel, Montserrat, Black/Gold theme)
?>

<!-- Hero Section -->
<section class="hero-section position-relative d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 85vh;">
    <!-- Background with Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at center, #2c2c2c 0%, #000000 100%); z-index: -2;"></div>
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?q=80&w=2574&auto=format&fit=crop') no-repeat center center/cover; opacity: 0.3; z-index: -1;"></div>
    
    <div class="container text-center position-relative z-index-1">
        <div class="animate-fade-up">
            <span class="d-inline-block text-uppercase letter-spacing-4 text-warning mb-3 small fw-bold">
                <i class="fas fa-crown me-2"></i>Vẻ đẹp vượt thời gian
            </span>
            <h1 class="display-1 fw-bold text-white mb-4 text-uppercase" style="font-family: var(--font-heading); letter-spacing: 5px; text-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                Luxury <br> <span style="color: var(--secondary-color);">Watches Store</span>
            </h1>
            <p class="lead text-light opacity-75 mb-5 mx-auto" style="max-width: 600px; font-weight: 300; letter-spacing: 1px;">
                Khám phá bộ sưu tập đồng hồ đẳng cấp, nơi sự tinh tế gặp gỡ kỹ thuật chế tác đỉnh cao.
            </p>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="<?php echo BASE_URL; ?>/products" class="btn btn-luxury-primary px-5 py-3 text-uppercase letter-spacing-2 fw-bold">
                    Khám phá ngay
                </a>
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="<?php echo BASE_URL; ?>/auth/register" class="btn btn-outline-light px-5 py-3 text-uppercase letter-spacing-2 fw-bold">
                        Đăng ký
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-5 animate-bounce">
        <a href="#featured" class="text-white opacity-50 text-decoration-none d-flex flex-column align-items-center text-uppercase x-small letter-spacing-2">
            <span>Scroll</span>
            <i class="fas fa-chevron-down mt-2"></i>
        </a>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white border-bottom">
    <div class="container py-4">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="p-4 border border-success-subtle h-100 hover-shadow transition-all">
                    <i class="fas fa-shipping-fast fa-2x text-warning mb-3"></i>
                    <h5 class="text-uppercase letter-spacing-1 fs-6 fw-bold">Miễn phí vận chuyển</h5>
                    <p class="text-muted small mb-0">Cho đơn hàng trên 5 triệu</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border border-success-subtle h-100 hover-shadow transition-all">
                    <i class="fas fa-shield-alt fa-2x text-warning mb-3"></i>
                    <h5 class="text-uppercase letter-spacing-1 fs-6 fw-bold">Bảo hành chính hãng</h5>
                    <p class="text-muted small mb-0">Cam kết 100% Authentic</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border border-success-subtle h-100 hover-shadow transition-all">
                    <i class="fas fa-undo fa-2x text-warning mb-3"></i>
                    <h5 class="text-uppercase letter-spacing-1 fs-6 fw-bold">Đổi trả linh hoạt</h5>
                    <p class="text-muted small mb-0">Trong vòng 30 ngày</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-4 border border-success-subtle h-100 hover-shadow transition-all">
                    <i class="fas fa-gem fa-2x text-warning mb-3"></i>
                    <h5 class="text-uppercase letter-spacing-1 fs-6 fw-bold">Chất lượng cao cấp</h5>
                    <p class="text-muted small mb-0">Kiểm định nghiêm ngặt</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($data['featuredProducts'])): ?>
<section id="featured" class="section py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="text-warning text-uppercase letter-spacing-2 small fw-bold">Best Sellers</span>
            <h2 class="display-5 text-uppercase fw-bold mt-2" style="font-family: var(--font-heading);">Sản phẩm nổi bật</h2>
            <div class="mx-auto mt-3" style="width: 50px; height: 3px; background-color: var(--secondary-color);"></div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($data['featuredProducts'] as $product): ?>
                <div class="col">
                    <div class="product-card h-100 bg-white group-hover-parent">
                        <div class="position-relative overflow-hidden mb-3">
                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>">
                                <?php if ($product['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         class="w-100 object-fit-cover transition-transform duration-500"
                                         style="height: 350px;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 350px;">
                                        <i class="far fa-clock fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </a>
                            
                            <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                                <div class="position-absolute top-0 start-0 m-3 badge bg-dark text-white rounded-0 fw-normal small">
                                    -<?php echo round((1 - $product['price'] / $product['old_price']) * 100); ?>%
                                </div>
                            <?php endif; ?>

                            <!-- Hover Overlay -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-white bg-opacity-90 translate-y-100 transition-transform group-hover-visible d-flex justify-content-center gap-2">
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="btn btn-outline-dark rounded-circle" style="width: 40px; height: 40px; padding: 0; line-height: 38px;">
                                    <i class="far fa-eye"></i>
                                </a>
                                <form action="<?php echo BASE_URL; ?>/cart/add" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn btn-dark rounded-circle" style="width: 40px; height: 40px; padding: 0; line-height: 38px;">
                                        <i class="fas fa-shopping-bag"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="text-center px-3 pb-4">
                            <div class="text-uppercase text-muted x-small letter-spacing-1 mb-2">
                                <?php echo htmlspecialchars($product['category_name'] ?? ''); ?>
                            </div>
                            <h5 class="h6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            <div class="fw-bold text-dark">
                                <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                                <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                                    <span class="text-decoration-line-through text-muted small ms-2 fw-normal">
                                        <?php echo number_format($product['old_price'], 0, ',', '.'); ?>đ
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-5">
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-outline-dark rounded-0 px-5 py-3 text-uppercase letter-spacing-2 fw-bold">
                Xem tất cả
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Categories Parallax -->
<?php if (!empty($data['categories'])): ?>
<section class="py-5 bg-dark position-relative overflow-hidden text-white">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('https://images.unsplash.com/photo-1547996668-cb540af087a8?q=80&w=2574&auto=format&fit=crop') fixed center center/cover; opacity: 0.2;"></div>
    <div class="container py-5 position-relative z-index-1">
        <div class="text-center mb-5">
            <h2 class="display-5 text-uppercase fw-bold text-white" style="font-family: var(--font-heading);">Bộ sưu tập</h2>
            <p class="text-white-50">Phong cách định hình cá tính của bạn</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach (array_slice($data['categories'], 0, 3) as $category): ?>
                <div class="col-md-4">
                    <a href="<?php echo BASE_URL; ?>/products?category=<?php echo $category['id']; ?>" class="card bg-transparent border border-secondary h-100 text-decoration-none group-hover-parent">
                        <div class="card-body py-5 text-center">
                            <h3 class="text-white text-uppercase letter-spacing-2 mb-3"><?php echo htmlspecialchars($category['name']); ?></h3>
                            <span class="text-warning text-uppercase small letter-spacing-1 group-hover-visible">
                                Khám phá <i class="fas fa-arrow-right ms-2"></i>
                            </span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Products -->
<?php if (!empty($data['latestProducts'])): ?>
<section class="py-5">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="text-warning text-uppercase letter-spacing-2 small fw-bold">New Arrivals</span>
                <h2 class="h1 text-uppercase fw-bold mt-2" style="font-family: var(--font-heading);">Mới nhất</h2>
            </div>
            <a href="<?php echo BASE_URL; ?>/products?sort=latest" class="text-dark text-decoration-none text-uppercase letter-spacing-1 fw-bold">
                Xem thêm <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($data['latestProducts'] as $product): ?>
                <div class="col">
                    <div class="product-card h-100 bg-white group-hover-parent">
                        <div class="position-relative overflow-hidden mb-3">
                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>">
                                <?php if ($product['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="" class="w-100 object-fit-cover" style="height: 350px;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 350px;"><i class="fas fa-clock fa-3x text-muted"></i></div>
                                <?php endif; ?>
                            </a>
                            <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark rounded-0 px-3 fw-bold">NEW</span>
                        </div>
                        <div class="px-2">
                            <h5 class="h6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            <div class="fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Brands -->
<?php if (!empty($data['brands'])): ?>
<section class="py-5 border-top">
    <div class="container">
        <div class="row align-items-center justify-content-center opacity-50 grayscale-hover-color g-5">
            <?php foreach (array_slice($data['brands'], 0, 5) as $brand): ?>
                <div class="col-6 col-md">
                    <div class="text-center">
                        <?php if ($brand['logo_url']): ?>
                            <img src="<?php echo htmlspecialchars($brand['logo_url']); ?>" alt="" style="max-height: 50px; filter: grayscale(100%); opacity: 0.7;">
                        <?php else: ?>
                            <h4 class="text-uppercase text-muted fw-bold mb-0" style="letter-spacing: 2px;">
                                <?php echo htmlspecialchars($brand['name']); ?>
                            </h4>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter CTA -->
<section class="py-5 bg-dark text-white text-center">
    <div class="container py-4">
        <h2 class="display-6 fw-bold text-uppercase mb-3" style="font-family: var(--font-heading);">Đăng ký nhận tin</h2>
        <p class="text-white-50 mb-4">Nhận thông báo về các bộ sưu tập mới và ưu đãi độc quyền.</p>
        <form class="mx-auto" style="max-width: 500px;">
            <div class="input-group">
                <input type="email" class="form-control rounded-0 border-0 p-3" placeholder="Email của bạn...">
                <button class="btn btn-warning rounded-0 px-4 text-dark fw-bold text-uppercase" type="button">Đăng ký</button>
            </div>
        </form>
    </div>
</section>

<style>
/* Local overrides for Home */
.btn-luxury-primary {
    background-color: var(--secondary-color);
    color: #fff;
    border: none;
    transition: all 0.3s ease;
}
.btn-luxury-primary:hover {
    background-color: #fff;
    color: #000;
}
.group-hover-parent:hover .group-hover-visible {
    opacity: 1 !important;
    visibility: visible !important;
}
.group-hover-parent:hover .translate-y-100 {
    transform: translateY(0) !important;
}
.hover-shadow:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-color: transparent !important;
}
.transition-all {
    transition: all 0.3s ease;
}
</style>
