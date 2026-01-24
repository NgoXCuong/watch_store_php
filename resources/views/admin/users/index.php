<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-users me-2"></i>Quản lý người dùng</h1>
            <a href="<?php echo BASE_URL; ?>/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm người dùng
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="search" placeholder="Tìm kiếm theo tên, email..."
                               value="<?php echo htmlspecialchars($data['search']); ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search me-2"></i>Tìm
                        </button>
                    </div>
                    <div class="col-md-2">
                        <?php if ($data['search']): ?>
                            <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i>Xóa lọc
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Thông tin</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['users'])): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Không tìm thấy người dùng nào</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['users'] as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td>
                                            <?php if ($user['avatar_url']): ?>
                                                <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>"
                                                     alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                                <br>
                                                <small class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $roleClass = '';
                                            $roleText = '';
                                            switch ($user['role']) {
                                                case 'admin':
                                                    $roleClass = 'badge bg-danger';
                                                    $roleText = 'Admin';
                                                    break;
                                                case 'staff':
                                                    $roleClass = 'badge bg-warning';
                                                    $roleText = 'Nhân viên';
                                                    break;
                                                default:
                                                    $roleClass = 'badge bg-secondary';
                                                    $roleText = 'Khách hàng';
                                            }
                                            ?>
                                            <span class="<?php echo $roleClass; ?>"><?php echo $roleText; ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($user['status']) {
                                                case 'active':
                                                    $statusClass = 'badge bg-success';
                                                    $statusText = 'Hoạt động';
                                                    break;
                                                case 'inactive':
                                                    $statusClass = 'badge bg-warning';
                                                    $statusText = 'Không hoạt động';
                                                    break;
                                                case 'banned':
                                                    $statusClass = 'badge bg-danger';
                                                    $statusText = 'Đã khóa';
                                                    break;
                                                default:
                                                    $statusClass = 'badge bg-secondary';
                                                    $statusText = 'Chưa xác định';
                                            }
                                            ?>
                                            <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo BASE_URL; ?>/admin/users/edit/<?php echo $user['id']; ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($user['id'] != $_SESSION['user']['id']): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>')"
                                                            title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($data['totalPages'] > 1): ?>
                    <nav aria-label="User pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($data['currentPage'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $data['currentPage'] - 1; ?>&search=<?php echo urlencode($data['search']); ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $data['currentPage'] - 2); $i <= min($data['totalPages'], $data['currentPage'] + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $data['currentPage'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($data['search']); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($data['currentPage'] < $data['totalPages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $data['currentPage'] + 1; ?>&search=<?php echo urlencode($data['search']); ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
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
