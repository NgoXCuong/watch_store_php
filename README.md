watch-store-project/
│
├── .env                    # (Quan trọng) Lưu cấu hình mật: DB Pass, API Key (VNPAY, Cloudinary)
├── .gitignore              # Chỉ định file không up lên git (vd: .env, vendor/)
├── composer.json           # Khai báo thư viện & Autoload PSR-4
├── composer.lock           # Khóa phiên bản thư viện
├── README.md               # Hướng dẫn cài đặt dự án
│
├── app/                    # [CORE LOGIC] Chứa toàn bộ mã nguồn xử lý
│   ├── Config/             # Class chứa cấu hình (đọc từ .env)
│   ├── Controllers/        # Xử lý luồng dữ liệu
│   │   ├── Admin/          # Controller cho trang quản trị
│   │   └── Client/         # Controller cho khách hàng
│   │
│   ├── Models/             # Tương tác Database
│   │
│   ├── Middlewares/        # Kiểm soát quyền truy cập
│   │
│   ├── Services/           # Xử lý logic phức tạp (Tách khỏi Controller cho gọn)
│   │
│   └── Helpers/            # Các hàm nhỏ dùng chung toàn dự án
│
├── core/                   # [FRAMEWORK CORE] Các file cấu tạo nên mô hình MVC
│
├── public/                 # [DOCUMENT ROOT] Chỉ thư mục này được public ra web
│   ├── index.php           # CỔNG VÀO DUY NHẤT (Entry Point)
│   ├── .htaccess           # Cấu hình Rewrite URL (để đường dẫn đẹp)
│   ├── assets/
│   │   ├── css/            # style.css, admin.css
│   │   ├── js/             # main.js, ajax-cart.js
│   │   ├── fonts/
│   │   └── images/         # Logo, banner tĩnh (Ảnh sp nên dùng Cloud hoặc upload riêng)
│   └── uploads/            # (Nếu lưu ảnh trên server)
│
├── resources/              # [VIEWS] Chứa giao diện HTML/PHP
│   ├── views/
│   │   ├── layouts/        # Khung giao diện chung
│   │   ├── client/         # Các trang của khách
│   │   │   ├── home.php
│   │   │   ├── products/
│   │   │   └── cart/
│   │   └── admin/          # Các trang quản trị
│   │       ├── products/
│   │       └── orders/
│   └── emails/             # Mẫu email gửi khách hàng (HTML Template)
│
├── routes/                 # Định nghĩa các đường dẫn
│
└── storage/                # Nơi lưu file tạm, logs
    ├── logs/               # File ghi lỗi (error.log)
    └── cache/              # File cache giao diện (nếu có)