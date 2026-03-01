<!-- Breadcrumb -->
<section class="breadcrumb-section bg-light py-3 border-bottom mb-5">
    <div class="container" style="max-width: 1400px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 small " style="letter-spacing: 1px;">
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
                    <!-- Price Range -->
                    <div class="mb-5">
                        <h5 class="letter-spacing-1 mb-4 fs-6 fw-bold text-dark border-bottom pb-2">Khoảng giá</h5>
                        <div class="price-slider-wrapper">
                            <!-- Dual Range Custom Sliders -->
                            <div class="slider-container position-relative mb-4" style="height: 4px; background: #e9ecef; border-radius: 5px; margin-top: 15px;">
                                <div class="slider-track bg-dark position-absolute" style="height: 100%; border-radius: 5px; z-index: 1;"></div>
                                <input type="range" class="position-absolute w-100 p-0 m-0" min="0" max="100000000" step="100000" id="slider-1" value="<?php echo $data['minPrice'] ?? 0; ?>" style="z-index: 2;">
                                <input type="range" class="position-absolute w-100 p-0 m-0" min="0" max="100000000" step="100000" id="slider-2" value="<?php echo $data['maxPrice'] ?? 500000000; ?>" style="z-index: 2;">
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-muted small" id="range1">0đ</span>
                                <span class="fw-bold text-muted small" id="range2">100.000.000đ</span>
                            </div>

                            <button type="button" class="btn btn-dark w-100  small letter-spacing-1" onclick="applyPriceFilter()">Lọc giá</button>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="mb-5">
                        <h5 class="letter-spacing-1 mb-3 fs-6 fw-bold text-dark border-bottom pb-2">Danh mục</h5>
                        <div class="filter-scroll-container">
                            <div class="d-flex flex-column gap-2">
                                <a href="<?php echo BASE_URL; ?>/products" class="text-decoration-none filter-item <?php echo !$data['selectedCategory'] ? 'active' : ''; ?>">
                                    <div class="filter-checkbox"><i class="fas fa-check"></i></div>
                                    <span>Tất cả</span>
                                </a>
                                <?php foreach ($data['categories'] as $category): ?>
                                    <a href="?category=<?php echo $category['id']; ?>&brand=<?php echo $data['selectedBrand']; ?>&sort=<?php echo $data['selectedSort']; ?>" 
                                       class="text-decoration-none filter-item <?php echo $data['selectedCategory'] == $category['id'] ? 'active' : ''; ?>">
                                        <div class="filter-checkbox"><i class="fas fa-check"></i></div>
                                        <span><?php echo htmlspecialchars($category['name']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="mb-5">
                        <h5 class="letter-spacing-1 mb-3 fs-6 fw-bold text-dark border-bottom pb-2">Thương hiệu</h5>
                        <div class="filter-scroll-container">
                            <div class="d-flex flex-column gap-2">
                                <a href="?category=<?php echo $data['selectedCategory']; ?>&sort=<?php echo $data['selectedSort']; ?>" class="text-decoration-none filter-item <?php echo !$data['selectedBrand'] ? 'active' : ''; ?>">
                                    <div class="filter-checkbox"><i class="fas fa-check"></i></div>
                                    <span>Tất cả</span>
                                </a>
                                <?php foreach ($data['brands'] as $brand): ?>
                                    <a href="?brand=<?php echo $brand['id']; ?>&category=<?php echo $data['selectedCategory']; ?>&sort=<?php echo $data['selectedSort']; ?>" 
                                       class="text-decoration-none filter-item <?php echo $data['selectedBrand'] == $brand['id'] ? 'active' : ''; ?>">
                                        <div class="filter-checkbox"><i class="fas fa-check"></i></div>
                                        <span><?php echo htmlspecialchars($brand['name']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <?php if (!empty($data['aiMessage'])): ?>
                    <div class="alert alert-info border-0 rounded-0 shadow-sm d-flex align-items-center gap-3 mb-4" role="alert" style="background-color: #f8f9fa; border-left: 4px solid var(--secondary-color) !important;">
                        <i class="fas fa-magic fa-2x text-warning"></i>
                        <div>
                            <h5 class="alert-heading mb-1 fs-6 fw-bold">Trợ lý AI Tìm kiếm</h5>
                            <p class="mb-0 small text-muted"><?php echo htmlspecialchars($data['aiMessage']); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Toolbar -->
                <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                    <span class="text-muted small letter-spacing-1">
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
                            <select name="sort" class="form-select form-select-sm border-0 bg-transparent text-start fw-bold" 
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
                        <a href="<?php echo BASE_URL; ?>/products" class="btn btn-outline-dark rounded-0 px-4 mt-3 " style="letter-spacing: 1px;">
                            Xóa bộ lọc
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                        <?php foreach ($data['products'] as $product): ?>
                            <div class="col">
                                <div class="product-card border h-100">
                                    <div class="product-image-wrapper">
                                        <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                                            <div class="discount-badge">
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

                                        <!-- Add to Wishlist (Moved to Top Right) -->
                                        <button type="button" onclick="toggleWishlist(<?php echo $product['id']; ?>, this)" class="btn bg-white text-muted shadow-sm position-absolute top-0 end-0 m-2 wishlist-badge-btn" title="Yêu thích" style="z-index: 10;">
                                            <i class="far fa-heart"></i>
                                        </button>

                                        <!-- Quick Actions Overlay -->
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3 d-flex gap-2 justify-content-center opacity-0 product-actions-overlay" style="transition: all 0.3s; transform: translateY(20px);">
                                            <!-- View Details -->
                                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="btn btn-light bg-white text-dark btn-action shadow-sm" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>

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
                                    
                                    <div class="product-info mt-3 text-center">
                                        <div class="product-category text-muted x-small letter-spacing-2 mb-2">
                                            <?php echo htmlspecialchars($product['category_name'] ?? ''); ?>
                                        </div>
                                        <h3 class="h6 mb-2">
                                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($product['name']); ?>
                                            </a>
                                        </h3>
                                        <div class="product-price">
                                            <span class="product-price-highlight">
                                                <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                                            </span>
                                            <?php if ($product['old_price'] && $product['old_price'] > $product['price']): ?>
                                                <br>
                                                <span class="text-decoration-line-through text-muted small">
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
/* Additional style for this view specifically to handle hover effect that isn't global */
.product-card:hover .product-actions-overlay {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

/* Sidebar Custom Scrollbar */
.filter-scroll-container {
    max-height: 250px;
    overflow-y: auto;
    padding-right: 5px; /* Avoid content hide */
}

.filter-scroll-container::-webkit-scrollbar {
    width: 4px;
}

.filter-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1; 
}

.filter-scroll-container::-webkit-scrollbar-thumb {
    background: #ccc; 
    border-radius: 4px;
}

.filter-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #888; 
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

.active .filter-checkbox {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.active .filter-checkbox i {
    opacity: 1;
}

/* Slider Styles */
input[type="range"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    width: 100%;
    outline: none;
    position: absolute;
    margin: 0;
    top: 0;
    bottom: 0;
    background-color: transparent;
    pointer-events: none;
}
.slider-track {
    height: 100%; /* Match parent height */
    position: absolute;
    margin: auto;
    top: 0;
    bottom: 0;
    border-radius: 5px;
}
input[type="range"]::-webkit-slider-runnable-track {
    -webkit-appearance: none;
    height: 5px; /* Same as container height */
    background: transparent; /* Fix for double track */
    border: none; /* remove default border */
}
input[type="range"]::-moz-range-track {
    -moz-appearance: none;
    height: 5px;
    background: transparent;
    border: none;
}
input[type="range"]::-ms-track {
    appearance: none;
    height: 5px;
    background: transparent;
    border: none;
    color: transparent;
}
input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    height: 20px;
    width: 20px;
    background-color: #fff;
    cursor: pointer;
    margin-top: -8px; 
    pointer-events: auto;
    border-radius: 50%;
    border: 3px solid #000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    transition: transform 0.2s;
}
input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.1);
}
input[type="range"]::-webkit-slider-thumb:active {
    transform: scale(0.95);
}
input[type="range"]::-moz-range-thumb {
    -webkit-appearance: none;
    height: 20px;
    width: 20px;
    cursor: pointer;
    border-radius: 50%;
    background-color: #fff;
    pointer-events: auto;
    border: 3px solid #000;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    transition: transform 0.2s;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const slider1 = document.getElementById("slider-1");
    const slider2 = document.getElementById("slider-2");
    const display1 = document.getElementById("range1");
    const display2 = document.getElementById("range2");
    const track = document.querySelector(".slider-track");
    const minGap = 1000000; // 1 million gap

    function slideOne() {
        if(parseInt(slider2.value) - parseInt(slider1.value) <= minGap){
            slider1.value = parseInt(slider2.value) - minGap;
        }
        updateDisplay();
    }

    function slideTwo() {
        if(parseInt(slider2.value) - parseInt(slider1.value) <= minGap){
            slider2.value = parseInt(slider1.value) + minGap;
        }
        updateDisplay();
    }

    function updateDisplay() {
        display1.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(slider1.value);
        display2.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(slider2.value);
        fillColor();
    }

    function fillColor() {
        const percent1 = (slider1.value / slider1.max) * 100;
        const percent2 = (slider2.value / slider2.max) * 100;
        track.style.left = percent1 + "%";
        track.style.right = (100 - percent2) + "%";
    }

    if(slider1 && slider2) {
        slider1.addEventListener("input", slideOne);
        slider2.addEventListener("input", slideTwo);
        
        // Init
        slideOne();
        slideTwo();
    }
});

function applyPriceFilter() {
    const slider1 = document.getElementById("slider-1");
    const slider2 = document.getElementById("slider-2");
    
    const min = Math.min(slider1.value, slider2.value);
    const max = Math.max(slider1.value, slider2.value);
    
    // Update URL params
    const url = new URL(window.location.href);
    url.searchParams.set("min_price", min);
    url.searchParams.set("max_price", max);
    url.searchParams.set("page", 1); // Reset page
    
    window.location.href = url.toString();
}
</script>
