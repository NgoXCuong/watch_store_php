<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'Watch Store - Đồng hồ chính hãng'; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/img/logo.svg">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            /* Luxury Black/Gold Theme */
            --primary-color: #1a1a1a;  /* Deep Black/Grey */
            --secondary-color: #c9a961; /* Gold */
            --secondary-dark: #b08d44;  /* Darker Gold */
            --accent-color: #ffffff;    /* White accents */
            
            --success-color: #4ecdc4;
            --warning-color: #c9a961;
            --danger-color: #e74c3c;
            
            --dark-color: #000000;
            --light-color: #f8f9fa;
            --border-color: #e5e5e5;
            
            --font-heading: 'Inter', sans-serif;
            --font-body: 'Inter', sans-serif;
            
            --shadow-soft: 0 10px 40px -10px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            color: var(--primary-color);
            font-weight: 700;
        }

        /* Header Styles */
        .header {
            background-color: white;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 2px 20px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }

        .header-content {
            max-width: 100%;
            margin: 0 auto;
            padding: 0.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 900;
            text-decoration: none;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: -1px;
            text-transform: uppercase;
        }
        
        .logo i {
            color: var(--secondary-color);
        }

        .logo:hover {
            color: var(--primary-color);
            transform: scale(1.02);
            transition: transform 0.3s ease;
        }

        .nav {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav a {
            font-family: var(--font-body);
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding: 0.5rem 0;
            transition: color 0.3s ease;
        }

        .nav a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--secondary-color);
            transition: width 0.3s ease;
        }

        .nav a:hover::after, .nav a.active::after {
            width: 100%;
        }

        .cart-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }

        /* Buttons */
        .btn-custom {
            padding: 0.8rem 2rem;
            border-radius: 0; /* Square elegant edges */
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s ease;
            position: relative;
            z-index: 1;
            overflow: hidden;
            display: inline-block;
            text-decoration: none;
            border: 1px solid var(--primary-color);
            color: var(--secondary-color);
            background: var(--primary-color);
            
        }

        .btn-primary-custom {
            background: var(--accent-color);
            color: var(--secondary-color);
            border-color: var(--primary-color);
        }

        .btn-primary-custom:hover {
            background: transparent;
            color: var(--primary-color);
        }
        
        .btn-outline-custom {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline-custom:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Search Form */
        .search-form {
            position: relative;
            margin-right: 0.5rem;
        }

        .search-input {
            border: none;
            border-bottom: 1px solid var(--border-color);
            background: transparent;
            border-radius: 0;
            padding: 0.5rem 2rem 0.5rem 0.5rem;
            width: 200px;
            font-family: var(--font-body);
            font-size: 0.9rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            width: 250px;
        }

        .search-btn {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--primary-color);
            cursor: pointer;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .search-btn:hover {
            color: var(--secondary-color);
        }

        /* Hero Section - Luxury Style */
        .hero {
            position: relative;
            min-height: 80vh;
            display: flex;
            align-items: center;
            background-color: #f7f7f7;
            overflow: hidden;
        }
        
        /* Replace vibrant gradient with subtle elegant background */
        .hero::before {
            content: '';
            position: absolute;
            width: 40%;
            height: 100%;
            right: 0;
            top: 0;
            background-color: #f2f0eb; /* Beige/Cream tone */
            z-index: 0;
            clip-path: polygon(20% 0, 100% 0, 100% 100%, 0% 100%);
        }

        .hero-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 4rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .hero-text {
            width: 50%;
        }

        .hero-title-main {
            display: block;
            font-family: var(--font-heading);
            font-size: 5rem;
            line-height: 1.1;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .hero-title-sub {
            display: block;
            font-family: var(--font-body);
            font-size: 1.5rem;
            letter-spacing: 5px;
            color: var(--secondary-color);
            text-transform: uppercase;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .hero-description {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 3rem;
            max-width: 500px;
            font-weight: 300;
        }

        /* Product Cards - Minimalist Luxury */
        .product-card {
            background: white;
            border: 1px solid transparent;
            transition: all 0.4s ease;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            border-color: #f0f0f0;
            box-shadow: var(--shadow-soft);
        }

        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            background-color: #f9f9f9;
            padding-top: 100%; /* Square Aspect Ratio */
        }
        
        .product-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Use contain to see full watch or cover for impactful style */
            transition: transform 0.6s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        .product-info {
            padding: 1.5rem 0;
            text-align: center;
        }

        .product-category {
            color: var(--secondary-color);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .product-name {
            font-family: var(--font-heading);
            font-size: 1.2rem;
            color: var(--primary-color);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }
        
        .product-name:hover {
            color: var(--secondary-color);
        }

        .product-price {
            font-family: var(--font-body);
            color: #333;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Footer - Dark Luxury */
        .footer {
            background-color: var(--primary-color);
            color: #ccc;
            padding: 5rem 0 2rem;
            margin-top: 5rem;
        }

        .footer h3 {
            color: var(--secondary-color);
            font-size: 1.5rem;
            margin-bottom: 2rem;
            letter-spacing: 1px;
        }

        .footer a {
            color: #999;
            text-decoration: none;
            transition: color 0.3s;
            font-size: 0.95rem;
            display: block;
            margin-bottom: 0.8rem;
        }

        .footer a:hover {
            color: var(--secondary-color);
            padding-left: 5px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 3rem;
            padding-top: 2rem;
            text-align: center;
            font-size: 0.85rem;
            color: #666;
        }

        /* Utilities */
        .section-title h2 {
            font-size: 3rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .section-subtitle {
            display: block;
            color: var(--secondary-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        /* Alert Stylings */
        .alert-custom {
            border-radius: 0;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .hero-title-main { font-size: 3rem; }
            .hero-content { flex-direction: column; text-align: center; padding-top: 3rem; }
            .hero-text { width: 100%; margin-bottom: 3rem; }
            .hero::before { width: 100%; height: 50%; bottom: 0; top: auto; clip-path: none; }
            .nav { display: none; } /* Simplified for example */
        }
        
        /* Include Utilities */
        <?php include __DIR__ . '/utilities.css'; ?>
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <a href="<?php echo BASE_URL; ?>/" class="logo">
                <img src="<?php echo BASE_URL; ?>/assets/img/logo.svg" alt="Watch Store" style="height: 60px; width: auto;">
            </a>

            <!-- Desktop Nav -->
            <nav class="nav">
                <a href="<?php echo BASE_URL; ?>/" class="<?php echo ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/watch_store/public/') ? 'active' : ''; ?>">Trang chủ</a>
                <a href="<?php echo BASE_URL; ?>/products" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/products') !== false ? 'active' : ''; ?>">Bộ sưu tập</a>
                <a href="<?php echo BASE_URL; ?>/brands" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/brands') !== false ? 'active' : ''; ?>">Thương hiệu</a>
                <a href="<?php echo BASE_URL; ?>/about" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/about') !== false ? 'active' : ''; ?>">Về chúng tôi</a>
            </nav>

            <!-- User Actions -->
            <div class="nav">
                <!-- Search Bar -->
                <form action="<?php echo BASE_URL; ?>/products" method="GET" class="search-form d-none border  d-lg-block">
                    <input type="text" name="search" class="search-input" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                </form>
                <a href="<?php echo BASE_URL; ?>/cart" class="cart-link" title="Giỏ hàng">
                    <i class="fas fa-shopping-bag" style="font-size: 1.2rem;"></i>
                    <span class="cart-count" id="cart-count" style="display: none;">0</span>
                </a>
                
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="dropdown">
                        <a href="#" class="cart-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="far fa-user" style="font-size: 1.2rem;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-0 p-2">
                            <li class="px-3 py-2 text-muted small border-bottom mb-2">Xin chào, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/profile">Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/orders">Lịch sử đơn hàng</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/reviews/myReviews">Đánh giá của tôi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/auth/logout">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/auth/login" class="btn-custom btn-primary-custom py-2 px-4" style="font-size: 0.75rem;">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?php
        $is_home = ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/watch_store/public/' || $_SERVER['REQUEST_URI'] === '/watch_store/public/index.php');
        $wrapper_style = $is_home ? 'width: 100%; padding: 0;' : 'max-width: 1400px; margin: 0 auto; padding: 2rem;';
        ?>
        <div class="content-wrapper" style="<?php echo $wrapper_style; ?>">
            <?php
            // Display alert messages
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success alert-custom alert-dismissible fade show mb-4" role="alert">
                        <i class="far fa-check-circle me-2"></i>' . $_SESSION['success'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>';
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger alert-custom alert-dismissible fade show mb-4" role="alert">
                        <i class="far fa-times-circle me-2"></i>' . $_SESSION['error'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>';
                unset($_SESSION['error']);
            }
            ?>

            <?php
            // Specific Page Content
            if (isset($content)) {
                echo $content;
            }
            ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="content-wrapper" style="max-width: 1400px; margin: 0 auto; padding: 0 4rem;">
            <div class="row">
                <div class="col-md-4 mb-5">
                    <h3>WATCH STORE</h3>
                    <p class="text-secondary mb-4">Đẳng cấp thời gian, khẳng định vị thế. Chúng tôi mang đến những chiếc đồng hồ chính hãng với chất lượng tuyệt hảo nhất.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-5">
                    <h3>Mua sắm</h3>
                    <a href="#">Đồng hồ nam</a>
                    <a href="#">Đồng hồ nữ</a>
                    <a href="#">Thương hiệu</a>
                    <a href="#">Phụ kiện</a>
                </div>
                <div class="col-md-2 mb-5">
                    <h3>Thông tin</h3>
                    <a href="#">Về chúng tôi</a>
                    <a href="#">Liên hệ</a>
                    <a href="#">Blog</a>
                    <a href="#">Tuyển dụng</a>
                </div>
                <div class="col-md-4 mb-5">
                    <h3>Dịch vụ khách hàng</h3>
                    <a href="#">Chính sách bảo hành</a>
                    <a href="#">Chính sách đổi trả</a>
                    <a href="#">Chính sách vận chuyển</a>
                    <a href="#">Câu hỏi thường gặp</a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-md-start">
                        &copy; 2025     Watch Store.
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <i class="fab fa-cc-visa fa-2x me-2 text-secondary"></i>
                        <i class="fab fa-cc-mastercard fa-2x me-2 text-secondary"></i>
                        <i class="fab fa-cc-paypal fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cart Count Update Logic
        <?php if (isset($_SESSION['user'])): ?>
        async function updateCartCount() {
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/cart/count');
                const data = await response.json();
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) {
                    if (data.count > 0) {
                        cartCountEl.textContent = data.count;
                        cartCountEl.style.display = 'flex';
                    } else {
                        cartCountEl.style.display = 'none';
                    }
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }
        updateCartCount();
        setInterval(updateCartCount, 30000);
        <?php endif; ?>

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // Loading State for Buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const btn = this.querySelector('button[type="submit"]');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                }
            });
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
