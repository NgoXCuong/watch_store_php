<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Admin Dashboard'; ?> - Watch Store Luxury</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/img/logo.svg">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --admin-sidebar-bg: #ffffff;
            --admin-sidebar-text: #64748b;
            --admin-sidebar-hover: #1e293b;
            --admin-accent: #c9a961;
            --admin-bg: #f3f4f6;
            --admin-primary: #5086b8;
            --font: "Inter", sans-serif;
        }

        body {
            font-family: var(--font);
            background-color: var(--admin-bg);
            font-size: 0.9rem;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font);
            font-weight: 700;
        }

        /* Sidebar */
        .sidebar {
            background-color: var(--admin-sidebar-bg);
            min-height: 100vh;
            width: 260px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            border-right: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            color: #1e293b;
            font-family: var(--font-heading);
            font-size: 1.5rem;
            text-transform: uppercase;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            letter-spacing: 1px;
        }

        .sidebar-brand span {
            color: var(--admin-accent);
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .menu-header {
            padding: 0 1.5rem;
            margin-bottom: 0.8rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 1.5px;
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            color: var(--admin-sidebar-text);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .nav-link i {
            width: 24px;
            margin-right: 10px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: #94a3b8;
        }

        .nav-link:hover {
            color: var(--admin-sidebar-hover);
            background: #f8fafc;
        }

        .nav-link:hover i {
            color: var(--admin-accent);
        }

        .nav-link.active {
            color: var(--admin-accent);
            background: linear-gradient(90deg, rgba(201, 169, 97, 0.1), transparent);
            border-left-color: var(--admin-accent);
        }

        .nav-link.active i {
            color: var(--admin-accent);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 1rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .page-title {
            margin: 0;
            font-size: 1.5rem;
            
            color: var(--admin-primary);
        }

        .user-dropdown .btn {
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 15px;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .user-dropdown .btn:hover, .user-dropdown .btn.show {
            background: #f8f9fa;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--admin-accent);
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-family: var(--font-heading);
        }

        /* Cards & Content */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.02);
            overflow: hidden;
            margin-bottom: 1.5rem;
            background: #fff;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-family: var(--font-heading);
            color: var(--admin-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: #6c757d;
            border-bottom-width: 1px;
            padding: 1rem;
        }
        
        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
            color: #333;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
        }

        /* Utilities */
        .text-gold { color: var(--admin-accent) !important; }
        .bg-gold { background-color: var(--admin-accent) !important; }

        .alert-custom {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                left: -260px;
            }
            .sidebar.active {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            .overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div class="overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="<?php echo BASE_URL; ?>/" class="logo">
                <img src="<?php echo BASE_URL; ?>/assets/img/logo.svg" alt="Watch Store" style="height: 60px; width: auto;">
            </a>

        <div class="sidebar-menu">
            <div class="menu-header">Tổng quan</div>
            <a href="<?php echo BASE_URL; ?>/admin" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin') !== false && strpos($_SERVER['REQUEST_URI'], '/admin/') === false) ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <div class="menu-header mt-3">Quản lý</div>
            
            <a href="<?php echo BASE_URL; ?>/admin/products" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/products') !== false ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Sản phẩm
            </a>
            
            <a href="<?php echo BASE_URL; ?>/admin/categories" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> Danh mục
            </a>
            
            <a href="<?php echo BASE_URL; ?>/admin/brands" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/brands') !== false ? 'active' : ''; ?>">
                <i class="fas fa-gem"></i> Thương hiệu
            </a>
            
            <a href="<?php echo BASE_URL; ?>/admin/orders" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : ''; ?>">
                <i class="fas fa-shopping-bag"></i> Đơn hàng
            </a>

            <a href="<?php echo BASE_URL; ?>/admin/vouchers" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/vouchers') !== false ? 'active' : ''; ?>">
                <i class="fas fa-ticket-alt"></i> Voucher
            </a>

            <a href="<?php echo BASE_URL; ?>/admin/reviews" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/reviews') !== false ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Đánh giá
            </a>

            <div class="menu-header mt-3">Hệ thống</div>

            <a href="<?php echo BASE_URL; ?>/admin/users" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Người dùng
            </a>

            <!-- Divider -->
            <div style="height: 1px; background: rgba(0,0,0,0.05); margin: 1rem 1.5rem;"></div>

            <a href="<?php echo BASE_URL; ?>/" target="_blank" class="nav-link">
                <i class="fas fa-external-link-alt"></i> Xem Website
            </a>
        </div>
        
        <div class="mt-auto border-top p-3">
            <a href="<?php echo BASE_URL; ?>/auth/logout" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt text-danger"></i> Đăng xuất
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                <h1 class="page-title"><?php echo $data['title'] ?? 'Dashboard'; ?></h1>
            </div>

            <div class="d-flex align-items-center">
                <div class="text-end me-2 d-none d-md-block">
                    <div class="small fw-bold text-dark"><?php echo $_SESSION['user']['full_name'] ?? 'Administrator'; ?></div>
                    <div class="x-small text-muted" style="font-size: 0.75rem;">Admin Access</div>
                </div>
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['user']['username'] ?? 'A', 0, 1)); ?>
                </div>
            </div>
        </nav>

        <!-- Content Flow -->
        <div class="content-wrapper fade-in-up">
            <?php
            // Alert Messages
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success alert-custom alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="bg-success text-white rounded-circle p-2 me-3"><i class="fas fa-check"></i></div>
                            <div>
                                <h6 class="mb-0 fw-bold">Thành công!</h6>
                                <div class="small">' . $_SESSION['success'] . '</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>';
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger alert-custom alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger text-white rounded-circle p-2 me-3"><i class="fas fa-exclamation"></i></div>
                            <div>
                                <h6 class="mb-0 fw-bold">Lỗi!</h6>
                                <div class="small">' . $_SESSION['error'] . '</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>';
                unset($_SESSION['error']);
            }
            ?>

            <?php if (isset($content)) echo $content; ?>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar Code
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.add('active');
            document.getElementById('sidebarOverlay').classList.add('active');
        });

        document.getElementById('sidebarOverlay')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });

        // Auto hide alerts
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
