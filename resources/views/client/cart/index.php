<?php
// Calculate total logic
$total = 0;
foreach ($data['cartItems'] as $item) {
    // Only count active items if necessary, but assuming all cart items are valid
    $total += $item['quantity'] * $item['price'];
}
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section bg-light py-3 border-bottom mb-5">
    <div class="container" style="max-width: 1400px;">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb m-0 small text-uppercase" style="letter-spacing: 1px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container mb-5" style="max-width: 1400px;">
    <h1 class="text-uppercase letter-spacing-2 mb-5 text-center" style="font-family: var(--font-heading);">Giỏ hàng của bạn</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success rounded-0 border-0 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger rounded-0 border-0 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($data['cartItems'])): ?>
        <div class="text-center py-5 bg-light">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-4 opacity-50"></i>
            <h3 class="fw-light mb-3">Giỏ hàng đang trống</h3>
            <p class="text-muted mb-4">Bạn chưa chọn sản phẩm nào.</p>
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-dark rounded-0 px-5 py-3 text-uppercase letter-spacing-2 fw-bold">
                Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
        <form action="<?php echo BASE_URL; ?>/cart/update" method="POST">
            <div class="row gx-lg-5">
                <!-- Cart Items List -->
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light text-uppercase small text-muted letter-spacing-1">
                                <tr>
                                    <th class="py-3 ps-3 border-0">Sản phẩm</th>
                                    <th class="py-3 border-0 text-center">Giá</th>
                                    <th class="py-3 border-0 text-center">Số lượng</th>
                                    <th class="py-3 border-0 text-end pe-3">Tổng</th>
                                    <th class="py-3 border-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['cartItems'] as $item): ?>
                                    <tr class="border-bottom">
                                        <td class="ps-3 py-4">
                                            <div class="d-flex align-items-center">
                                                <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $item['product_id']; ?>" class="d-block me-3 bg-light" style="width: 80px; height: 80px;">
                                                    <?php if ($item['image_url']): ?>
                                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="w-100 h-100 object-fit-cover">
                                                    <?php else: ?>
                                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </a>
                                                <div>
                                                    <a href="<?php echo BASE_URL; ?>/products/show/<?php echo $item['product_id']; ?>" class="text-decoration-none text-dark fw-bold mb-1 d-block">
                                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                                    </a>
                                                    <div class="text-muted small">Mã: #<?php echo $item['product_id']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center py-4">
                                            <?php echo number_format($item['price'], 0, ',', '.'); ?>đ
                                        </td>
                                        <td class="text-center py-4">
                                            <div class="quantity-input-group border d-inline-flex align-items-center" style="height: 40px;">
                                                <button type="button" class="btn border-0 rounded-0 px-2 h-100" onclick="changeQuantity(<?php echo $item['product_id']; ?>, -1)">-</button>
                                                <input type="number" class="form-control border-0 text-center shadow-none p-0 fw-bold h-100" 
                                                       style="width: 40px;"
                                                       name="quantities[<?php echo $item['product_id']; ?>]" 
                                                       value="<?php echo $item['quantity']; ?>" 
                                                       min="1" max="<?php echo $item['stock']; ?>"
                                                       id="qty-<?php echo $item['product_id']; ?>">
                                                <button type="button" class="btn border-0 rounded-0 px-2 h-100" onclick="changeQuantity(<?php echo $item['product_id']; ?>, 1)">+</button>
                                            </div>
                                        </td>
                                        <td class="text-end py-4 pe-3 fw-bold ps-4">
                                            <?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?>đ
                                        </td>
                                        <td class="text-end py-4">
                                            <a href="<?php echo BASE_URL; ?>/cart/remove/<?php echo $item['product_id']; ?>" 
                                               class="text-muted hover-danger"
                                               onclick="return confirm('Xóa sản phẩm này?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="<?php echo BASE_URL; ?>/products" class="text-decoration-none text-muted text-uppercase small letter-spacing-1">
                            <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua sắm
                        </a>
                        <div class="d-flex gap-3">
                            <a href="<?php echo BASE_URL; ?>/cart/clear" class="btn btn-outline-dark rounded-0 text-uppercase letter-spacing-1 small px-4" onclick="return confirm('Bạn chắc chắn muốn xóa hết?')">
                                Xóa giỏ hàng
                            </a>
                            <button type="submit" class="btn btn-dark rounded-0 text-uppercase letter-spacing-1 small px-4">
                                Cập nhật giỏ hàng
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cart Totals -->
                <div class="col-lg-4">
                    <div class="bg-light p-4 p-md-5">
                        <h4 class="text-uppercase letter-spacing-2 mb-4" style="font-family: var(--font-heading);">Cộng giỏ hàng</h4>
                        
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">Vận chuyển</span>
                            <span class="text-success">Miễn phí</span>
                        </div>

                        <div class="d-flex justify-content-between mb-5">
                            <span class="h5 fw-bold mb-0">Tổng cộng</span>
                            <span class="h5 fw-bold mb-0 text-dark"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/checkout" class="btn btn-dark w-100 rounded-0 py-3 text-uppercase letter-spacing-2 fw-bold">
                            Thanh toán
                        </a>
                        
                        <div class="mt-4 text-center">
                            <div class="text-muted x-small text-uppercase mb-2">Thanh toán an toàn</div>
                            <div class="d-flex justify-content-center gap-3 text-secondary fs-4">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard"></i>
                                <i class="fab fa-cc-paypal"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
function changeQuantity(id, change) {
    const input = document.getElementById('qty-' + id);
    let newValue = parseInt(input.value) + change;
    if (newValue >= 1 && newValue <= parseInt(input.max)) {
        input.value = newValue;
    }
}
</script>
