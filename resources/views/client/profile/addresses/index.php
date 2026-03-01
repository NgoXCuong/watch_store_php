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
                        <i class="fas fa-map-marker-alt me-2 text-warning"></i>Sổ địa chỉ
                    </h5>
                    <a href="<?php echo BASE_URL; ?>/profile/addresses/create" class="btn btn-dark rounded-0 px-4 letter-spacing-1 small">
                        <i class="fas fa-plus me-1"></i> Thêm địa chỉ mới
                    </a>
                </div>
                <div class="card-body p-0">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success rounded-0 m-4">
                            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger rounded-0 m-4">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($data['addresses'])): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($data['addresses'] as $address): ?>
                                <div class="list-group-item p-4 p-lg-5 border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-md-8 mb-3 mb-md-0">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="fw-bold mb-0 me-3"><?php echo htmlspecialchars($address['recipient_name']); ?></h6>
                                                <span class="text-muted border-start ps-3"><?php echo htmlspecialchars($address['recipient_phone']); ?></span>
                                            </div>
                                            <p class="text-muted mb-1"><?php echo htmlspecialchars($address['address_line']); ?></p>
                                            <p class="text-muted mb-2"><?php echo htmlspecialchars($address['district'] . ', ' . $address['city']); ?></p>
                                            
                                            <?php if ($address['is_default'] == 1): ?>
                                                <span class="badge border border-danger text-danger rounded-0 px-2 py-1 bg-transparent">Mặc định</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <div class="mb-3">
                                                <a href="<?php echo BASE_URL; ?>/profile/addresses/edit/<?php echo $address['id']; ?>" class="text-decoration-none me-3">Cập nhật</a>
                                                <?php if ($address['is_default'] != 1): ?>
                                                    <form action="<?php echo BASE_URL; ?>/profile/addresses/delete/<?php echo $address['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                                                        <button type="submit" class="btn btn-link p-0 m-0 text-decoration-none text-danger border-0">Xóa</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($address['is_default'] != 1): ?>
                                                <form action="<?php echo BASE_URL; ?>/profile/addresses/set-default/<?php echo $address['id']; ?>" method="POST">
                                                    <button type="submit" class="btn btn-outline-dark btn-sm rounded-0">Thiết lập mặc định</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">Bạn chưa lưu địa chỉ nào</p>
                            <a href="<?php echo BASE_URL; ?>/profile/addresses/create" class="btn btn-dark rounded-0 px-4 letter-spacing-1 small">Thêm địa chỉ ngay</a>
                        </div>
                    <?php endif; ?>
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
