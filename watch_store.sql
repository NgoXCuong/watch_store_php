-- 1. KHỞI TẠO DATABASE
CREATE DATABASE IF NOT EXISTS `watch_store_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `watch_store_db`;

-- ========================================================
-- PHẦN 1: CÁC BẢNG ĐỘC LẬP (MASTER DATA)
-- ========================================================

-- 2. Bảng Brands (Thương hiệu)
CREATE TABLE `brands` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `logo_url` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_brand_name` (`name`),
    UNIQUE KEY `unique_brand_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng Categories (Danh mục)
CREATE TABLE `categories` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `parent_id` INT(11) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_cat_name` (`name`),
    UNIQUE KEY `unique_cat_slug` (`slug`),
    KEY `idx_parent_id` (`parent_id`),
    CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bảng Users (Người dùng)
CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `avatar_url` VARCHAR(255) DEFAULT NULL,
    `role` ENUM('admin', 'staff', 'customer') DEFAULT 'customer',
    `status` ENUM('active', 'banned') DEFAULT 'active',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_username` (`username`),
    UNIQUE KEY `unique_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Bảng Vouchers (Mã giảm giá)
CREATE TABLE `vouchers` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `code` VARCHAR(50) NOT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `discount_type` ENUM('fixed', 'percent') DEFAULT 'fixed',
    `discount_value` DECIMAL(10,2) NOT NULL,
    `max_discount_amount` DECIMAL(10,2) DEFAULT NULL,
    `min_order_value` DECIMAL(10,2) DEFAULT 0.00,
    `usage_limit` INT(11) DEFAULT 100,
    `usage_count` INT(11) DEFAULT 0,
    `start_date` DATETIME DEFAULT NULL,
    `end_date` DATETIME DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_voucher_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PHẦN 2: CÁC BẢNG SẢN PHẨM & LIÊN KẾT (CẤU TRÚC MỚI)
-- ========================================================

-- 6. Bảng Products (Sản phẩm) - Đã bỏ category_id
CREATE TABLE `products` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `old_price` DECIMAL(10,2) DEFAULT NULL,
    `stock` INT(11) DEFAULT 0,
    `brand_id` INT(11) NOT NULL,
    -- Đã xóa category_id ở đây để dùng bảng trung gian
    `image_url` VARCHAR(255) DEFAULT NULL,
    `gallery_urls` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_urls`)),
    `specifications` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`specifications`)),
    `views` INT(11) DEFAULT 0,
    `is_featured` TINYINT(1) DEFAULT 0,
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_product_slug` (`slug`),
    KEY `idx_product_brand` (`brand_id`),
    KEY `idx_product_price` (`price`),
    FULLTEXT KEY `idx_product_search` (`name`, `description`), -- Thêm index tìm kiếm
    CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. [MỚI] Bảng Trung Gian: Product_Categories (Nhiều-Nhiều)
