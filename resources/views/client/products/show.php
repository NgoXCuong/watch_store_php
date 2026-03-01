<!-- Breadcrumb -->
<section class="breadcrumb-section bg-light py-3 border-bottom mb-5">
    <div class="container" style="max-width: 1400px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 small " style="letter-spacing: 1px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/products" class="text-decoration-none text-muted">Bộ sưu tập</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($data['product']['name']); ?></li>
            </ol>
        </nav>
    </div>
</section>


<!-- Custom Tab Styling -->
<!-- Custom Styling -->
<style>
    /* Sticky Tabs */
    /* Sticky Tabs - REMOVED sticky behavior */
    .sticky-tabs {
        /* position: sticky; */
        /* top: 80px; */
        /* z-index: 100; */
        /* background: rgba(255, 255, 255, 0.95); */
        /* backdrop-filter: blur(10px); */
        /* box-shadow: 0 5px 20px rgba(0,0,0,0.05); */
    }
    
    .nav-tabs .nav-link {
        color: #999;
        border: none !important;
        border-bottom: 3px solid transparent !important;
        transition: all 0.3s;
        padding: 1.5rem 0;
        font-size: 0.9rem;
    }
    
    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
    }
    
    .nav-tabs .nav-link.active {
        color: var(--secondary-color) !important;
        border-bottom: 3px solid var(--secondary-color) !important;
        background: transparent !important;
    }

    /* Description Typography */
    .typography-content h2, 
    .typography-content h3 {
        font-family: var(--font-heading);
        color: var(--primary-color);
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .typography-content p {
        margin-bottom: 1.5rem;
        color: #555;
    }

    .typography-content ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 2rem;
    }

    .typography-content ul li {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 0.8rem;
        color: #555;
    }

    .typography-content ul li::before {
        content: '\f00c'; /* FontAwesome Check */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        top: 2px;
        color: var(--secondary-color);
    }
    
    .typography-content b, 
    .typography-content strong {
        color: var(--primary-color);
        font-weight: 700;
    }

    .typography-content img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin: 2rem 0;
        box-shadow: var(--shadow-soft);
    }

    /* Specs Grid */
    .specs-group-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 0.5rem;
        display: inline-block;
    }

    .spec-item {
        margin-bottom: 0;
        padding: 12px 15px;
        display: flex;
        align-items: baseline;
        border-bottom: 1px solid #f5f5f5;
        font-size: 0.95rem;
    }

    .spec-item:nth-of-type(odd) {
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    .spec-item:last-child {
        border-bottom: none;
    }

    .spec-label {
        width: 40%;
        color: #999;
        font-size: 0.9rem;
    }

    .spec-value {
        width: 60%;
        color: var(--primary-color);
        font-weight: 500;
    }

    /* Review Summary */
    .review-summary-box {
        background-color: #f8f9fa;
        padding: 2rem;
        border-radius: 8px;
    }

    .rating-big {
        font-size: 3.5rem;
        font-weight: 700;
        line-height: 1;
        color: var(--primary-color);
    }

    .progress {
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        background-color: var(--secondary-color);
    }
    
    .avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }
</style>

<?php
$gallery = [];
// Add main image first
if (!empty($data['product']['image_url'])) {
    $gallery[] = $data['product']['image_url'];
}

// Add gallery images
if (!empty($data['product']['gallery_urls'])) {
    $decoded = json_decode($data['product']['gallery_urls'], true);
    if (is_array($decoded)) {
        $gallery = array_merge($gallery, $decoded);
    }
}

// Remove duplicates just in case
$gallery = array_unique($gallery);
?>

