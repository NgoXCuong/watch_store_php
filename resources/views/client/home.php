<?php
// Note: Global styles are inherited from layouts/client.php
// (Cinzel, Montserrat, Black/Gold theme)
?>

<!-- Hero Section -->
<!-- Hero Section -->
<section class="hero-section position-relative d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 85vh;">
    <!-- Background Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade position-absolute top-0 start-0 w-100 h-100" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <div class="carousel-item active h-100">
                <img src="<?php echo BASE_URL; ?>/assets/img/banner-1.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Banner 1">
            </div>
            <div class="carousel-item h-100">
                <img src="<?php echo BASE_URL; ?>/assets/img/banner-2.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Banner 2">
            </div>
            <div class="carousel-item h-100">
                <img src="<?php echo BASE_URL; ?>/assets/img/banner.avif" class="d-block w-100 h-100 object-fit-cover" alt="Banner 3">
            </div>
        </div>
    </div>

    <!-- Dark Overlay for Readability -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: radial-gradient(circle at center, rgba(44,44,44,0.4) 0%, rgba(0,0,0,0.8) 100%); z-index: 1;"></div>
    
    <!-- Content -->
    <div class="container text-center position-relative" style="z-index: 2;">
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
    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-5 animate-bounce" style="z-index: 2;">
        <a href="#featured" class="text-white opacity-50 text-decoration-none d-flex flex-column align-items-center text-uppercase x-small letter-spacing-2">
            <span>Scroll</span>
            <i class="fas fa-chevron-down mt-2"></i>
        </a>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white border-bottom reveal">
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
<section id="featured" class="section py-5 reveal">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="text-warning text-uppercase letter-spacing-2 small fw-bold">Best Sellers</span>
            <h2 class="display-5 text-uppercase fw-bold mt-2" style="font-family: var(--font-heading);">Sản phẩm nổi bật</h2>
            <div class="mx-auto mt-3" style="width: 50px; height: 3px; background-color: var(--secondary-color);"></div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach (array_slice($data['featuredProducts'], 0, 4) as $product): ?>
                <div class="col">
                    <div class="product-card h-100 border bg-white group-hover-parent">
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
                                <div class="position-absolute top-0 start-0 m-3 badge bg-danger text-white rounded-0 fw-normal small">
                                    -<?php echo round((1 - $product['price'] / $product['old_price']) * 100); ?>%
                                </div>
                            <?php endif; ?>

                            <!-- Hover Overlay -->
                            <!-- Quick Actions Overlay -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex gap-2 justify-content-center opacity-0 product-actions-overlay" style="transition: all 0.3s; transform: translateY(20px);">
                                <!-- View Details -->
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="btn btn-light bg-white text-dark btn-action shadow-sm" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Add to Wishlist -->
                                <button type="button" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" class="btn btn-light bg-white text-muted btn-action shadow-sm" title="Yêu thích">
                                    <i class="far fa-heart"></i>
                                </button>

                                <!-- Add to Cart -->
                                <?php if (isset($_SESSION['user'])): ?>
                                    <form action="<?php echo BASE_URL; ?>/cart/add" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-dark btn-action shadow-sm" title="Thêm vào giỏ">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-dark btn-action shadow-sm" title="Thêm vào giỏ">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                <?php endif; ?>
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
                            <div class="text-muted small mt-1">
                                Tồn kho: <?php echo $product['stock']; ?>
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
<section class="py-5 bg-dark position-relative overflow-hidden text-white reveal">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background: url('<?php echo BASE_URL; ?>/assets/img/banner.avif') fixed center center/cover; opacity: 0.2;"></div>
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
                            <span class="text-white text-uppercase small letter-spacing-1 category-hover-text transition-all">
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
<section class="py-5 reveal">
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
            <?php foreach (array_slice($data['latestProducts'], 0, 4) as $product): ?>
                <div class="col">
                    <div class="product-card h-100 border bg-white group-hover-parent">
                        <div class="position-relative overflow-hidden mb-3">
                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>">
                                <?php if ($product['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="" class="w-100 object-fit-cover" style="height: 350px;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 350px;"><i class="fas fa-clock fa-3x text-muted"></i></div>
                                <?php endif; ?>
                            </a>
                            <span class="position-absolute top-0 end-0 m-3 badge bg-warning text-dark rounded-0 px-3 fw-bold">NEW</span>
                            
                            <!-- Quick Actions Overlay -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex gap-2 justify-content-center opacity-0 product-actions-overlay" style="transition: all 0.3s; transform: translateY(20px);">
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="btn btn-light bg-white text-dark btn-action shadow-sm" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" class="btn btn-light bg-white text-muted btn-action shadow-sm" title="Yêu thích">
                                    <i class="far fa-heart"></i>
                                </button>
                                <?php if (isset($_SESSION['user'])): ?>
                                    <form action="<?php echo BASE_URL; ?>/cart/add" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-dark btn-action shadow-sm" title="Thêm vào giỏ"><i class="fas fa-shopping-cart"></i></button>
                                    </form>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-dark btn-action shadow-sm" title="Thêm vào giỏ"><i class="fas fa-shopping-cart"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="px-2">
                            <h5 class="h6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            <div class="fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</div>
                            <div class="text-muted small mt-1">
                                Tồn kho: <?php echo $product['stock']; ?>
                            </div>
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
<section class="py-5 border-top overflow-hidden reveal">
    <div class="container-fluid">
        <div class="position-relative">
            <!-- Gradient Masks -->
            <div class="position-absolute top-0 start-0 h-100 bg-white" style="width: 50px; z-index: 2; mask-image: linear-gradient(to right, black, transparent); -webkit-mask-image: linear-gradient(to right, black, transparent);"></div>
            <div class="position-absolute top-0 end-0 h-100 bg-white" style="width: 50px; z-index: 2; mask-image: linear-gradient(to left, black, transparent); -webkit-mask-image: linear-gradient(to left, black, transparent);"></div>
            
            <div class="brand-track d-flex align-items-center">
                <?php 
                $displayBrands = array_merge($data['brands'], $data['brands'], $data['brands'], $data['brands']); 
                foreach ($displayBrands as $brand): 
                ?>
                    <div class="flex-shrink-0 px-5">
                        <div class="text-center">
                            <a href="<?php echo BASE_URL; ?>/products?brand=<?php echo $brand['id']; ?>" class="d-block text-decoration-none">
                                <?php if ($brand['logo_url']): ?>
                                    <img src="<?php echo htmlspecialchars($brand['logo_url']); ?>" alt="<?php echo htmlspecialchars($brand['name']); ?>" style="height: 60px; object-fit: contain; filter: none; opacity: 1;" class="transition-transform duration-300 hover-scale">
                                <?php else: ?>
                                    <h4 class="text-uppercase text-muted fw-bold mb-0 text-nowrap" style="letter-spacing: 2px;">
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </h4>
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Newsletter CTA -->
<section class="py-5 bg-dark text-white text-center reveal">
    <div class="container py-4">
        <h2 class="display-6 fw-bold  text-white  text-uppercase mb-3" style="font-family: var(--font-heading);">Đăng ký nhận tin</h2>
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
.group-hover-parent:hover .category-hover-text {
    color: var(--secondary-color) !important;
}
/* Marquee Animation */
.brand-track {
    width: fit-content;
    animation: marquee 40s linear infinite;
    will-change: transform;
}
.brand-track:hover {
    animation-play-state: paused;
}
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-25%); } /* Move 1/4th (one full set of brands) */
}
.hover-scale:hover {
    transform: scale(1.1);
}

/* Action Buttons */
.btn-action {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: all 0.3s;
    border: none;
}

.btn-action:hover {
    background-color: var(--secondary-color, #c9a050) !important;
    color: white !important;
    transform: translateY(-3px);
}

.product-card:hover .product-actions-overlay {
    opacity: 1 !important;
    transform: translateY(0) !important;
}
</style>