CREATE TABLE `product_categories` (
    `product_id` INT(11) NOT NULL,
    `category_id` INT(11) NOT NULL,
    PRIMARY KEY (`product_id`, `category_id`),
    CONSTRAINT `fk_pc_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pc_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PHẦN 3: CÁC BẢNG GIAO DỊCH & NGƯỜI DÙNG
-- ========================================================

-- 8. Bảng User Addresses
CREATE TABLE `user_addresses` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `recipient_name` VARCHAR(100) NOT NULL,
    `recipient_phone` VARCHAR(20) NOT NULL,
    `address_line` TEXT NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `district` VARCHAR(100) DEFAULT NULL,
    `is_default` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    KEY `idx_user_address` (`user_id`),
    CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Bảng Carts (Giỏ hàng)
CREATE TABLE `carts` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `quantity` INT(11) DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_cart_item` (`user_id`, `product_id`),
    CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Bảng Reviews (Đánh giá)
CREATE TABLE `reviews` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `rating` TINYINT(4) NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment` TEXT DEFAULT NULL,
    `is_approved` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_review` (`user_id`, `product_id`),
    CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Bảng Orders (Đơn hàng)
CREATE TABLE `orders` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) DEFAULT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `phone_number` VARCHAR(20) NOT NULL,
    `shipping_address` TEXT NOT NULL,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `shipping_fee` DECIMAL(10,2) DEFAULT 0.00,
    `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
    `voucher_id` INT(11) DEFAULT NULL,
    `note` TEXT DEFAULT NULL,
    `status` ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled', 'returned') DEFAULT 'pending',
    `payment_method` ENUM('cod', 'vnpay', 'momo', 'banking') DEFAULT 'cod',
    `payment_status` ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    KEY `idx_order_user` (`user_id`),
    KEY `idx_order_status` (`status`),
    CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_order_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Bảng Order Details (Chi tiết đơn hàng)
CREATE TABLE `order_details` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `order_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `quantity` INT(11) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) GENERATED ALWAYS AS (`quantity` * `price`) STORED,
    PRIMARY KEY (`id`),
    KEY `idx_od_order` (`order_id`),
    CONSTRAINT `fk_od_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_od_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 13. [BỔ SUNG] Bảng Wishlists (Sản phẩm yêu thích)
CREATE TABLE IF NOT EXISTS `wishlists` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `product_id` INT(11) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`),
    -- Chặn việc một User thích một sản phẩm 2 lần (Tránh dữ liệu rác)
    UNIQUE KEY `unique_wishlist` (`user_id`, `product_id`),
    -- Ràng buộc khóa ngoại
    CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- PHẦN 4: TRIGGERS (TỰ ĐỘNG HÓA & LOGIC AN TOÀN)
-- ========================================================

DELIMITER $$

-- Trigger 1: Kiểm tra và Trừ tồn kho an toàn (Chặn nếu hết hàng)
CREATE TRIGGER `after_order_details_insert` AFTER INSERT ON `order_details`
FOR EACH ROW
BEGIN
    DECLARE current_stock INT;
    
    -- Lấy tồn kho hiện tại
    SELECT stock INTO current_stock FROM products WHERE id = NEW.product_id;
    
    -- Nếu kho không đủ -> Báo lỗi
    IF current_stock < NEW.quantity THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Lỗi: Sản phẩm không đủ số lượng tồn kho để thực hiện giao dịch này!';
    ELSE
        -- Nếu đủ -> Trừ kho
        UPDATE `products`
        SET `stock` = `stock` - NEW.quantity
        WHERE `id` = NEW.product_id;
    END IF;
END$$

-- Trigger 2: Tăng lượt dùng Voucher
CREATE TRIGGER `update_voucher_usage` AFTER INSERT ON `orders`
FOR EACH ROW
BEGIN
    IF NEW.voucher_id IS NOT NULL THEN
        UPDATE `vouchers`
        SET `usage_count` = `usage_count` + 1
        WHERE `id` = NEW.voucher_id;
    END IF;
END$$

-- Trigger 3: Hoàn tồn kho & lượt dùng voucher khi HỦY đơn
CREATE TRIGGER `restore_stock_vouchers_on_cancel` AFTER UPDATE ON `orders`
FOR EACH ROW
BEGIN
    -- Chỉ chạy khi đơn hàng chuyển sang 'cancelled'
    IF NEW.status = 'cancelled' AND OLD.status != 'cancelled' THEN
        
        -- 1. Hoàn tồn kho
        UPDATE `products` p
        JOIN `order_details` od ON p.id = od.product_id
        SET p.stock = p.stock + od.quantity
        WHERE od.order_id = NEW.id;
        
        -- 2. Hoàn lượt dùng Voucher (nếu có)
        IF NEW.voucher_id IS NOT NULL THEN
            UPDATE `vouchers`
            SET `usage_count` = `usage_count` - 1
            WHERE `id` = NEW.voucher_id;
        END IF;

    END IF;
END$$

DELIMITER ;