<!-- Product Detail -->
<section class="product-detail-section mb-5">
    <div class="container" style="max-width: 1400px;">
        <div class="row gx-lg-5">
            <!-- Product Images -->
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="position-relative bg-light mb-3" style="min-height: 500px;">
                    <img src="<?php echo htmlspecialchars($data['product']['image_url'] ?? ''); ?>"
                         alt="<?php echo htmlspecialchars($data['product']['name']); ?>"
                         class="img-fluid w-100 h-100 object-fit-cover" id="main-image">
                    
                    <?php if (isset($gallery) && count($gallery) > 1): ?>
                        <button class="btn btn-dark position-absolute top-50 start-0 translate-middle-y rounded-0 ms-2 d-flex align-items-center justify-content-center" 
                                onclick="prevImage()" style="width: 40px; height: 40px; opacity: 0.8; z-index: 10; border: none;">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-dark position-absolute top-50 end-0 translate-middle-y rounded-0 me-2 d-flex align-items-center justify-content-center" 
                                onclick="nextImage()" style="width: 40px; height: 40px; opacity: 0.8; z-index: 10; border: none;">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?>
                    
                    <?php if ($data['product']['stock'] == 0): ?>
                        <div class="position-absolute top-50 start-50 translate-middle badge bg-dark text-white fs-5 rounded-0 px-4 py-3 ">
                            Hết hàng
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Thumbnail Grid (Placeholder for multi-image logic if added later) -->
                <!-- Gallery Thumbnails -->
                <!-- Gallery Thumbnails -->

                <?php if (count($gallery) > 1): ?>
                <div class="d-flex gap-2 overflow-auto pb-2 custom-scrollbar">
                    <?php foreach ($gallery as $index => $imgUrl): ?>
                        <div class="thumbnail-item border <?php echo $index === 0 ? 'border-primary' : 'border-light'; ?> p-1" 
                             style="width: 80px; height: 80px; min-width: 80px; cursor: pointer; transition: all 0.3s;"
                             onclick="setMainImage(<?php echo $index; ?>)">
                            <img src="<?php echo htmlspecialchars($imgUrl); ?>" class="w-100 h-100 object-fit-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Script moved to bottom -->
            </div>

            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info ps-lg-4">
                    <div class="text-muted letter-spacing-2 mb-2 small">
                        <?php echo htmlspecialchars($data['product']['brand_name'] ?? 'Thương hiệu'); ?>
                    </div>
                    
                    <h1 class="display-7 fw-bold mb-3" style="font-family: var(--font-heading);"><?php echo htmlspecialchars($data['product']['name']); ?></h1>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <?php
                        $avgRating = 0;
                        $totalReviews = count($data['reviews'] ?? []);
                        if ($totalReviews > 0) {
                            $sum = 0;
                            foreach ($data['reviews'] as $r) $sum += $r['rating'];
                            $avgRating = round($sum / $totalReviews, 1);
                        }
                        ?>
                        <div class="text-warning small">
                            <?php
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $avgRating) {
                                    echo '<i class="fas fa-star"></i>';
                                } elseif ($i - 0.5 <= $avgRating) {
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <span class="text-muted small border-start ps-3"><?php echo $totalReviews; ?> Đánh giá</span>
                        <span class="text-muted small border-start ps-3">Mã: #<?php echo $data['product']['id']; ?></span>
                        <span class="text-muted small border-start ps-3">Tồn kho: <?php echo $data['product']['stock']; ?></span>
                    </div>

                    <div class="mb-4">
                        <span class="h2 fw-bold text-dark">
                            <?php echo number_format($data['product']['price'], 0, ',', '.'); ?>đ
                        </span>
                        <?php if ($data['product']['old_price'] && $data['product']['old_price'] > $data['product']['price']): ?>
                            <span class="text-decoration-line-through text-muted ms-2 fs-5">
                                <?php echo number_format($data['product']['old_price'], 0, ',', '.'); ?>đ
                            </span>
                        <?php endif; ?>
                    </div>

                    

                    <?php if ($data['product']['stock'] > 0): ?>
                        <form action="<?php echo BASE_URL; ?>/cart/add" method="POST" class="mb-5">
                            <input type="hidden" name="product_id" value="<?php echo $data['product']['id']; ?>">
                            
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="quantity-input-group border d-flex align-items-center h-100">
                                        <button type="button" class="btn border-0 rounded-0 px-2" onclick="changeQuantity(-1)">-</button>
                                        <input type="number" class="form-control border-0 text-center shadow-none p-0 fw-bold" 
                                               name="quantity" value="1" min="1" max="<?php echo $data['product']['stock']; ?>" id="quantity-input">
                                        <button type="button" class="btn border-0 rounded-0 px-2" onclick="changeQuantity(1)">+</button>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button type="button" class="btn btn-outline-dark w-100 h-100 rounded-0 d-flex align-items-center justify-content-center" onclick="toggleWishlist(<?php echo $data['product']['id']; ?>, this)" title="Yêu thích">
                                        <i class="far fa-heart fs-5"></i>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <?php if (isset($_SESSION['user'])): ?>
                                        <button type="submit" class="btn btn-dark w-100 h-100 rounded-0  letter-spacing-2 fw-bold d-flex align-items-center justify-content-center gap-2" style="font-size: 0.9rem;">
                                            <i class="fas fa-shopping-bag"></i> Thêm vào giỏ
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-dark w-100 h-100 rounded-0 letter-spacing-2 fw-bold d-flex align-items-center justify-content-center" style="font-size: 0.9rem;">
                                            Đăng nhập để mua
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-secondary rounded-0 mb-4">
                            Sản phẩm này hiện đang hết hàng. Vui lòng quay lại sau.
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-4 text-small letter-spacing-1 border-top pt-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-truck text-muted"></i> Miễn phí vận chuyển
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-shield-alt text-muted"></i> Bảo hành 2 năm
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-undo text-muted"></i> Đổi trả 30 ngày
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Details & Reviews Tabs -->
<section class="bg-light py-5">
    <div class="container" style="max-width: 1300px;">
        <ul class="nav nav-tabs border-0 justify-content-center mb-5 gap-4 sticky-tabs" id="productTabs" role="tablist">
            <li class="nav-item border-0" role="presentation">
                <button class="nav-link active bg-transparent border-0 letter-spacing-2 fw-bold rounded-0 pb-2 px-0" 
                        id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                    Mô tả chi tiết
                </button>
            </li>
            <li class="nav-item border-0" role="presentation">
                <button class="nav-link bg-transparent border-0 text-muted letter-spacing-2 fw-bold rounded-0 pb-2 px-0" 
                        id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                    Thông số
                </button>
            </li>
            <li class="nav-item border-0" role="presentation">
                <button class="nav-link bg-transparent border-0 text-muted letter-spacing-2 fw-bold rounded-0 pb-2 px-0" 
                        id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    Đánh giá (<?php echo count($data['reviews'] ?? []); ?>)
                </button>
            </li>
        </ul>

        <div class="tab-content bg-white p-5 shadow-sm" id="productTabsContent">
            <!-- Description -->
            <div class="tab-pane fade show active" id="description" role="tabpanel">
                <div class="typography-content" style="line-height: 1.8;">
                    <?php if ($data['product']['description']): ?>
                        <?php echo html_entity_decode(htmlspecialchars_decode($data['product']['description'])); ?>
                    <?php else: ?>
                        <p class="text-muted text-center fst-italic">Đang cập nhật nội dung...</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Specs -->
            <div class="tab-pane fade" id="specifications" role="tabpanel">
                <?php
                // Process Specs Grouping
                $specs = [];
                if (!empty($data['product']['specifications'])) {
                    $specs = json_decode($data['product']['specifications'], true) ?? [];
                }

                $specMap = [
                    'brand' => 'Thương hiệu', 'origin' => 'Xuất xứ', 'target' => 'Đối tượng', 'gender' => 'Giới tính', 'line' => 'Dòng sản phẩm',
                    'water_resistance' => 'Chống nước', 'water_resist' => 'Chống nước', 'dial_type' => 'Loại mặt số',
                    'movement' => 'Loại máy', 'engine' => 'Động cơ',
                    'crystal_material' => 'Chất liệu kính', 'glass_material' => 'Chất liệu kính', 'glass' => 'Kính',
                    'case_material' => 'Chất liệu vỏ', 'band_material' => 'Chất liệu dây', 'strap_material' => 'Chất liệu dây',
                    'band_width' => 'Độ rộng dây', 'clasp' => 'Kiểu khóa',
                    'case_size' => 'Size mặt', 'case_thickness' => 'Độ dày', 'thickness' => 'Độ dày',
                    'face_color' => 'Màu mặt', 'dial_color' => 'Màu mặt',
                    'case_diameter' => 'Đường kính mặt', 'diameter' => 'Đường kính', 'case_color' => 'Màu vỏ',
                    'face_shape' => 'Hình dáng mặt', 'shape' => 'Hình dáng',
                    'collection' => 'Bộ sưu tập', 'features' => 'Tính năng', 'function' => 'Chức năng', 'warranty' => 'Bảo hành'
                ];

                // Group Definitions
                $groups = [
                    'general' => ['title' => 'Thông tin chung', 'keys' => ['brand', 'origin', 'gender', 'target', 'collection', 'line', 'warranty']],
                    'movement' => ['title' => 'Bộ máy & Năng lượng', 'keys' => ['movement', 'engine', 'function', 'features', 'water_resistance', 'water_resist']],
                    'appearance' => ['title' => 'Ngoại hình & Chất liệu', 'keys' => ['dial_type', 'case_size', 'case_diameter', 'diameter', 'case_thickness', 'thickness', 'face_shape', 'shape', 'face_color', 'dial_color', 'glass', 'glass_material', 'crystal_material', 'case_material', 'case_color', 'band_material', 'strap_material', 'band_width', 'clasp']]
                ];

                // Assign raw specs to groups
                $groupedSpecs = [];
                $usedKeys = [];

                foreach ($groups as $groupKey => $groupDef) {
                    $groupData = [];
                    foreach ($groupDef['keys'] as $key) {
                        if (isset($specs[$key]) && !empty($specs[$key])) {
                            $groupData[$key] = $specs[$key];
                            $usedKeys[$key] = true;
                        }
                    }
                    if (!empty($groupData)) {
                        $groupedSpecs[$groupKey] = [
                            'title' => $groupDef['title'],
                            'data' => $groupData
                        ];
                    }
                }

                // Collect remaining specs
                $others = [];
                foreach ($specs as $key => $value) {
                    if (!isset($usedKeys[$key]) && !empty($value)) {
                        $others[$key] = $value;
                    }
                }
                if (!empty($others)) {
                    $groupedSpecs['others'] = [
                        'title' => 'Thông số khác',
                        'data' => $others
                    ];
                }
                
                // Always add static info if missing
                // Always add static info if missing
                if (!isset($usedKeys['brand'])) {
                    if (!isset($groupedSpecs['general'])) {
                        $groupedSpecs['general'] = [
                            'title' => 'Thông tin chung',
                            'data' => []
                        ];
                    }
                    $groupedSpecs['general']['data']['brand'] = $data['product']['brand_name'] ?? 'N/A';
                }
                ?>

                <div class="row g-5">
                    <?php if (empty($specs)): ?>
                         <div class="col-12 text-center text-muted py-5">Đang cập nhật thông số...</div>
                    <?php else: ?>
                        <?php foreach ($groupedSpecs as $group): ?>
                            <div class="col-md-6">
                                <h4 class="specs-group-title"><?php echo $group['title']; ?></h4>
                                <?php foreach ($group['data'] as $key => $value): ?>
                                    <div class="spec-item">
                                        <div class="spec-label">
                                            <?php echo htmlspecialchars($specMap[$key] ?? ucfirst(str_replace('_', ' ', $key))); ?>
                                        </div>
                                        <div class="spec-value">
                                            <?php 
                                            echo htmlspecialchars($value); 
                                            // Add visual bar for water resistance
                                            if (in_array($key, ['water_resistance', 'water_resist'])) {
                                                echo '<div class="progress mt-2" style="height: 4px; width: 100px; opacity: 0.5;">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
                                                      </div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reviews -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="row mb-5 align-items-center">
                    <div class="col-md-5">
                        <div class="review-summary-box text-center">
                            <div class="rating-big mb-2"><?php echo $avgRating > 0 ? $avgRating : '0.0'; ?></div>
                            <div class="text-warning mb-2 fs-5">
                                <?php
                                for ($i = 1; $i <= 5; $i++) echo $i <= $avgRating ? '<i class="fas fa-star"></i>' : ($i - 0.5 <= $avgRating ? '<i class="fas fa-star-half-alt"></i>' : '<i class="far fa-star"></i>');
                                ?>
                            </div>
                            <div class="text-muted small"><?php echo count($data['reviews'] ?? []); ?> đánh giá</div>
                        </div>
                    </div>
                    <div class="col-md-7 mt-4 mt-md-0">
                        <?php
                        $starCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                        foreach ($data['reviews'] ?? [] as $r) {
                            if (isset($starCounts[$r['rating']])) $starCounts[$r['rating']]++;
                        }
                        $total = max(count($data['reviews'] ?? []), 1); // Avoid div by zero
                        ?>
                        <div class="d-flex flex-column gap-2 px-md-4">
                            <?php for ($star = 5; $star >= 1; $star--): ?>
                                <?php $percent = ($starCounts[$star] / $total) * 100; ?>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-muted small fw-bold" style="width: 30px;"><?php echo $star; ?> <i class="fas fa-star text-warning"></i></div>
                                    <div class="progress flex-grow-1" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar" style="width: <?php echo $percent; ?>%"></div>
                                    </div>
                                    <div class="text-muted small text-end" style="width: 30px;"><?php echo $starCounts[$star]; ?></div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        
                        <div class="mt-4 text-center text-md-start px-md-4">
                            <?php if (isset($_SESSION['user']) && $data['canReview']): ?>
                                <button class="btn btn-dark rounded-0 px-4 py-2  letter-spacing-1" onclick="showReviewForm()">Viết đánh giá</button>
                            <?php elseif (!isset($_SESSION['user'])): ?>
                                <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-outline-dark rounded-0 px-4">Đăng nhập để đánh giá</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Review Form (Hidden by default) -->
                <div id="reviewFormContainer" class="mb-5 bg-light p-4 d-none">
                    <form id="reviewForm" action="<?php echo BASE_URL; ?>/reviews/store" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $data['product']['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Đánh giá của bạn:</label>
                            <div class="stars fs-4" style="cursor: pointer;">
                                <i class="far fa-star" data-rating="1"></i>
                                <i class="far fa-star" data-rating="2"></i>
                                <i class="far fa-star" data-rating="3"></i>
                                <i class="far fa-star" data-rating="4"></i>
                                <i class="far fa-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="formRatingInput" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control rounded-0" name="comment" rows="3" placeholder="Chia sẻ cảm nghĩ của bạn..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-dark rounded-0 px-4">Gửi đánh giá</button>
                        <button type="button" class="btn btn-link text-decoration-none text-muted" onclick="hideReviewForm()">Hủy</button>
                    </form>
                </div>

                <!-- Reviews List -->
                <div class="reviews-list">
                    <?php if (!empty($data['reviews'])): ?>
                        <?php foreach ($data['reviews'] as $review): ?>
                            <div class="mb-4 border-bottom pb-4">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <?php if (!empty($review['avatar_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($review['avatar_url']); ?>" class="rounded-circle object-fit-cover" style="width: 50px; height: 50px;">
                                        <?php else: ?>
                                            <div class="avatar-placeholder">
                                                <?php 
                                                $parts = explode(' ', $review['user_name']);
                                                $initials = '';
                                                if (count($parts) > 0) $initials .= strtoupper(mb_substr($parts[0], 0, 1));
                                                if (count($parts) > 1) $initials .= strtoupper(mb_substr(end($parts), 0, 1));
                                                echo $initials ?: 'U';
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($review['user_name']); ?></h6>
                                            <small class="text-muted"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></small>
                                        </div>
                                        <div class="text-warning mb-2 small">
                                            <?php for($i=1; $i<=5; $i++) echo $i <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; ?>
                                        </div>
                                        <p class="text-secondary mb-0"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                        
                                        <!-- Placeholder for admin reply if needed later -->
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">Chưa có đánh giá nào.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($data['relatedProducts'])): ?>
<section class="py-5">
    <div class="container" style="max-width: 1400px;">
        <h3 class="text-center letter-spacing-2 mb-5" style="font-family: var(--font-heading);">Có thể bạn quan tâm</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($data['relatedProducts'] as $related): ?>
                <div class="col">
                    <div class="product-card border h-100 bg-white">
                        <div class="product-image-wrapper position-relative overflow-hidden mb-3">
                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $related['id']; ?>">
                                <img src="<?php echo htmlspecialchars($related['image_url'] ?? ''); ?>" 
                                     alt="<?php echo htmlspecialchars($related['name']); ?>"
                                     class="product-image">
                            </a>
                            
                            <!-- Add to Wishlist (Moved to Top Right) -->
                            <button type="button" onclick="toggleWishlist(<?php echo $related['id']; ?>, this)" class="btn bg-white text-muted shadow-sm position-absolute top-0 end-0 m-2 wishlist-badge-btn" title="Yêu thích" style="z-index: 10;">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                        <div class="text-center">
                            <h5 class="h6 mb-2">
                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $related['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($related['name']); ?>
                                </a>
                            </h5>
                            <div class="text-muted fw-bold"><?php echo number_format($related['price'], 0, ',', '.'); ?>đ</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
    // Gallery Logic
    const galleryImages = <?php echo json_encode(array_values($gallery)); ?>;
    let currentIndex = 0;

    function setMainImage(index) {
        if (index < 0 || index >= galleryImages.length) return;
        currentIndex = index;
        updateGalleryDisplay();
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % galleryImages.length;
        updateGalleryDisplay();
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
        updateGalleryDisplay();
    }

    function updateGalleryDisplay() {
        // Update Main Image
        const mainImg = document.getElementById('main-image');
        mainImg.style.opacity = '0.5';
        setTimeout(() => {
            mainImg.src = galleryImages[currentIndex];
            mainImg.style.opacity = '1';
        }, 150);

        // Update Thumbnails
        document.querySelectorAll('.thumbnail-item').forEach((item, index) => {
            if (index === currentIndex) {
                item.classList.remove('border-light');
                item.classList.add('border-primary');
                item.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                item.classList.remove('border-primary');
                item.classList.add('border-light');
            }
        });
    }

    function changeQuantity(change) {
        const input = document.getElementById('quantity-input');
        let newValue = parseInt(input.value) + change;
        if (newValue >= 1 && newValue <= parseInt(input.max)) {
            input.value = newValue;
        }
    }

    // Tab Logic is now handled generically by CSS active class, 
    // but we can ensure standard Bootstrap tab events work fine.

    // Review Form Toggle
    function showReviewForm() { document.getElementById('reviewFormContainer').classList.remove('d-none'); }
    function hideReviewForm() { document.getElementById('reviewFormContainer').classList.add('d-none'); }

    // Star Rating Logic
    document.querySelectorAll('#reviewForm .stars i').forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('formRatingInput').value = rating;
            
            document.querySelectorAll('#reviewForm .stars i').forEach(s => {
                if (s.dataset.rating <= rating) {
                    s.classList.remove('far');
                    s.classList.add('fas');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
        });
    });
</script>
