<!-- Breadcrumb -->
<section class="breadcrumb-section bg-light py-3 border-bottom mb-5">
    <div class="container" style="max-width: 1400px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 small text-uppercase" style="letter-spacing: 1px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thương hiệu</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container pb-5" style="max-width: 1400px;">
    <div class="text-center mb-5">
        <span class="text-warning text-uppercase letter-spacing-2 small fw-bold">Partners</span>
        <h1 class="display-4 fw-bold mt-2 text-uppercase" style="font-family: var(--font-heading);">Thương hiệu đối tác</h1>
        <p class="text-muted lead mx-auto mt-3" style="max-width: 600px;">
            Chúng tôi tự hào là đại lý ủy quyền chính thức của các thương hiệu đồng hồ danh tiếng trên thế giới.
        </p>
    </div>

    <?php if (empty($data['brands'])): ?>
        <div class="text-center py-5">
            <p class="text-muted">Chưa có thương hiệu nào.</p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
            <?php foreach ($data['brands'] as $brand): ?>
                <div class="col">
                    <div class="brand-card text-center p-4 bg-light h-100 hover-shadow transition-all d-flex flex-column align-items-center justify-content-center group-hover-parent position-relative">
                        
                        <!-- Logo -->
                        <div class="mb-4 d-flex align-items-center justify-content-center" style="height: 100px; width: 100%;">
                            <a href="<?php echo BASE_URL; ?>/products?brand=<?php echo $brand['id']; ?>" class="d-block w-100 h-100 d-flex align-items-center justify-content-center text-decoration-none">
                                <?php if ($brand['logo_url']): ?>
                                    <img src="<?php echo htmlspecialchars($brand['logo_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                         class="img-fluid" style="max-height: 80px; transition: all 0.3s;">
                                <?php else: ?>
                                    <h3 class="text-uppercase text-muted fw-bold mb-0" style="letter-spacing: 2px;">
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </h3>
                                <?php endif; ?>
                            </a>
                        </div>

                        <h3 class="h5 text-uppercase fw-bold mb-3">
                            <a href="<?php echo BASE_URL; ?>/products?brand=<?php echo $brand['id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($brand['name']); ?>
                            </a>
                        </h3>
                        
                        <p class="text-muted small mb-4 line-clamp-3">
                            <?php echo $brand['description'] ? html_entity_decode(htmlspecialchars_decode($brand['description'])) : 'Thương hiệu đồng hồ nổi tiếng với thiết kế sang trọng và bộ máy chính xác.'; ?>
                        </p>

                        <a href="<?php echo BASE_URL; ?>/products?brand=<?php echo $brand['id']; ?>" class="btn btn-outline-dark rounded-0 text-uppercase letter-spacing-1 px-4 mt-auto">
                            Xem sản phẩm
                        </a>

                        <!-- Hover Effect CSS in-line for specific behavior -->
                        <style>
                            .brand-card:hover img {
                                transform: scale(1.1);
                            }
                            .line-clamp-3 {
                                display: -webkit-box;
                                -webkit-line-clamp: 3;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                            }
                        </style>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- CTA -->
<section class="py-5 mt-5 bg-dark text-white text-center">
    <div class="container">
        <h2 class="text-uppercase mb-3 text-white " style="font-family: var(--font-heading);">Không tìm thấy thương hiệu yêu thích?</h2>
        <p class="text-white-50 mb-4">Liên hệ với chúng tôi để được tư vấn đặt hàng riêng.</p>
        <a href="<?php echo BASE_URL; ?>/about" class="btn btn-warning rounded-0 px-4 text-dark fw-bold text-uppercase">Liên hệ ngay</a>
    </div>
</section>
