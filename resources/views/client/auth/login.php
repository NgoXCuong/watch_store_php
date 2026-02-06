<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'Đăng nhập') ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #c9a961;
            --text-light: #f8f9fa;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #000;
            height: 100vh;
            overflow: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
        }

        .auth-wrapper {
            height: 100vh;
        }

        .auth-visual {
            background: url('<?= BASE_URL ?>/assets/img/login.avif') no-repeat center center/cover;
            position: relative;
        }

        .auth-visual::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.3), rgba(0,0,0,0.8));
        }

        .auth-form-container {
            background-color: var(--primary-color);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-content {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            color: var(--text-light);
        }

        .form-control {
            background: transparent;
            border: none;
            border-bottom: 2px solid #333;
            border-radius: 0;
            color: white;
            padding: 1rem 0.5rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: transparent;
            box-shadow: none;
            border-color: var(--secondary-color);
            color: white;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-light);
            margin-bottom: 0.25rem;
        }

        .btn-auth {
            background: var(--secondary-color);
            color: #000;
            border: none;
            border-radius: 0;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-auth:hover {
            background: #fff;
            color: #000;
        }

        .auth-link {
            color: var(--secondary-color);
            text-decoration: none;
            transition: all 0.3s;
        }

        .auth-link:hover {
            color: #fff;
            text-decoration: underline;
        }

        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: white;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 2px;
            opacity: 0.8;
            transition: all 0.3s;
            z-index: 10;
        }

        .back-link:hover {
            opacity: 1;
            color: var(--secondary-color);
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ea868f;
        }

        /* Mobile adjustments */
        @media(max-width: 991px) {
            .auth-visual {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="row g-0 auth-wrapper">
        <!-- Left: Image Section -->
        <div class="col-lg-7 auth-visual d-none d-lg-block">
            <div class="position-absolute bottom-0 start-0 p-5 z-index-1 text-white">
                <img src="<?= BASE_URL ?>/assets/img/logo.svg" alt="Logo" class="img-fluid mb-3" style="max-width: 250px;">
                <p class="lead text-white">Khẳng định đẳng cấp với bộ sưu tập đồng hồ thượng hạng.</p>
            </div>
        </div>

        <!-- Right: Form Section -->
        <div class="col-lg-5 auth-form-container">
            <a href="<?= BASE_URL ?>/" class="back-link">
                <i class="fas fa-arrow-left me-2"></i>Trang chủ
            </a>

            <div class="auth-content">
                <div class="text-center mb-5">
                    <h1 class="text-white fw-bold  text-uppercase mb-2">Đăng Nhập</h1>
                    <p class="text-white letter-spacing-2">Chào mừng bạn quay trở lại</p>
                </div>

                <?php if (isset($data['errors']['general'])): ?>
                    <div class="alert alert-error rounded-0 mb-4 p-3">
                        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($data['errors']['general']) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/auth/login-process" method="POST">
                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= htmlspecialchars($data['old_input']['email'] ?? '') ?>" placeholder="name@gmail.com" required>
                        <?php if (isset($data['errors']['email'])): ?>
                            <div class="text-danger small mt-1"><?= htmlspecialchars($data['errors']['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-5 position-relative">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                        <button type="button" class="btn border-0 p-0 text-muted position-absolute end-0 bottom-0 mb-3" onclick="togglePassword()">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                        <?php if (isset($data['errors']['password'])): ?>
                            <div class="text-danger small mt-1"><?= htmlspecialchars($data['errors']['password']) ?></div>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="btn-auth mb-4">
                        Đăng nhập
                    </button>

                    <div class="text-center">
                        <p class="text-muted mb-0">Chưa có tài khoản? 
                            <a href="<?= BASE_URL ?>/auth/register" class="auth-link fw-bold">Đăng ký ngay</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>
