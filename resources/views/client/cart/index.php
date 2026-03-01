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
            <ol class="breadcrumb m-0 small " style="letter-spacing: 1px;">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container mb-5" style="max-width: 1400px;">
    <h1 class="letter-spacing-2 mb-5 text-center" style="font-family: var(--font-heading);">Giỏ hàng của bạn</h1>

    <?php
    // Alerts are handled by global Toast in layout
    ?>

    <?php if (empty($data['cartItems'])): ?>
        <div class="text-center py-5 bg-light">
            <i class="fas fa-shopping-bag fa-3x text-muted mb-4 opacity-50"></i>
            <h3 class="fw-light mb-3">Giỏ hàng đang trống</h3>
            <p class="text-muted mb-4">Bạn chưa chọn sản phẩm nào.</p>
            <a href="<?php echo BASE_URL; ?>/products" class="btn btn-dark rounded-0 px-5 py-3 letter-spacing-2 fw-bold">
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
                            <thead class="bg-light small text-muted letter-spacing-1">
                                <tr>
                                    <th class="py-3 ps-3 border-0">Sản phẩm</th>
                                    <th class="py-3 border-0 text-center">Giá</th>
                                    <th class="py-3 border-0 text-center" style="width: 150px;">Số lượng</th>
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
                                                <button type="button" class="btn border-0 rounded-0 px-2 h-100" onclick="changeQuantity(<?php echo $item['product_id']; ?>, -1)" <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?> id="btn-minus-<?php echo $item['product_id']; ?>">-</button>
                                                
                                                <input type="number" class="form-control border-0 text-center shadow-none p-0 fw-bold h-100 quantity-input-field" 
                                                       style="width: 40px;"
                                                       data-product-id="<?php echo $item['product_id']; ?>"
                                                       value="<?php echo $item['quantity']; ?>" 
                                                       min="1" max="<?php echo $item['stock']; ?>"
                                                       id="qty-<?php echo $item['product_id']; ?>"
                                                       onchange="updateCartAjax(<?php echo $item['product_id']; ?>, this.value)">
                                                       
                                                <button type="button" class="btn border-0 rounded-0 px-2 h-100" onclick="changeQuantity(<?php echo $item['product_id']; ?>, 1)" <?php echo $item['quantity'] >= $item['stock'] ? 'disabled' : ''; ?> id="btn-plus-<?php echo $item['product_id']; ?>">+</button>
                                            </div>
                                            <div class="text-danger small mt-1 d-none" id="qty-error-<?php echo $item['product_id']; ?>">Vượt quá kho</div>
                                        </td>
                                        <td class="text-end py-4 pe-3 fw-bold ps-4 text-primary" id="line-total-<?php echo $item['product_id']; ?>">
                                            <?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?>đ
                                        </td>
                                        <td class="text-end py-4">
                                            <a href="javascript:void(0)" 
                                               class="text-muted hover-danger"
                                               onclick="removeItemAjax(<?php echo $item['product_id']; ?>)">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Nút Tiếp tục mua sắm và Xóa giỏ hàng đã được ẩn theo yêu cầu -->
                </div>

                <!-- Cart Totals -->
                <div class="col-lg-4">
                    <div class="bg-light p-4 p-md-5">
                        <h4 class="letter-spacing-2 mb-4" style="font-family: var(--font-heading);">Cộng giỏ hàng</h4>
                        
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-bold" id="cart-subtotal"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                            <span class="text-muted">Vận chuyển</span>
                            <span class="text-success">Miễn phí</span>
                        </div>

                        <div class="d-flex justify-content-between mb-5">
                            <span class="h5 fw-bold mb-0">Tổng cộng</span>
                            <span class="h5 fw-bold mb-0 text-dark" id="cart-total"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                        </div>

                        <a href="<?php echo BASE_URL; ?>/checkout" class="btn btn-dark w-100 rounded-0 py-3 letter-spacing-2 fw-bold">
                            Thanh toán
                        </a>
                        
                        <div class="mt-4 text-center">
                            <div class="text-muted x-small mb-2">Thanh toán an toàn</div>
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
// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

// Thay đổi số lượng khi bấm nút +/-
function changeQuantity(id, change) {
    const input = document.getElementById('qty-' + id);
    let newValue = parseInt(input.value) + change;
    let min = parseInt(input.min) || 1;
    let max = parseInt(input.max);
    
    if (newValue >= min && newValue <= max) {
        input.value = newValue;
        updateCartAjax(id, newValue);
    } else if (newValue > max) {
        const errorDiv = document.getElementById('qty-error-' + id);
        errorDiv.classList.remove('d-none');
        setTimeout(() => errorDiv.classList.add('d-none'), 3000);
    }
}

// debounce timer
let updateTimer;

// Gọi API cập nhật
function updateCartAjax(productId, quantity) {
    // Clear the previous timer
    clearTimeout(updateTimer);
    
    // Set a new timer
    updateTimer = setTimeout(() => {
        fetch('<?php echo BASE_URL; ?>/cart/update-ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update Line Total
                if (document.getElementById('line-total-' + productId)) {
                    document.getElementById('line-total-' + productId).innerText = formatCurrency(data.lineTotal);
                }
                
                // Update Subtotal & Total
                document.getElementById('cart-subtotal').innerText = formatCurrency(data.newTotal);
                document.getElementById('cart-total').innerText = formatCurrency(data.newTotal);
                
                // Update Header Cart Count
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) {
                    cartCountEl.innerText = data.newCount;
                    cartCountEl.style.display = data.newCount > 0 ? 'inline-block' : 'none';
                }
                
                // Enable/Disable buttons based on min/max
                const input = document.getElementById('qty-' + productId);
                document.getElementById('btn-minus-' + productId).disabled = input.value <= 1;
                document.getElementById('btn-plus-' + productId).disabled = input.value >= input.max;
                
            } else {
                alert(data.message || 'Có lỗi xảy ra');
                // Reload to sync state if error
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }, 400); // 400ms delay debounce
}

// Xóa 1 sản phẩm
function removeItemAjax(productId) {
    if(!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
    
    // Gọi ajax xóa bằng cách gửi số lượng = 0
    fetch('<?php echo BASE_URL; ?>/cart/update-ajax', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 0
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.reload(); // Reload to remove HTML row
        } else {
            alert(data.message);
        }
    });
}

// Xóa tất cả
function clearCartAjax() {
    if(!confirm('Bạn chắc chắn muốn xóa hết giỏ hàng?')) return;
    window.location.href = '<?php echo BASE_URL; ?>/cart/clear';
}
</script>
