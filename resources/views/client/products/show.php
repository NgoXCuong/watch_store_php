<!-- Breadcrumb -->
<section class="breadcrumb-section bg-light py-3 border-bottom mb-5">
    <div class="container" style="max-width: 1400px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 small text-uppercase" style="letter-spacing: 1px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/products" class="text-decoration-none text-muted">Bộ sưu tập</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($data['product']['name']); ?></li>
            </ol>
        </nav>
    </div>
</section>

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
                    
                    <?php if ($data['product']['stock'] == 0): ?>
                        <div class="position-absolute top-50 start-50 translate-middle badge bg-dark text-white fs-5 rounded-0 px-4 py-3 text-uppercase">
                            Hết hàng
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Thumbnail Grid (Placeholder for multi-image logic if added later) -->
                <!-- Gallery Thumbnails -->
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

                <?php if (count($gallery) > 1): ?>
                <div class="d-flex gap-2 overflow-auto pb-2 custom-scrollbar">
                    <?php foreach ($gallery as $index => $imgUrl): ?>
                        <div class="thumbnail-item border <?php echo $index === 0 ? 'border-primary' : 'border-light'; ?> p-1" 
                             style="width: 80px; height: 80px; min-width: 80px; cursor: pointer; transition: all 0.3s;"
                             onclick="changeMainImage(this, '<?php echo htmlspecialchars($imgUrl); ?>')">
                            <img src="<?php echo htmlspecialchars($imgUrl); ?>" class="w-100 h-100 object-fit-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <script>
                    function changeMainImage(el, src) {
                        // Update Main Image
                        const mainImg = document.getElementById('main-image');
                        mainImg.style.opacity = '0.5';
                        setTimeout(() => {
                            mainImg.src = src;
                            mainImg.style.opacity = '1';
                        }, 200);

                        // Update Active State
                        document.querySelectorAll('.thumbnail-item').forEach(item => {
                            item.classList.remove('border-primary');
                            item.classList.add('border-light');
                        });
                        el.classList.remove('border-light');
                        el.classList.add('border-primary');
                    }
                </script>
            </div>

            <!-- Product Info -->
            <div class="col-lg-5">
                <div class="product-info ps-lg-4">
                    <div class="text-uppercase text-muted letter-spacing-2 mb-2 small">
                        <?php echo htmlspecialchars($data['product']['brand_name'] ?? 'Thương hiệu'); ?>
                    </div>
                    
                    <h1 class="display-5 fw-bold mb-3" style="font-family: var(--font-heading);"><?php echo htmlspecialchars($data['product']['name']); ?></h1>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="text-warning small">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-muted small border-start ps-3">120 Đánh giá</span>
                        <span class="text-muted small border-start ps-3">Mã: #<?php echo $data['product']['id']; ?></span>
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

                    <div class="mb-5 text-muted fw-light">
                        <?php 
                        $desc = htmlspecialchars($data['product']['description']);
                        echo strlen($desc) > 200 ? substr($desc, 0, 200) . '...' : $desc;
                        ?>
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
                                <div class="col-8">
                                    <?php if (isset($_SESSION['user'])): ?>
                                        <button type="submit" class="btn btn-dark w-100 h-100 rounded-0 text-uppercase letter-spacing-2 fw-bold d-flex align-items-center justify-content-center gap-2">
                                            <i class="fas fa-shopping-bag"></i> Thêm vào giỏ
                                        </button>
                                    <?php else: ?>
                                        <a href="<?php echo BASE_URL; ?>/auth/login" class="btn btn-dark w-100 h-100 rounded-0 text-uppercase letter-spacing-2 fw-bold d-flex align-items-center justify-content-center">
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

                    <div class="d-flex gap-4 text-small text-uppercase letter-spacing-1 border-top pt-4">
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
    <div class="container" style="max-width: 1000px;">
        <ul class="nav nav-tabs border-0 justify-content-center mb-5 gap-4" id="productTabs" role="tablist">
            <li class="nav-item border-0" role="presentation">
                <button class="nav-link active bg-transparent border-0 border-bottom border-2 border-dark text-dark text-uppercase letter-spacing-2 fw-bold rounded-0 pb-2 px-0" 
                        id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                    Mô tả chi tiết
                </button>
            </li>
            <li class="nav-item border-0" role="presentation">
                <button class="nav-link bg-transparent border-0 text-muted text-uppercase letter-spacing-2 fw-bold rounded-0 pb-2 px-0" 
                        id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab">
                    Thông số
                </button>
            </li>
            <li class="nav-item border-0" role="presentation">
                <button class="nav-link bg-transparent border-0 text-muted text-uppercase letter-spacing-2 fw-bold rounded-0 pb-2 px-0" 
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
                        <?php echo nl2br(htmlspecialchars($data['product']['description'])); ?>
                    <?php else: ?>
                        <p class="text-muted text-center fst-italic">Đang cập nhật nội dung...</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Specs -->
            <div class="tab-pane fade" id="specifications" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped">
                        <tbody>
                            <tr>
                                <td class="fw-bold w-25">Thương hiệu</td>
                                <td><?php echo htmlspecialchars($data['product']['brand_name'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Danh mục</td>
                                <td><?php echo htmlspecialchars($data['product']['category_name'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tình trạng</td>
                                <td><?php echo $data['product']['stock'] > 0 ? 'Còn hàng' : 'Hết hàng'; ?></td>
                            </tr>
                            <?php 
                            if (!empty($data['product']['specifications'])) {
                                $specs = json_decode($data['product']['specifications'], true);
                                
                                // Mapping keys to Vietnamese
                                $specMap = [
                                    'brand' => 'Thương hiệu',
                                    'origin' => 'Xuất xứ',
                                    'target' => 'Đối tượng',
                                    'gender' => 'Giới tính',
                                    'line' => 'Dòng sản phẩm',
                                    'water_resistance' => 'Chống nước',
                                    'water_resist' => 'Chống nước',
                                    'dial_type' => 'Loại mặt số',
                                    'movement' => 'Loại máy',
                                    'engine' => 'Động cơ',
                                    'crystal_material' => 'Chất liệu kính',
                                    'glass_material' => 'Chất liệu kính',
                                    'glass' => 'Kính',
                                    'case_material' => 'Chất liệu vỏ',
                                    'band_material' => 'Chất liệu dây',
                                    'strap_material' => 'Chất liệu dây',
                                    'band_width' => 'Độ rộng dây',
                                    'clasp' => 'Kiểu khóa',
                                    'case_size' => 'Size mặt',
                                    'case_thickness' => 'Độ dày',
                                    'thickness' => 'Độ dày',
                                    'face_color' => 'Màu mặt',
                                    'dial_color' => 'Màu mặt',
                                    'case_diameter' => 'Đường kính mặt',
                                    'diameter' => 'Đường kính',
                                    'case_color' => 'Màu vỏ',
                                    'face_shape' => 'Hình dáng mặt',
                                    'shape' => 'Hình dáng',
                                    'collection' => 'Bộ sưu tập',
                                    'features' => 'Tính năng',
                                    'function' => 'Chức năng',
                                    'warranty' => 'Bảo hành'
                                ];

                                if (is_array($specs)) {
                                    foreach ($specs as $key => $value) {
                                        // Normalize key: lowercase and removing special chars if needed, 
                                        // stick to simple lookup
                                        $displayKey = $specMap[$key] ?? $key;
                                        // If key is not in map, maybe try capitalize first letter
                                        if (!isset($specMap[$key])) {
                                            $displayKey = ucfirst(str_replace('_', ' ', $key));
                                        }
                                        
                                        echo '<tr>';
                                        echo '<td class="fw-bold">' . htmlspecialchars($displayKey) . '</td>';
                                        echo '<td>' . htmlspecialchars($value) . '</td>';
                                        echo '</tr>';
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reviews -->
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
                    <div>
                        <h4 class="mb-1">Đánh giá khách hàng</h4>
                        <div class="text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            <span class="text-muted ms-2 small">4.5/5 Trung bình</span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user']) && $data['canReview']): ?>
                        <button class="btn btn-outline-dark rounded-0 px-4" onclick="showReviewForm()">Viết đánh giá</button>
                    <?php endif; ?>
                </div>

                <!-- Review Form (Hidden by default) -->
                <div id="reviewFormContainer" class="mb-5 bg-light p-4 d-none">
                    <form id="reviewForm">
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
                            <div class="mb-4 border-bottom pb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="fw-bold text-uppercase"><?php echo htmlspecialchars($review['user_name']); ?></div>
                                    <div class="small text-muted"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></div>
                                </div>
                                <div class="text-warning mb-2 small">
                                    <?php for($i=1; $i<=5; $i++) echo $i <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; ?>
                                </div>
                                <p class="text-muted mb-0"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
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
        <h3 class="text-center text-uppercase letter-spacing-2 mb-5" style="font-family: var(--font-heading);">Có thể bạn quan tâm</h3>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($data['relatedProducts'] as $related): ?>
                <div class="col">
                    <div class="product-card h-100 bg-white">
                        <div class="product-image-wrapper position-relative overflow-hidden mb-3">
                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $related['id']; ?>">
                                <img src="<?php echo htmlspecialchars($related['image_url'] ?? ''); ?>" 
                                     alt="<?php echo htmlspecialchars($related['name']); ?>"
                                     class="w-100 object-fit-cover" style="height: 300px;">
                            </a>
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
    function changeQuantity(change) {
        const input = document.getElementById('quantity-input');
        let newValue = parseInt(input.value) + change;
        if (newValue >= 1 && newValue <= parseInt(input.max)) {
            input.value = newValue;
        }
    }

    // Simple Tab Logic for Custom Style (since we removed standard border-bottom of bootstrap nav-tabs mostly)
    document.querySelectorAll('#productTabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('#productTabs .nav-link').forEach(t => {
                t.classList.remove('active', 'border-dark', 'text-dark');
                t.classList.add('text-muted');
            });
            this.classList.add('active', 'border-dark', 'text-dark');
            this.classList.remove('text-muted');
        });
    });

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
