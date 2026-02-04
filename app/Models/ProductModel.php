<?php
namespace App\Models;

use Core\Database;
use PDO;

class ProductModel {
    protected $conn;
    private $table = 'products';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Lấy tất cả sản phẩm với phân trang
    public function getAll($page = 1, $limit = 10, $search = '', $categoryId = null, $brandId = null, $sort = 'default', $minPrice = null, $maxPrice = null) {
        $offset = ($page - 1) * $limit;
        $where = "p.status = 'active'"; // Thêm điều kiện active mặc định
        $params = [];

        if (!empty($search)) {
            $where .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        // Lọc theo danh mục
        if ($categoryId) {
            $where .= " AND EXISTS (SELECT 1 FROM product_categories pc WHERE pc.product_id = p.id AND pc.category_id = :category_id)";
            $params[':category_id'] = $categoryId;
        }

        if ($brandId) {
            $where .= " AND p.brand_id = :brand_id";
            $params[':brand_id'] = $brandId;
        }

        // Filter by Price
        if ($minPrice !== null) {
            $where .= " AND p.price >= :min_price";
            $params[':min_price'] = $minPrice;
        }
        if ($maxPrice !== null) {
            $where .= " AND p.price <= :max_price";
            $params[':max_price'] = $maxPrice;
        }

        // Xác định cách sắp xếp
        $orderBy = "p.created_at DESC"; // Mặc định
        switch ($sort) {
            case 'latest':
                $orderBy = "p.created_at DESC";
                break;
            case 'price_asc':
                $orderBy = "p.price ASC";
                break;
            case 'price_desc':
                $orderBy = "p.price DESC";
                break;
            case 'name':
                $orderBy = "p.name ASC";
                break;
            default:
                $orderBy = "p.created_at DESC";
        }

        // Query cập nhật: Group concat category names (optional)
        $query = "SELECT p.*, b.name as brand_name
                  FROM " . $this->table . " p
                  LEFT JOIN brands b ON p.brand_id = b.id
                  WHERE $where
                  ORDER BY $orderBy
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch categories for each product (để hiển thị frontend nếu cần)
        foreach ($products as &$product) {
            $product['categories'] = $this->getCategoriesByProductId($product['id']);
            // Fallback for old code using category_name
            $product['category_name'] = !empty($product['categories']) ? $product['categories'][0]['name'] : ''; 
        }

        return $products;
    }

    // Đếm tổng số sản phẩm
    public function countAll($search = '', $categoryId = null, $brandId = null, $minPrice = null, $maxPrice = null) {
        $where = "p.status = 'active'";
        $params = [];

        if (!empty($search)) {
            $where .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = '%' . $search . '%';
        }

        if ($categoryId) {
            $where .= " AND EXISTS (SELECT 1 FROM product_categories pc WHERE pc.product_id = p.id AND pc.category_id = :category_id)";
            $params[':category_id'] = $categoryId;
        }

        if ($brandId) {
            $where .= " AND p.brand_id = :brand_id";
            $params[':brand_id'] = $brandId;
        }

        // Filter by Price
        if ($minPrice !== null) {
            $where .= " AND p.price >= :min_price";
            $params[':min_price'] = $minPrice;
        }
        if ($maxPrice !== null) {
            $where .= " AND p.price <= :max_price";
            $params[':max_price'] = $maxPrice;
        }

        $query = "SELECT COUNT(*) as total FROM " . $this->table . " p WHERE $where";
        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Tìm sản phẩm theo ID
    public function findById($id) {
        $query = "SELECT p.*, b.name as brand_name
                  FROM " . $this->table . " p
                  LEFT JOIN brands b ON p.brand_id = b.id
                  WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $product['categories'] = $this->getCategoriesByProductId($id);
            $product['category_ids'] = array_column($product['categories'], 'id');
            // Compatibility
            $product['category_name'] = !empty($product['categories']) ? $product['categories'][0]['name'] : '';
        }

        return $product;
    }

    // Tạo sản phẩm mới
    public function create($data) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table . "
                      (name, slug, description, price, old_price, stock, brand_id,
                       image_url, gallery_urls, specifications, is_featured, status)
                      VALUES (:name, :slug, :description, :price, :old_price, :stock, :brand_id,
                              :image_url, :gallery_urls, :specifications, :is_featured, :status)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':slug', $data['slug']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':old_price', $data['old_price']);
            $stmt->bindParam(':stock', $data['stock']);
            $stmt->bindParam(':brand_id', $data['brand_id']);
            $stmt->bindParam(':image_url', $data['image_url']);
            $stmt->bindParam(':gallery_urls', $data['gallery_urls']);
            $stmt->bindParam(':specifications', $data['specifications']);
            $stmt->bindParam(':is_featured', $data['is_featured']);
            $stmt->bindParam(':status', $data['status']);

            if (!$stmt->execute()) {
                throw new \Exception("Error inserting product");
            }

            $productId = $this->conn->lastInsertId();

