<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body text-center p-4 bg-light">
                    <div class="position-relative d-inline-block mb-3">
<?php 
                        $avatarUrl = !empty($_SESSION['user']['avatar_url']) 
                            ? htmlspecialchars($_SESSION['user']['avatar_url']) 
                            : BASE_URL . '/assets/img/default-avatar.png';
                        ?>
                        <img src="<?php echo $avatarUrl; ?>" 
                             alt="Avatar" 
                             class="rounded-circle shadow-sm object-fit-cover"
                             style="width: 100px; height: 100px;">
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($_SESSION['user']['full_name']); ?></h5>
                    <p class="text-muted small mb-0"><?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
                </div>
            </div>
            
            <div class="list-group list-group-flush shadow-sm mt-3 rounded-3 overflow-hidden">
                <a href="<?php echo BASE_URL; ?>/profile" class="list-group-item list-group-item-action py-3">
                    <i class="fas fa-user-circle me-3 text-muted"></i>Thông tin tài khoản
                </a>
                <a href="<?php echo BASE_URL; ?>/profile/edit" class="list-group-item list-group-item-action py-3">
                    <i class="fas fa-edit me-3 text-muted"></i>Chỉnh sửa hồ sơ
                </a>
                <a href="<?php echo BASE_URL; ?>/profile/change-password" class="list-group-item list-group-item-action py-3 active fw-bold">
                    <i class="fas fa-key me-3"></i>Đổi mật khẩu
                </a>
                <a href="<?php echo BASE_URL; ?>/reviews/my-reviews" class="list-group-item list-group-item-action py-3">
                    <i class="fas fa-star me-3 text-muted"></i>Đánh giá của tôi
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Đổi mật khẩu</h5>
                </div>
                <div class="card-body p-4">
                
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <?php if (isset($data['errors']['general'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $data['errors']['general']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>/profile/updatePassword" method="POST">
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?php echo isset($data['errors']['current_password']) ? 'is-invalid' : ''; ?>" 
                                       name="current_password">
                                <?php if (isset($data['errors']['current_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['current_password']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?php echo isset($data['errors']['new_password']) ? 'is-invalid' : ''; ?>" 
                                       name="new_password">
                                <div class="form-text">Tối thiểu 6 ký tự</div>
                                <?php if (isset($data['errors']['new_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['new_password']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?php echo isset($data['errors']['confirm_password']) ? 'is-invalid' : ''; ?>" 
                                       name="confirm_password">
                                <?php if (isset($data['errors']['confirm_password'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['confirm_password']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-dark px-4">Đổi mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
