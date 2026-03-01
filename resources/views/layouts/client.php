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

        /* Base Button Overrides for consistency with Theme */
        .btn-dark {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .btn-dark:hover, .btn-dark:focus, .btn-dark:active {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-dark) !important;
            color: white !important;
        }

        .btn-outline-dark {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
            background-color: transparent !important;
            transition: all 0.3s ease;
        }

        .btn-outline-dark:hover, .btn-outline-dark:focus, .btn-outline-dark:active {
            background-color: var(--primary-color) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-dark) !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--secondary-dark) !important;
            border-color: var(--secondary-dark) !important;
            color: white !important;
        }

        .btn-outline-primary {
            color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
            background-color: transparent !important;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--secondary-color) !important;
            color: white !important;
            border-color: var(--secondary-color) !important;
        }

        /* Legacy Button custom classes (retain if used somewhere) */
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
        
        /* User Dropdown */
        .user-menu-dropdown {
            animation: dropdownFadeIn 0.2s ease;
        }

        .user-menu-dropdown .dropdown-item {
            font-size: 0.95rem;
            transition: all 0.2s;
            border-radius: 4px;
        }

        .user-menu-dropdown .dropdown-item:hover {
            background-color: #f1f1f1;
            color: var(--primary-color) !important;
            transform: translateX(5px);
        }

        .user-menu-dropdown .dropdown-item.text-danger:hover {
            background-color: #ffeaea;
            color: #dc3545 !important;
        }

        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
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
        /* Sidebar Filters */
        .filter-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            color: #666;
            transition: all 0.2s;
            cursor: pointer;
        }

        .filter-item:hover {
            color: var(--secondary-color);
        }

        .filter-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .filter-checkbox {
            width: 18px;
            height: 18px;
            border: 1px solid #ddd;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            transition: all 0.2s;
        }

        .filter-item:hover .filter-checkbox {
            border-color: var(--secondary-color);
        }

        .filter-item.active .filter-checkbox {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .filter-checkbox i {
            display: none;
            font-size: 10px;
        }

        .filter-item.active .filter-checkbox i {
            display: block;
        }

        /* Product Card Enhancements */
        .product-card {
            border: 1px solid #f0f0f0; /* Lighter border */
            transition: all 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.08); /* Highlight shadow */
            transform: translateY(-5px);
            border-color: transparent;
        }

        /* Global Wishlist Badge Button */
        .wishlist-badge-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
        }

        .wishlist-badge-btn:hover {
            color: #dc3545 !important; /* Bootstrap Danger/Red hover */
            transform: scale(1.1);
        }

        .discount-badge {
            background-color: var(--danger-color);
            color: white;
            padding: 4px 8px;
            font-size: 0.8rem;
            font-weight: 600;
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 2;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .product-price-highlight {
            color: var(--danger-color); /* Make price pop */
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        /* Layout Adjustments */
        .sidebar {
            padding-right: 2rem; /* Increase separation */
        }
        
        .breadcrumb-section {
            background-color: white !important; /* Cleaner look */
            border-bottom: 1px solid #f0f0f0 !important;
        }

        /* Search Bar in Header - Enhanced */
        .header-search {
            position: relative;
            width: 350px;
        }
        .header-search .input-group {
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e5e5e5;
        }
        .header-search input {
            background: #f8f9fa;
            border: none;
            font-size: 0.9rem;
            padding-left: 15px;
        }
        .header-search input:focus {
            background: white;
            box-shadow: none;
        }
        .header-search .btn-search {
            background: #f8f9fa;
            border: none;
            color: #999;
            transition: all 0.3s;
        }
        .header-search .btn-search:hover {
            color: var(--secondary-color);
        }
        .header-search .btn-ai {
            background: var(--primary-color);
            color: var(--secondary-color);
            border: none;
            font-weight: 600;
            padding: 0 15px;
            transition: all 0.3s;
        }
        .header-search .btn-ai:hover {
            background: var(--secondary-color);
            color: white;
        }

        <?php include __DIR__ . '/utilities.css'; ?>
        /* V2 Header Styles */
        .topbar-link { transition: color 0.3s; }
        .topbar-link:hover { color: var(--secondary-color) !important; }
        .ai-btn:hover { background-color: var(--secondary-color) !important; color: #fff !important; }
        .action-item { transition: all 0.3s; }
        .action-item:hover { color: var(--secondary-color) !important; }
        .action-item:hover i { color: var(--secondary-color) !important; }
        .main-nav .nav-link { font-size: 0.95rem; letter-spacing: 0.5px; transition: color 0.3s; }
        .main-nav .nav-link:hover { color: var(--secondary-color) !important; }
        .main-nav .active-nav { color: var(--secondary-color) !important; }
        .main-nav .active-nav::after, .main-nav .nav-link:hover::after { 
            content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background-color: var(--secondary-color); 
        }
        .nav-dropdown:hover .dropdown-content { opacity: 1 !important; visibility: visible !important; transform: translateY(0) !important; }
        .hover-bg-light:hover { background-color: #f8f9fa; color: var(--secondary-color) !important; padding-left: 20px !important; transition: all 0.3s; }
        .dropdown-content { transition: all 0.3s; }
        /* Mega Menu Styles */
        .nav-item.position-static { position: static !important; }
        .mega-menu {
            box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .mega-menu-list .dropdown-item-custom {
            display: block;
            padding: 8px 0;
            transition: all 0.3s ease;
            position: relative;
        }
        .mega-menu-list .dropdown-item-custom:hover {
            color: var(--secondary-color) !important;
            transform: translateX(8px);
        }
        .columns-2 { column-count: 2; column-gap: 20px; }
        .shadow-text { text-shadow: 0 2px 4px rgba(0,0,0,0.5); }
        .hover-scale { transition: transform 0.5s ease; }
        .group-hover-parent:hover .hover-scale { transform: scale(1.1); }
    </style>
</head>
<body>
    <!-- Header Area -->
    <div class="header-wrapper">
        <!-- Topbar -->
        <div class="topbar py-1 d-none d-lg-block border-bottom bg-light">
            <div class="container d-flex justify-content-between align-items-center" style="max-width: 1400px; font-size: 0.8rem;">
                <div class="d-flex gap-4 fw-bold text-secondary">
                    <span><i class="fas fa-map-marker-alt me-1 text-warning"></i> Hệ thống 20 Showroom toàn quốc</span>
                    <span><i class="fas fa-phone-alt me-1 text-warning"></i> Hotline tư vấn: <b class="text-danger fs-6 ms-1">1900.1234</b></span>
                </div>
                <div class="d-flex gap-4">
                    <a href="https://www.facebook.com/watchstore.vn" target="_blank" class="text-muted text-decoration-none topbar-link" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/watchstore.vietnam" target="_blank" class="text-muted text-decoration-none topbar-link" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com/@watchstore.official" target="_blank" class="text-muted text-decoration-none topbar-link" title="Youtube">
                        <i class="fab fa-youtube"></i>
                    </a>

                    <?php if (!isset($_SESSION['user'])): ?>
                        <span class="text-muted">|</span> <a href="<?php echo BASE_URL; ?>/auth/login" class="text-muted text-decoration-none topbar-link">
                            <i class="far fa-user-circle me-1"></i> Đăng nhập / Đăng ký
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <header class="main-header bg-white py-3 border-bottom border-light position-relative" style="z-index: 1030;">
            <div class="container d-flex justify-content-between align-items-center" style="max-width: 1400px;">
                <!-- Logo -->
                <a href="<?php echo BASE_URL; ?>/" class="logo-wrapper text-decoration-none">
                    <img src="<?php echo BASE_URL; ?>/assets/img/logo.svg" alt="Watch Store" style="height: 55px; width: auto; object-fit: contain;">
                </a>

                <!-- Middle Search -->
                <div class="search-wrapper mx-4 flex-grow-1 d-none d-lg-block" style="max-width: 600px;">
                    <form action="<?php echo BASE_URL; ?>/products" method="GET" class="w-100 m-0">
                        <div class="input-group search-group position-relative" style="border: 0.5px solid var(--secondary-color); border-radius: 30px; overflow: hidden; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            
                            <!-- Search Input with animated placeholder via JS -->
                            <input type="text" name="search" id="animatedSearchInput" class="form-control border-0 shadow-none px-4 fw-medium" 
                                   placeholder="" 
                                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                                   style="height: 48px; font-size: 0.95rem; background: transparent; z-index: 2;">
                            
                            <!-- Normal Search Button -->
                            <button class="btn border-0 px-3 hover-secondary" type="submit" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tìm kiếm cơ bản" style="background: transparent; z-index: 2;">
                                <i class="fas fa-search text-muted fs-5"></i>
                            </button>
                            
                            <!-- AI Search Button (Distinctive) -->
                            <div class="position-relative" style="z-index: 2;">
                                <button class="btn border-0 px-4 fw-bold ai-btn text-white h-100 d-flex align-items-center gap-2" 
                                        type="submit" name="ai_search" value="1" 
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tìm kiếm thông minh (Mô tả yêu cầu chi tiết)"
                                        style="background: linear-gradient(45deg, var(--secondary-color), #f39c12); border-radius: 0 30px 30px 0; transition: all 0.3s; box-shadow: -2px 0 10px rgba(0,0,0,0.1);">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                    <span class="d-none d-xl-inline">Trợ lý AI</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Right Actions -->
                <div class="header-actions d-flex align-items-center gap-4">
                    <a href="<?php echo BASE_URL; ?>/wishlist" class="action-item text-decoration-none d-flex flex-column align-items-center text-dark">
                        <div class="position-relative mb-1">
                            <i class="far fa-heart fs-4"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-flex align-items-center justify-content-center" id="wishlist-count" style="font-size: 0.6rem; width: 14px; height: 14px; display: none !important;">0</span>
                        </div>
                        <span class="small fw-bold d-none d-xl-block" style="font-size: 0.75rem;">Yêu thích</span>
                    </a>

                    <a href="<?php echo BASE_URL; ?>/cart" class="action-item text-decoration-none d-flex flex-column align-items-center text-dark">
                        <div class="position-relative mb-1">
                            <i class="fas fa-shopping-bag fs-4"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count" style="font-size: 0.6rem; display: none;">0</span>
                        </div>
                        <span class="small fw-bold d-none d-xl-block" style="font-size: 0.75rem;">Giỏ hàng</span>
                    </a>

                    <?php if (isset($_SESSION['user'])): ?>
                        <div class="action-item dropdown">
                            <a href="#" class="text-decoration-none d-flex flex-column align-items-center text-dark" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="0,0">
                                <i class="far fa-user fs-4 mb-1"></i>
                                <span class="small fw-bold d-none d-xl-block text-truncate" style="font-size: 0.75rem; max-width: 80px;"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-0 p-0 user-menu-dropdown m-0" style="min-width: 250px;">
                                <li class="p-3 bg-light border-bottom">
                                    <div class="small text-muted fw-bold letter-spacing-1 mb-1">Xin chào</div>
                                    <div class="fw-bold fs-6 text-dark"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></div>
                                </li>
                                <li class="p-2">
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-3 text-secondary" href="<?php echo BASE_URL; ?>/profile"><i class="far fa-id-card text-center" style="width: 20px;"></i><span>Hồ sơ cá nhân</span></a>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-3 text-secondary" href="<?php echo BASE_URL; ?>/orders"><i class="fas fa-history text-center" style="width: 20px;"></i><span>Lịch sử đơn hàng</span></a>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-3 text-secondary" href="<?php echo BASE_URL; ?>/reviews/my-reviews"><i class="far fa-star text-center" style="width: 20px;"></i><span>Đánh giá của tôi</span></a>
                                </li>
                                <li class="border-top p-2 bg-light">
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-3 text-danger fw-bold" href="<?php echo BASE_URL; ?>/auth/logout"><i class="fas fa-sign-out-alt text-center" style="width: 20px;"></i><span>Đăng xuất</span></a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Navigation Bar -->
        <nav class="main-nav sticky-top shadow-sm" style="background-color: var(--primary-color);">
            <div class="container position-relative" style="max-width: 1400px;">
                <ul class="d-flex list-unstyled gap-3 m-0 justify-content-center align-items-center">
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/" class="nav-link text-white fw-bold py-3 mx-2 mx-xl-3 px-0 position-relative d-flex align-items-center <?php echo ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/watch_store/public/') ? 'active-nav' : ''; ?>">
                            Trang chủ
                        </a>
                    </li>
                    <li class="nav-item nav-dropdown position-static">
                        <a href="<?php echo BASE_URL; ?>/products" class="nav-link text-white fw-bold py-3 mx-2 mx-xl-3 px-0 position-relative d-flex align-items-center <?php echo strpos($_SERVER['REQUEST_URI'], '/products') !== false && !isset($_GET['category']) ? 'active-nav' : ''; ?>">
                            Đồng hồ <i class="fas fa-chevron-down ms-2 small d-none d-lg-block" style="font-size: 0.7rem;"></i>
                        </a>
                        <div class="dropdown-content position-absolute w-100 bg-white shadow-lg border-top border-warning mega-menu" style="border-top-width: 3px !important; z-index: 1000; opacity: 0; visibility: hidden; top: 100%; left: 0; transform: translateY(15px); transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out, visibility 0.3s;">
                            <div class="container py-4">
                                <div class="row g-4 d-flex justify-content-center">
                                    <!-- Theo giới tính / đối tượng -->
                                    <div class="col-lg-2 col-md-4">
                                        <h6 class="fw-bold text-dark mb-3 letter-spacing-1 border-bottom pb-2">Đối tượng</h6>
                                        <ul class="list-unstyled mega-menu-list">
                                            <li><a href="<?php echo BASE_URL; ?>/products?category=6" class="text-secondary text-decoration-none dropdown-item-custom"><i class="fas fa-male me-2 text-warning" style="width:15px"></i>Nam</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?category=7" class="text-secondary text-decoration-none dropdown-item-custom"><i class="fas fa-female me-2 text-warning" style="width:15px"></i>Nữ</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?category=8" class="text-secondary text-decoration-none dropdown-item-custom"><i class="fas fa-user-friends me-2 text-warning" style="width:15px"></i>Cặp đôi</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?category=9" class="text-secondary text-decoration-none dropdown-item-custom"><i class="fas fa-genderless me-2 text-warning" style="width:15px"></i>Unisex</a></li>
                                        </ul>
                                    </div>
                                    
                                    <!-- Theo thương hiệu -->
                                    <div class="col-lg-4 col-md-4">
                                        <h6 class="fw-bold text-dark mb-3 letter-spacing-1 border-bottom pb-2">Thương hiệu Nổi bật</h6>
                                        <ul class="list-unstyled mega-menu-list columns-2">
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=rolex" class="text-secondary text-decoration-none dropdown-item-custom">Rolex</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=hublot" class="text-secondary text-decoration-none dropdown-item-custom">Hublot</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=casio" class="text-secondary text-decoration-none dropdown-item-custom">Casio</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=seiko" class="text-secondary text-decoration-none dropdown-item-custom">Seiko</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=citizen" class="text-secondary text-decoration-none dropdown-item-custom">Citizen</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=orient" class="text-secondary text-decoration-none dropdown-item-custom">Orient</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=tissot" class="text-secondary text-decoration-none dropdown-item-custom">Tissot</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?search=omega" class="text-secondary text-decoration-none dropdown-item-custom">Omega</a></li>
                                        </ul>
                                    </div>

                                    <!-- Theo mức giá -->
                                    <div class="col-lg-3 col-md-4">
                                        <h6 class="fw-bold text-dark mb-3 letter-spacing-1 border-bottom pb-2">Mức giá</h6>
                                        <ul class="list-unstyled mega-menu-list">
                                            <li><a href="<?php echo BASE_URL; ?>/products?max_price=2000000" class="text-secondary text-decoration-none dropdown-item-custom">Dưới 2 triệu</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?min_price=2000000&max_price=5000000" class="text-secondary text-decoration-none dropdown-item-custom">Từ 2 - 5 triệu</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?min_price=5000000&max_price=10000000" class="text-secondary text-decoration-none dropdown-item-custom">Từ 5 - 10 triệu</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?min_price=10000000&max_price=20000000" class="text-secondary text-decoration-none dropdown-item-custom">Từ 10 - 20 triệu</a></li>
                                            <li><a href="<?php echo BASE_URL; ?>/products?min_price=20000000" class="text-secondary text-decoration-none dropdown-item-custom">Trên 20 triệu</a></li>
                                        </ul>
                                    </div>

                                    <!-- Hình ảnh nổi bật -->
                                    <div class="col-lg-3 d-none d-lg-block">
                                        <div class="mega-menu-banner position-relative overflow-hidden group-hover-parent" style="border-radius: 8px; height: 100%; min-height: 200px; max-height: 250px;">
                                            <img src="<?php echo BASE_URL; ?>/assets/img/banner-1.jpg" alt="Collection" class="w-100 h-100 object-fit-cover hover-scale" style="position: absolute; top:0; left:0;">
                                            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                                <h5 class="text-white letter-spacing-1 mb-1 shadow-text fs-6">Bộ Sưu Tập Mới</h5>
                                                <a href="<?php echo BASE_URL; ?>/products?sort=latest" class="text-warning text-decoration-none small fw-bold letter-spacing-1" style="font-size: 0.75rem;">Khám phá ngay <i class="fas fa-arrow-right ms-1"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/products?category=6" class="nav-link text-white fw-bold py-3 mx-2 mx-xl-3 px-0 position-relative d-flex align-items-center <?php echo isset($_GET['category']) && $_GET['category'] == '6' ? 'active-nav' : ''; ?>">
                            Đồng hồ Nam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/products?category=7" class="nav-link text-white fw-bold py-3 mx-2 mx-xl-3 px-0 position-relative d-flex align-items-center <?php echo isset($_GET['category']) && $_GET['category'] == '7' ? 'active-nav' : ''; ?>">
                            Đồng hồ Nữ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/brands" class="nav-link text-white fw-bold py-3 mx-2 mx-xl-3 px-0 position-relative d-flex align-items-center <?php echo strpos($_SERVER['REQUEST_URI'], '/brands') !== false ? 'active-nav' : ''; ?>">
                            Thương hiệu
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/about" class="nav-link text-white fw-bold py-3 mx-2 mx-xl-3 px-0 position-relative d-flex align-items-center <?php echo strpos($_SERVER['REQUEST_URI'], '/about') !== false ? 'active-nav' : ''; ?>">
                            Về chúng tôi
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <?php
        $is_home = ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/watch_store/public/' || $_SERVER['REQUEST_URI'] === '/watch_store/public/index.php');
        $wrapper_style = $is_home ? 'width: 100%; padding: 0;' : 'max-width: 1400px; margin: 0 auto; padding: 2rem;';
        ?>
        <div class="content-wrapper" style="<?php echo $wrapper_style; ?>">
            <?php
            // Display alert messages is handled by Toasts now
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
                        <a href="https://www.facebook.com/watchstore.vn" target="_blank" class="text-muted text-decoration-none topbar-link" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/watchstore.vietnam" target="_blank" class="text-muted text-decoration-none topbar-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@watchstore.official" target="_blank" class="text-muted text-decoration-none topbar-link" title="Youtube">
                            <i class="fab fa-youtube"></i>
                        </a>
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

    <!-- Toast Container -->
    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1060; pointer-events: none;"></div>

    <!-- CSS for Scroll Reveal -->
    <style>
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Chat Widget Styles */
        .chat-widget-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--secondary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            z-index: 1050;
            transition: transform 0.3s ease;
        }
        .chat-widget-btn:hover {
            transform: scale(1.1);
        }
        .chat-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }
        .chat-window.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .chat-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: var(--font-heading);
        }
        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f8f9fa;
        }
        .chat-message {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 15px;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .chat-message.bot {
            background: white;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 2px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .chat-message.user {
            background: var(--secondary-color);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 2px;
        }
        .chat-input-area {
            padding: 15px;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }
        .chat-input {
            flex: 1;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 8px 15px;
            outline: none;
            font-size: 0.9rem;
        }
        .chat-input:focus {
            border-color: var(--secondary-color);
        }
        .chat-send-btn {
            background: var(--secondary-color);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .chat-send-btn:hover {
            transform: scale(1.05);
        }
        .typing-indicator {
            display: none;
            align-items: center;
            gap: 4px;
            padding: 10px 15px;
            background: white;
            align-self: flex-start;
            border-radius: 15px;
            border-bottom-left-radius: 2px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .typing-dot {
            width: 6px;
            height: 6px;
            background: #999;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>

    <!-- Chatbot Widget -->
    <div class="chat-widget-btn" onclick="toggleChat()">
        <i class="fas fa-comment-dots"></i>
    </div>
    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-robot text-warning"></i>
                <h6 class="mb-0 text-white fw-bold">Trợ lí AI Watch Store</h6>
            </div>
            <button onclick="toggleChat()" class="btn-close btn-close-white" style="font-size: 0.8rem;"></button>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="chat-message bot">
                Xin chào! Tôi là Trợ lí ảo của Watch Store. Bạn cần tìm mẫu đồng hồ nào hôm nay? (Ví dụ: "Tôi cần tìm đồng hồ nam tầm giá 15 triệu phong cách thể thao")
            </div>
            <div class="typing-indicator" id="typingIndicator">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" class="chat-input" id="chatInput" placeholder="Nhập tin nhắn..." onkeypress="handleChatKeyPress(event)">
            <button class="chat-send-btn" onclick="sendChatMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- Scroll Reveal Logic ---
        document.addEventListener('DOMContentLoaded', function() {
            const reveals = document.querySelectorAll('.reveal');

            const revealOnScroll = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target); // Only animate once
                    }
                });
            }, {
                threshold: 0.15, // Trigger when 15% of element is visible
                rootMargin: "0px 0px -50px 0px"
            });

            reveals.forEach(reveal => {
                revealOnScroll.observe(reveal);
            });
        });

        // --- Toast Notification Logic ---
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            
            // Create toast element
            const toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center border-0 mb-2 fade show';
            toastEl.style.pointerEvents = 'auto'; // Re-enable clicks
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            // Set colors based on type
            if (type === 'success') {
                toastEl.classList.add('bg-dark', 'text-white');
                toastEl.style.borderLeft = '5px solid var(--secondary-color)';
            } else if (type === 'error') {
                toastEl.classList.add('bg-danger', 'text-white');
                toastEl.style.borderLeft = '5px solid #fff';
            } else {
                toastEl.classList.add('bg-white', 'text-dark');
                toastEl.style.borderLeft = '5px solid var(--primary-color)';
            }

            // Icon selection
            const icon = type === 'success' ? '<i class="fas fa-check-circle me-2 text-warning"></i>' 
                       : (type === 'error' ? '<i class="fas fa-exclamation-circle me-2"></i>' 
                       : '<i class="fas fa-info-circle me-2"></i>');

            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center" style="font-size: 0.95rem;">
                        ${icon}
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            container.appendChild(toastEl);

            // Initialize Bootstrap Toast (Optional if you want drag/drop behavior, but manual timeout is simpler here)
            // Using simple timeout for auto-remove
            setTimeout(() => {
                toastEl.classList.remove('show');
                setTimeout(() => toastEl.remove(), 500); // Wait for fade out transition
            }, 3000);
        }

        // Display PHP Session Messages as Toasts
        <?php if (isset($_SESSION['success'])): ?>
            showToast('<?php echo $_SESSION['success']; ?>', 'success');
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            showToast('<?php echo $_SESSION['error']; ?>', 'error');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>


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

        // Wishlist Count & Global Toggle Logic
        <?php if (isset($_SESSION['user'])): ?>
        async function updateWishlistCount() {
            try {
                const response = await fetch('<?php echo BASE_URL; ?>/wishlist/count');
                const data = await response.json();
                const wishlistCountEl = document.getElementById('wishlist-count');
                if (wishlistCountEl) {
                    if (data.count > 0) {
                        wishlistCountEl.textContent = data.count;
                        wishlistCountEl.style.setProperty('display', 'flex', 'important');
                    } else {
                        wishlistCountEl.style.setProperty('display', 'none', 'important');
                    }
                }
            } catch (error) {
                console.error('Error updating wishlist count:', error);
            }
        }
        updateWishlistCount();
        setInterval(updateWishlistCount, 30000);
        <?php endif; ?>

        async function toggleWishlist(productId, btnElement) {
            try {
                const formData = new FormData();
                formData.append('product_id', productId);
                const response = await fetch('<?php echo BASE_URL; ?>/wishlist/toggle', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success) {
                    showToast(data.message, 'success');
                    <?php if (isset($_SESSION['user'])): ?>
                    updateWishlistCount();
                    <?php endif; ?>
                    
                    if (btnElement) {
                        if (data.action === 'added') {
                            btnElement.classList.add('text-danger');
                            btnElement.classList.remove('text-muted');
                            const icon = btnElement.querySelector('i');
                            if(icon) {
                                icon.classList.remove('far');
                                icon.classList.add('fas');
                            }
                        } else {
                            btnElement.classList.remove('text-danger');
                            btnElement.classList.add('text-muted');
                            const icon = btnElement.querySelector('i');
                            if(icon) {
                                icon.classList.remove('fas');
                                icon.classList.add('far');
                            }
                        }
                    }
                } else {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        showToast(data.message, 'error');
                    }
                }
            } catch(e) {
                console.error('Error toggling wishlist', e);
                showToast('Có lỗi xảy ra', 'error');
            }
        }

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // AI Chatbot Logic
        let chatHistory = [];
        
        function toggleChat() {
            const chatWindow = document.getElementById('chatWindow');
            chatWindow.classList.toggle('open');
            if (chatWindow.classList.contains('open')) {
                document.getElementById('chatInput').focus();
            }
        }

        function handleChatKeyPress(event) {
            if (event.key === 'Enter') {
                sendChatMessage();
            }
        }

        async function sendChatMessage() {
            const inputEl = document.getElementById('chatInput');
            const message = inputEl.value.trim();
            if (!message) return;

            // Clear input
            inputEl.value = '';

            // Add user message to UI
            appendMessage(message, 'user');

            // Show typing indicator
            const typingIndicator = document.getElementById('typingIndicator');
            const messagesContainer = document.getElementById('chatMessages');
            
            messagesContainer.appendChild(typingIndicator); // move to bottom
            typingIndicator.style.display = 'flex';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                const response = await fetch('<?php echo BASE_URL; ?>/api/chat', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message, history: chatHistory })
                });

                const data = await response.json();
                
                typingIndicator.style.display = 'none';

                if (data.reply) {
                    appendMessage(data.reply, 'bot');
                    // Save to history
                    chatHistory.push({ role: 'user', content: message });
                    chatHistory.push({ role: 'bot', content: data.reply });
                } else {
                    appendMessage("Xin lỗi, hệ thống AI đang gặp lỗi. Vui lòng thử lại sau.", 'bot');
                }
            } catch (error) {
                console.error("Chat Error:", error);
                typingIndicator.style.display = 'none';
                appendMessage("Không thể kết nối với hệ thống AI.", 'bot');
            }
        }

        function appendMessage(text, role) {
            const messagesContainer = document.getElementById('chatMessages');
            const typingIndicator = document.getElementById('typingIndicator');
            
            const msgEl = document.createElement('div');
            msgEl.className = 'chat-message ' + role;
            msgEl.innerHTML = text; // Allow HTML from AI response
            
            messagesContainer.insertBefore(msgEl, typingIndicator);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Search Bar Typing Animation
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('animatedSearchInput');
            if (!searchInput) return;
            
            // Only animate if input is empty
            if (searchInput.value.trim() !== '') return;

            const phrases = [
                "Tìm 'Đồng hồ nam lịch lãm' bằng AI...",
                "Tìm 'Seiko dưới 5 triệu'...",
                "Bạn cần tìm đồng hồ gì hôm nay?",
                "Gõ yêu cầu chi tiết để AI hỗ trợ..."
            ];
            
            let phraseIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            let typingSpeed = 100;
            
            function typePlaceholder() {
                const currentPhrase = phrases[phraseIndex];
                
                if (isDeleting) {
                    searchInput.setAttribute('placeholder', currentPhrase.substring(0, charIndex - 1));
                    charIndex--;
                    typingSpeed = 50;
                } else {
                    searchInput.setAttribute('placeholder', currentPhrase.substring(0, charIndex + 1));
                    charIndex++;
                    typingSpeed = 100;
                }

                if (!isDeleting && charIndex === currentPhrase.length) {
                    isDeleting = true;
                    typingSpeed = 2000; // Pause at end of phrase
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    phraseIndex = (phraseIndex + 1) % phrases.length;
                    typingSpeed = 500; // Pause before new phrase
                }

                setTimeout(typePlaceholder, typingSpeed);
            }

            // Start animation
            setTimeout(typePlaceholder, 1000);
            
            // Stop/Start on focus
            searchInput.addEventListener('focus', function() {
                this.setAttribute('placeholder', 'Nhập từ khóa hoặc câu hỏi cho AI...');
            });
            
            searchInput.addEventListener('blur', function() {
                if(this.value.trim() === '') {
                    charIndex = 0;
                    isDeleting = false;
                    phraseIndex = 0;
                }
            });
            
            // Tooltip Initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
