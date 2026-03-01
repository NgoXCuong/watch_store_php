<div class="container py-5" style="max-width: 1400px;">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-0 overflow-hidden h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 letter-spacing-1 border-bottom pb-3">Tài khoản của tôi</h5>
                    <div class="d-flex flex-column gap-2 mb-4">
                        <a href="<?php echo BASE_URL; ?>/profile" class="text-decoration-none text-dark py-2 px-3 rounded admin-sidebar-link hover-bg-light">
                            <i class="fas fa-user border-0 text-center" style="width: 25px;"></i> Thông tin tài khoản
                        </a>
                        <a href="<?php echo BASE_URL; ?>/profile/addresses" class="text-decoration-none text-dark py-2 px-3 rounded admin-sidebar-link hover-bg-light fw-bold bg-light">
                            <i class="fas fa-map-marker-alt text-center" style="width: 25px;"></i> Sổ địa chỉ
                        </a>
                        <a href="<?php echo BASE_URL; ?>/profile/change-password" class="text-decoration-none text-dark py-2 px-3 rounded admin-sidebar-link hover-bg-light">
                            <i class="fas fa-key text-center" style="width: 25px;"></i> Đổi mật khẩu
                        </a>
                        <a href="<?php echo BASE_URL; ?>/orders" class="text-decoration-none text-dark py-2 px-3 rounded admin-sidebar-link hover-bg-light">
                            <i class="fas fa-shopping-bag text-center" style="width: 25px;"></i> Đơn mua
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-0 mb-5">
                <div class="card-header bg-white border-bottom py-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold letter-spacing-2" style="font-family: var(--font-heading);">
                        <i class="fas fa-plus me-2 text-warning"></i>Thêm địa chỉ giao hàng
                    </h5>
                    <a href="<?php echo BASE_URL; ?>/profile/addresses" class="btn btn-link text-dark text-decoration-none">
                        Trở lại
                    </a>
                </div>
                <div class="card-body p-4 p-lg-5">
                    
                    <?php if (isset($data['errors']['general'])): ?>
                        <div class="alert alert-danger rounded-0 mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $data['errors']['general']; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/profile/addresses/store" method="POST">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-0 <?php echo isset($data['errors']['recipient_name']) ? 'is-invalid' : ''; ?>" 
                                           id="recipient_name" name="recipient_name" placeholder="Họ và tên" 
                                           value="<?php echo htmlspecialchars($data['old_input']['recipient_name'] ?? ''); ?>">
                                    <label for="recipient_name">Họ và tên</label>
                                    <?php if (isset($data['errors']['recipient_name'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['recipient_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-0 <?php echo isset($data['errors']['recipient_phone']) ? 'is-invalid' : ''; ?>" 
                                           id="recipient_phone" name="recipient_phone" placeholder="Số điện thoại"
                                           value="<?php echo htmlspecialchars($data['old_input']['recipient_phone'] ?? ''); ?>">
                                    <label for="recipient_phone">Số điện thoại</label>
                                    <?php if (isset($data['errors']['recipient_phone'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['recipient_phone']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-0 <?php echo isset($data['errors']['city']) ? 'is-invalid' : ''; ?>" 
                                           id="city" name="city" placeholder="Tỉnh/Thành phố"
                                           value="<?php echo htmlspecialchars($data['old_input']['city'] ?? ''); ?>">
                                    <label for="city">Tỉnh/Thành phố</label>
                                    <?php if (isset($data['errors']['city'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['city']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-0 <?php echo isset($data['errors']['district']) ? 'is-invalid' : ''; ?>" 
                                           id="district" name="district" placeholder="Quận/Huyện"
                                           value="<?php echo htmlspecialchars($data['old_input']['district'] ?? ''); ?>">
                                    <label for="district">Quận/Huyện</label>
                                    <?php if (isset($data['errors']['district'])): ?>
                                        <div class="invalid-feedback"><?php echo $data['errors']['district']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating mb-4">
                            <input type="text" class="form-control rounded-0 <?php echo isset($data['errors']['address_line']) ? 'is-invalid' : ''; ?>" 
                                   id="address_line" name="address_line" placeholder="Địa chỉ cụ thể"
                                   value="<?php echo htmlspecialchars($data['old_input']['address_line'] ?? ''); ?>">
                            <label for="address_line">Địa chỉ cụ thể (Tên tòa nhà, Số nhà, Đường...)</label>
                            <?php if (isset($data['errors']['address_line'])): ?>
                                <div class="invalid-feedback"><?php echo $data['errors']['address_line']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" <?php echo isset($data['old_input']['is_default']) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_default">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?php echo BASE_URL; ?>/profile/addresses" class="btn btn-outline-dark rounded-0 px-4 me-2">Hủy</a>
                            <button type="submit" class="btn btn-dark rounded-0 px-4">Lưu địa chỉ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-bg-light:hover {
    background-color: #f8f9fa !important;
}
</style>
