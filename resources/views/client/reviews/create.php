<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i>Đánh giá sản phẩm</h5>
                </div>
                <div class="card-body">
                    <!-- Product Info -->
                    <div class="product-info mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <?php if ($data['product']['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($data['product']['image_url']); ?>"
                                     alt="<?php echo htmlspecialchars($data['product']['name']); ?>"
                                     class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <?php endif; ?>
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($data['product']['name']); ?></h6>
                                <p class="text-muted mb-0"><?php echo number_format($data['product']['price'], 0, ',', '.'); ?>đ</p>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo BASE_URL; ?>/reviews/store" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $data['product']['id']; ?>">

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label">Đánh giá của bạn <span class="text-danger">*</span></label>
                            <div class="rating-stars">
                                <div class="stars" id="rating-stars">
                                    <i class="far fa-star" data-rating="1"></i>
                                    <i class="far fa-star" data-rating="2"></i>
                                    <i class="far fa-star" data-rating="3"></i>
                                    <i class="far fa-star" data-rating="4"></i>
                                    <i class="far fa-star" data-rating="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="rating-input" value="<?php echo $data['old_input']['rating'] ?? ''; ?>" required>
                                <div class="rating-text mt-2" id="rating-text">
                                    <?php if (isset($data['old_input']['rating'])): ?>
                                        Bạn đã chọn <?php echo $data['old_input']['rating']; ?> sao
                                    <?php else: ?>
                                        Chọn số sao để đánh giá
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (isset($data['errors']['rating'])): ?>
                                <div class="text-danger mt-1"><?php echo $data['errors']['rating']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label for="comment" class="form-label">Nội dung đánh giá <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php echo isset($data['errors']['comment']) ? 'is-invalid' : ''; ?>"
                                      id="comment" name="comment" rows="5"
                                      placeholder="Hãy chia sẻ trải nghiệm của bạn về sản phẩm này..."
                                      required><?php echo htmlspecialchars($data['old_input']['comment'] ?? ''); ?></textarea>
                            <?php if (isset($data['errors']['comment'])): ?>
                                <div class="invalid-feedback"><?php echo $data['errors']['comment']; ?></div>
                            <?php endif; ?>
                            <div class="form-text">Tối thiểu 10 ký tự</div>
                        </div>

                        <?php if (isset($data['errors']['general'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $data['errors']['general']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark" id="submit-btn" disabled>
                                Đăng đánh giá
                            </button>
                            <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $data['product']['id']; ?>" class="btn btn-outline-dark">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    user-select: none;
}

.stars {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
}

.stars i {
    margin-right: 5px;
    transition: color 0.2s ease;
}

.stars i:hover,
.stars i.active {
    color: #ffc107;
}

.rating-text {
    font-size: 0.9rem;
    color: #666;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('#rating-stars i');
    const ratingInput = document.getElementById('rating-input');
    const ratingText = document.getElementById('rating-text');

    // Set initial rating if exists
    const currentRating = parseInt(ratingInput.value);
    if (currentRating > 0) {
        updateStars(currentRating);
    }

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            updateStars(rating);
            updateRatingText(rating);
        });

        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function() {
            const currentRating = parseInt(ratingInput.value) || 0;
            updateStars(currentRating);
        });
    });

    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('far');
                star.classList.add('fas', 'active');
            } else {
                star.classList.remove('fas', 'active');
                star.classList.add('far');
            }
        });
    }

    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('far');
                star.classList.add('fas');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
            }
        });
    }

    function updateRatingText(rating) {
        const texts = {
            1: 'Rất tệ',
            2: 'Tệ',
            3: 'Bình thường',
            4: 'Tốt',
            5: 'Xuất sắc'
        };
        ratingText.textContent = rating > 0 ? `Bạn đã chọn ${rating} sao - ${texts[rating]}` : 'Chọn số sao để đánh giá';
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const rating = ratingInput.value;
        const comment = document.getElementById('comment').value.trim();

        if (!rating || rating < 1 || rating > 5) {
            e.preventDefault();
            alert('Vui lòng chọn số sao đánh giá');
            return false;
        }

        if (comment.length < 10) {
            e.preventDefault();
            alert('Nội dung đánh giá phải có ít nhất 10 ký tự');
            return false;
        }
    });
});
</script>
