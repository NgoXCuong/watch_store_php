<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang | Watch Store</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/img/logo.svg">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1a1a1a;
            --secondary-color: #c9a961;
            --font-main: 'Inter', sans-serif;
        }
        
        body {
            font-family: var(--font-main);
            background-color: #f8f9fa;
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        
        .letter-spacing-1 { letter-spacing: 1px; }
        .letter-spacing-2 { letter-spacing: 2px; }
        .letter-spacing-neg-5 { letter-spacing: -5px; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">

    <div class="container py-5">
        <div class="row w-100 justify-content-center m-0">
            <div class="col-lg-6 text-center">
                <div class="position-relative mb-5">
                    <h1 class="display-1 fw-bold text-dark mb-0" style="font-size: 10rem; letter-spacing: -5px; line-height: 1;">404</h1>
                    <div class="position-absolute top-50 start-50 translate-middle w-100">
                        <span class="bg-white text-dark px-3 py-1 border border-dark rounded-pill fw-bold text-uppercase letter-spacing-2 shadow-sm" style="font-size: 1rem;">
                            Page Not Found
                        </span>
                    </div>
                </div>
                
                <h2 class="h3 fw-bold text-uppercase letter-spacing-1 mb-4">Đã có lỗi xảy ra!</h2>
                <p class="text-muted mb-5 lead fw-light px-md-5">
                    Rất tiếc, trang bạn đang tìm kiếm không tồn tại, đã bị di chuyển hoặc địa chỉ URL không chính xác. Hãy kiểm tra lại hoặc quay về trang chủ.
                </p>
                
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo BASE_URL; ?>/" class="btn btn-dark rounded-0 px-5 py-3 text-uppercase fw-bold letter-spacing-1 shadow-lg hover-lift">
                        <i class="fas fa-home me-2"></i> Trang chủ
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-dark rounded-0 px-5 py-3 text-uppercase fw-bold letter-spacing-1 hover-lift">
                        <i class="fas fa-arrow-left me-2"></i> Quay lại
                    </a>
                </div>

                <div class="mt-5 pt-5 border-top border-light">
                    <p class="text-muted small text-uppercase letter-spacing-1 mb-0">Watch Store Premium</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