            // Insert categories
            if (!empty($data['category_ids'])) {
                $this->addCategories($productId, $data['category_ids']);
            }

            $this->conn->commit();
            return $productId;

        } catch (\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Cập nhật sản phẩm
    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table . " SET ";
            $setParts = [];
            $params = [':id' => $id];

            // Remove category_ids from main update data if present
            $categoryIds = $data['category_ids'] ?? [];
            unset($data['category_ids']);

            foreach ($data as $key => $value) {
                $setParts[] = "$key = :$key";
                $params[":$key"] = $value;
            }

            $query .= implode(', ', $setParts) . " WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            if (!$stmt->execute($params)) {
               throw new \Exception("Error updating product info");
            }

            // Update Categories
            if (isset($categoryIds)) {
                // Delete old
                $delQuery = "DELETE FROM product_categories WHERE product_id = :product_id";
                $delStmt = $this->conn->prepare($delQuery);
                $delStmt->bindParam(':product_id', $id);
                $delStmt->execute();

                // Add new
                if (!empty($categoryIds)) {
                     $this->addCategories($id, $categoryIds);
                }
            }

            $this->conn->commit();
            return true;

        } catch (\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Helper: Add categories
    private function addCategories($productId, $categoryIds) {
        $query = "INSERT INTO product_categories (product_id, category_id) VALUES (:product_id, :category_id)";
        $stmt = $this->conn->prepare($query);
        foreach ($categoryIds as $catId) {
            $stmt->bindValue(':product_id', $productId);
            $stmt->bindValue(':category_id', $catId);
            $stmt->execute();
        }
    }

    // Helper: Get categories of a product
    public function getCategoriesByProductId($productId) {
        $query = "SELECT c.* FROM categories c
                  JOIN product_categories pc ON c.id = pc.category_id
                  WHERE pc.product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xóa sản phẩm
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Lấy sản phẩm nổi bật
    public function getFeatured($limit = null) {
        $query = "SELECT p.*, b.name as brand_name
                  FROM " . $this->table . " p
                  LEFT JOIN brands b ON p.brand_id = b.id
                  WHERE p.is_featured = 1 AND p.status = 'active'
                  ORDER BY p.created_at DESC";

        if ($limit) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->conn->prepare($query);

        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo danh mục (Updated)
    public function getByCategory($categoryId, $limit = null) {
        $query = "SELECT p.* FROM " . $this->table . " p
                  JOIN product_categories pc ON p.id = pc.product_id
                  WHERE pc.category_id = :category_id AND p.status = 'active'
                  ORDER BY p.created_at DESC";
        if ($limit) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);

        if ($limit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy số lượng sản phẩm tồn kho thấp
    public function getLowStockCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE stock <= 10 AND status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    // Lấy sản phẩm bán chạy nhất
    public function getTopSellingProducts($limit = 5) {
        $query = "SELECT
                    p.id, p.name, p.image_url,
                    SUM(od.quantity) as total_sold,
                    SUM(od.quantity * od.price) as total_revenue
                  FROM " . $this->table . " p
                  JOIN order_details od ON p.id = od.product_id
                  JOIN orders o ON od.order_id = o.id
                  WHERE o.status != 'cancelled' AND p.status = 'active'
                  GROUP BY p.id, p.name, p.image_url
                  ORDER BY total_sold DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update product stock
    public function updateStock($productId, $quantity) {
        $query = "UPDATE " . $this->table . " SET stock = stock - :quantity WHERE id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':quantity', $quantity);
        $stmt->bindValue(':product_id', $productId);

        if (!$stmt->execute()) {
            throw new \Exception('Không thể cập nhật tồn kho');
        }
    }

    // Analytics methods
    public function countByStatus($status) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function countFeatured() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE is_featured = 1 AND status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    public function getTopSellingProductsByDateRange($startDate, $endDate, $limit = 10) {
        $query = "SELECT
                    p.id, p.name, p.image_url,
                    SUM(od.quantity) as total_quantity,
                    SUM(od.quantity * od.price) as total_revenue
                  FROM " . $this->table . " p
                  JOIN order_details od ON p.id = od.product_id
                  JOIN orders o ON od.order_id = o.id
                  WHERE o.created_at BETWEEN :start_date AND :end_date
                  AND o.status != 'cancelled' AND p.status = 'active'
                  GROUP BY p.id, p.name, p.image_url
                  ORDER BY total_quantity DESC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLowStockProducts($limit = 10) {
        $query = "SELECT id, name, stock FROM " . $this->table . "
                  WHERE stock <= 10 AND status = 'active'
                  ORDER BY stock ASC
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductsByCategories() {
        // Updated to use product_categories table
        $query = "SELECT
                    c.name,
                    COUNT(pc.product_id) as product_count
                  FROM categories c
                  LEFT JOIN product_categories pc ON c.id = pc.category_id
                  LEFT JOIN products p ON pc.product_id = p.id AND p.status = 'active'
                  GROUP BY c.id, c.name
                  ORDER BY product_count DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
