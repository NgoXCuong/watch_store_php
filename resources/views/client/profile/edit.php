<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body text-center p-4 bg-light">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="<?php echo $data['user']['avatar_url'] ? htmlspecialchars($data['user']['avatar_url']) : BASE_URL . '/assets/img/default-avatar.png'; ?>" 
                             alt="Avatar" 
                             class="rounded-circle shadow-sm object-fit-cover"
                             style="width: 100px; height: 100px;">
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($data['user']['full_name']); ?></h5>
                    <p class="text-muted small mb-0"><?php echo htmlspecialchars($data['user']['email']); ?></p>
                </div>
            </div>
            
            <div class="list-group list-group-flush shadow-sm mt-3 rounded-3 overflow-hidden">
                <a href="<?php echo BASE_URL; ?>/profile" class="list-group-item list-group-item-action py-3">
                    <i class="fas fa-user-circle me-3 text-muted"></i>Thông tin tài khoản
                </a>
                <a href="<?php echo BASE_URL; ?>/profile/edit" class="list-group-item list-group-item-action py-3 active fw-bold">
                    <i class="fas fa-edit me-3"></i>Chỉnh sửa hồ sơ
                </a>
                <a href="<?php echo BASE_URL; ?>/profile/change-password" class="list-group-item list-group-item-action py-3">
                    <i class="fas fa-key me-3 text-muted"></i>Đổi mật khẩu
                </a>
                <a href="<?php echo BASE_URL; ?>/reviews/myReviews" class="list-group-item list-group-item-action py-3">
                    <i class="fas fa-star me-3 text-muted"></i>Đánh giá của tôi
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">Chỉnh sửa hồ sơ</h5>
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

                    <form action="<?php echo BASE_URL; ?>/profile/update" method="POST" enctype="multipart/form-data">
                        
                        <!-- Avatar Upload -->
                        <div class="mb-4 text-center">
                            <div class="position-relative d-inline-block">
                                <img id="avatarPreview" 
                                     src="<?php echo $data['user']['avatar_url'] ? htmlspecialchars($data['user']['avatar_url']) : BASE_URL . '/assets/img/default-avatar.png'; ?>" 
                                     alt="Avatar" 
                                     class="rounded-circle shadow-sm object-fit-cover border border-3 border-light"
                                     style="width: 120px; height: 120px;">
                                
                                <label for="avatarInput" class="position-absolute bottom-0 end-0 bg-dark text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center cursor-pointer hover-scale transition-all" 
                                       style="width: 35px; height: 35px; cursor: pointer;">
                                    <i class="fas fa-camera small"></i>
                                </label>
                                <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*">
                            </div>
                            <div class="form-text mt-2 small">Nhấn vào icon máy ảnh để thay đổi ảnh đại diện</div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" name="username" 
                                       value="<?php echo htmlspecialchars($data['old_input']['username'] ?? $data['user']['username']); ?>" readonly disabled>
                                <div class="form-text">Tên đăng nhập không thể thay đổi</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control <?php echo isset($data['errors']['email']) ? 'is-invalid' : ''; ?>" 
                                       name="email" 
                                       value="<?php echo htmlspecialchars($data['old_input']['email'] ?? $data['user']['email']); ?>">
                                <?php if (isset($data['errors']['email'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['email']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($data['errors']['full_name']) ? 'is-invalid' : ''; ?>" 
                                       name="full_name" 
                                       value="<?php echo htmlspecialchars($data['old_input']['full_name'] ?? $data['user']['full_name']); ?>">
                                <?php if (isset($data['errors']['full_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['full_name']; ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control <?php echo isset($data['errors']['phone']) ? 'is-invalid' : ''; ?>" 
                                       name="phone" 
                                       value="<?php echo htmlspecialchars($data['old_input']['phone'] ?? $data['user']['phone']); ?>">
                                <?php if (isset($data['errors']['phone'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['phone']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?php echo BASE_URL; ?>/profile" class="btn btn-outline-secondary">Hủy bỏ</a>
                            <button type="submit" class="btn btn-dark px-4">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
