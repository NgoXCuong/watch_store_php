<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-user-edit me-2"></i>Chỉnh sửa người dùng</h1>
            <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>/admin/users/update/<?php echo $data['user']['id']; ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($data['errors']['username']) ? 'is-invalid' : ''; ?>"
                                       id="username" name="username"
                                       value="<?php echo htmlspecialchars($data['old_input']['username'] ?? $data['user']['username']); ?>" required>
                                <?php if (isset($data['errors']['username'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['username']; ?></div>
                                <?php endif; ?>
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

                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control <?php echo isset($data['errors']['password']) ? 'is-invalid' : ''; ?>"
                                       id="password" name="password">
                                <?php if (isset($data['errors']['password'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['password']; ?></div>
                                <?php endif; ?>
                                <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu. Mật khẩu mới phải có ít nhất 6 ký tự</div>
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo isset($data['errors']['full_name']) ? 'is-invalid' : ''; ?>"
                                       id="full_name" name="full_name"
                                       value="<?php echo htmlspecialchars($data['old_input']['full_name'] ?? $data['user']['full_name']); ?>" required>
                                <?php if (isset($data['errors']['full_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $data['errors']['full_name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       value="<?php echo htmlspecialchars($data['old_input']['phone'] ?? $data['user']['phone']); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="customer" <?php echo ($data['old_input']['role'] ?? $data['user']['role']) === 'customer' ? 'selected' : ''; ?>>Khách hàng</option>
                                    <option value="staff" <?php echo ($data['old_input']['role'] ?? $data['user']['role']) === 'staff' ? 'selected' : ''; ?>>Nhân viên</option>
                                    <option value="admin" <?php echo ($data['old_input']['role'] ?? $data['user']['role']) === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <div class="form-text">
                                    <strong>Lưu ý:</strong> Thay đổi vai trò sẽ ảnh hưởng đến quyền truy cập của người dùng
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" <?php echo ($data['old_input']['status'] ?? $data['user']['status']) === 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                                    <option value="inactive" <?php echo ($data['old_input']['status'] ?? $data['user']['status']) === 'inactive' ? 'selected' : ''; ?>>Không hoạt động</option>
                                    <option value="banned" <?php echo ($data['old_input']['status'] ?? $data['user']['status']) === 'banned' ? 'selected' : ''; ?>>Đã khóa</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="avatar_url" class="form-label">URL Avatar</label>
                                <input type="url" class="form-control" id="avatar_url" name="avatar_url"
                                       value="<?php echo htmlspecialchars($data['old_input']['avatar_url'] ?? $data['user']['avatar_url']); ?>"
                                       placeholder="https://example.com/avatar.jpg">
                                <div class="form-text">Để trống nếu không có avatar</div>
                            </div>

                            <!-- User Info Display -->
                            <div class="mb-3">
                                <label class="form-label">Thông tin tài khoản</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <small class="text-muted">Ngày tạo:</small><br>
                                            <strong><?php echo date('d/m/Y H:i', strtotime($data['user']['created_at'])); ?></strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <small class="text-muted">Cập nhật cuối:</small><br>
                                            <strong><?php echo date('d/m/Y H:i', strtotime($data['user']['updated_at'])); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($data['errors']['general'])): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $data['errors']['general']; ?>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật người dùng
                        </button>
                        <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <?php if ($data['user']['id'] != $_SESSION['user']['id']): ?>
                            <button type="button" class="btn btn-outline-danger ms-auto"
                                    onclick="deleteUser(<?php echo $data['user']['id']; ?>, '<?php echo htmlspecialchars($data['user']['full_name']); ?>')">
                                <i class="fas fa-trash me-2"></i>Xóa người dùng
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteUser(id, name) {
    if (confirm('Bạn có chắc chắn muốn xóa người dùng "' + name + '"?\n\nLưu ý: Hành động này không thể hoàn tác.')) {
        // Create a form to submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/admin/users/delete/' + id;

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'DELETE';

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
