<div class="container py-4">
    <div class="row">
        <!-- Checkout Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>/checkout/process" method="POST" id="checkoutForm">
                        <!-- Customer Information -->
                        <div class="mb-4">
                            <h6 class="mb-3">Thông tin khách hàng</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="full_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo isset($data['errors']['full_name']) ? 'is-invalid' : ''; ?>"
                                           id="full_name" name="full_name"
                                           value="<?php echo htmlspecialchars($data['old_input']['full_name'] ?? $data['user']['full_name']); ?>" required>
                                    <?php if (isset($data['errors']['full_name'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['full_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control <?php echo isset($data['errors']['phone_number']) ? 'is-invalid' : ''; ?>"
                                           id="phone_number" name="phone_number"
                                           value="<?php echo htmlspecialchars($data['old_input']['phone_number'] ?? $data['user']['phone']); ?>" required>
                                    <?php if (isset($data['errors']['phone_number'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['phone_number']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control <?php echo isset($data['errors']['email']) ? 'is-invalid' : ''; ?>"
                                       id="email" name="email"
                                       value="<?php echo htmlspecialchars($data['old_input']['email'] ?? $data['user']['email']); ?>" required>
                                <?php if (isset($data['errors']['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['email']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="mb-4">
                            <h6 class="mb-3">Địa chỉ giao hàng</h6>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($data['errors']['address']) ? 'is-invalid' : ''; ?>"
                                       id="address" name="address"
                                       value="<?php echo htmlspecialchars($data['old_input']['address'] ?? ''); ?>"
                                       placeholder="Số nhà, tên đường..." required>
                                <?php if (isset($data['errors']['address'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['address']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo isset($data['errors']['city']) ? 'is-invalid' : ''; ?>"
                                           id="city" name="city"
                                           value="<?php echo htmlspecialchars($data['old_input']['city'] ?? ''); ?>" required>
                                    <?php if (isset($data['errors']['city'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['city']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo isset($data['errors']['district']) ? 'is-invalid' : ''; ?>"
                                           id="district" name="district"
                                           value="<?php echo htmlspecialchars($data['old_input']['district'] ?? ''); ?>" required>
                                    <?php if (isset($data['errors']['district'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['district']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ward" class="form-label">Phường/Xã</label>
                                    <input type="text" class="form-control" id="ward" name="ward"
                                           value="<?php echo htmlspecialchars($data['old_input']['ward'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <h6 class="mb-3">Phương thức thanh toán</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod"
                                               <?php echo ($data['old_input']['payment_method'] ?? 'cod') === 'cod' ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="cod">
                                            <i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng (COD)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer"
                                               <?php echo ($data['old_input']['payment_method'] ?? '') === 'bank_transfer' ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="bank_transfer">
                                            <i class="fas fa-university me-2"></i>Chuyển khoản ngân hàng
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo"
                                               <?php echo ($data['old_input']['payment_method'] ?? '') === 'momo' ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="momo">
                                            <i class="fas fa-mobile-alt me-2"></i>Ví MoMo
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="vnpay" value="vnpay"
                                               <?php echo ($data['old_input']['payment_method'] ?? '') === 'vnpay' ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="vnpay">
                                            <i class="fas fa-qrcode me-2"></i>VNPAY-QR
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($data['errors']['payment_method'])): ?>
                                <div class="text-danger"><?php echo $data['errors']['payment_method']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Ghi chú đơn hàng</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                      placeholder="Ghi chú về đơn hàng (tùy chọn)"><?php echo htmlspecialchars($data['old_input']['notes'] ?? ''); ?></textarea>
                        </div>

                        <?php if (isset($data['errors']['general'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $data['errors']['general']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>Đặt hàng
                            </button>
                            <a href="<?php echo BASE_URL; ?>/cart" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại giỏ hàng
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <!-- Cart Items -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Sản phẩm trong đơn hàng</h6>
                </div>
                <div class="card-body">
                    <?php foreach ($data['cartItems'] as $item): ?>
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                <?php if ($item['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                <div class="text-muted small">SL: <?php echo $item['quantity']; ?> x <?php echo number_format($item['price'], 0, ',', '.'); ?>đ</div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold"><?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?>đ</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Voucher -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Mã giảm giá</h6>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="voucher_code" placeholder="Nhập mã voucher">
                        <button class="btn btn-outline-primary" type="button" onclick="applyVoucher()">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                    <div id="voucher-result"></div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Tóm tắt đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span><?php echo number_format($data['cartTotal'], 0, ',', '.'); ?>đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3" id="discount-row" style="display: none;">
                        <span>Giảm giá:</span>
                        <span class="text-success" id="discount-amount">-0đ</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-0">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-primary" id="final-total"><?php echo number_format($data['cartTotal'], 0, ',', '.'); ?>đ</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let originalTotal = <?php echo $data['cartTotal']; ?>;
let discountAmount = 0;

function applyVoucher() {
    const voucherCode = document.getElementById('voucher_code').value.trim();

    if (!voucherCode) {
        alert('Vui lòng nhập mã voucher');
        return;
    }

    fetch('<?php echo BASE_URL; ?>/checkout/applyVoucher', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'voucher_code=' + encodeURIComponent(voucherCode)
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('voucher-result');

        if (data.error) {
            resultDiv.innerHTML = '<div class="alert alert-danger py-2"><i class="fas fa-exclamation-triangle me-1"></i>' + data.error + '</div>';
            // Reset discount
            discountAmount = 0;
            updateTotal();
        } else {
            resultDiv.innerHTML = '<div class="alert alert-success py-2"><i class="fas fa-check-circle me-1"></i>Mã giảm giá hợp lệ: ' + data.voucher.description + '</div>';
            discountAmount = data.voucher.discount;
            updateTotal();

            // Set voucher code in form
            let voucherInput = document.querySelector('input[name="voucher_code"]');
            if (!voucherInput) {
                voucherInput = document.createElement('input');
                voucherInput.type = 'hidden';
                voucherInput.name = 'voucher_code';
                document.getElementById('checkoutForm').appendChild(voucherInput);
            }
            voucherInput.value = voucherCode;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('voucher-result').innerHTML = '<div class="alert alert-danger py-2">Có lỗi xảy ra, vui lòng thử lại</div>';
    });
}

function updateTotal() {
    const finalTotal = originalTotal - discountAmount;
    document.getElementById('final-total').textContent = new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(finalTotal);

    const discountRow = document.getElementById('discount-row');
    const discountAmountEl = document.getElementById('discount-amount');

    if (discountAmount > 0) {
        discountRow.style.display = 'flex';
        discountAmountEl.textContent = '-' + new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(discountAmount);
    } else {
        discountRow.style.display = 'none';
    }
}

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    // Basic validation
    const requiredFields = ['full_name', 'email', 'phone_number', 'address', 'city', 'district'];
    let isValid = true;

    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.classList.add('is-invalid');
            isValid = false;
        } else {
            element.classList.remove('is-invalid');
        }
    });

    // Email validation
    const email = document.getElementById('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.value && !emailRegex.test(email.value)) {
        email.classList.add('is-invalid');
        isValid = false;
    }

    // Phone validation
    const phone = document.getElementById('phone_number');
    const phoneRegex = /^[0-9]{10,11}$/;
    if (phone.value && !phoneRegex.test(phone.value)) {
        phone.classList.add('is-invalid');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ và chính xác thông tin bắt buộc');
        return false;
    }

    // Disable submit button
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
});
</script>
