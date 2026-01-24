<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-info-circle me-2"></i>Chi tiết sản phẩm</h1>
            <div>
                <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo $product['id']; ?>" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/products" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Hình ảnh</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid rounded mb-3" style="max-height: 300px;">
                        
                        <?php 
                        if (!empty($product['gallery_urls'])):
                            $gallery = json_decode($product['gallery_urls'], true);
                            if (is_array($gallery) && !empty($gallery)):
                        ?>
                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <?php foreach($gallery as $img): ?>
                                    <img src="<?php echo htmlspecialchars($img); ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                <?php endforeach; ?>
                            </div>
                        <?php 
                            endif;
                        endif; 
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin chi tiết</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td><?php echo $product['id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td><?php echo htmlspecialchars($product['slug']); ?></td>
                                </tr>
                                <tr>
                                    <th>Thương hiệu</th>
                                    <td><?php echo htmlspecialchars($product['brand_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Danh mục</th>
                                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                </tr>
                                <tr>
                                    <th>Giá bán</th>
                                    <td class="text-primary fw-bold"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</td>
                                </tr>
                                <tr>
                                    <th>Giá cũ</th>
                                    <td><?php echo $product['old_price'] ? number_format($product['old_price'], 0, ',', '.') . 'đ' : '-'; ?></td>
                                </tr>
                                <tr>
                                    <th>Tồn kho</th>
                                    <td><?php echo $product['stock']; ?></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        <span class="badge <?php echo $product['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo $product['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nổi bật</th>
                                    <td>
                                        <?php if ($product['is_featured']): ?>
                                            <span class="badge bg-warning text-dark">Có</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Không</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thông số kỹ thuật</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        if (!empty($product['specifications'])):
                            $specs = json_decode($product['specifications'], true);
                            
                            // Translation Map
                            $specMap = [
                                'brand' => 'Thương hiệu',
                                'origin' => 'Xuất xứ',
                                'target' => 'Đối tượng',
                                'gender' => 'Giới tính',
                                'line' => 'Dòng sản phẩm',
                                'water_resistance' => 'Chống nước',
                                'water_resist' => 'Chống nước',
                                'dial_type' => 'Loại mặt số',
                                    'movement' => 'Loại máy',
                                    'engine' => 'Động cơ',
                                    'crystal_material' => 'Chất liệu kính',
                                    'glass_material' => 'Chất liệu kính',
                                    'glass' => 'Kính',
                                    'case_material' => 'Chất liệu vỏ',
                                    'band_material' => 'Chất liệu dây',
                                    'strap_material' => 'Chất liệu dây',
                                    'band_width' => 'Độ rộng dây',
                                    'clasp' => 'Kiểu khóa',
                                    'case_size' => 'Size mặt',
                                    'case_thickness' => 'Độ dày',
                                    'thickness' => 'Độ dày',
                                    'face_color' => 'Màu mặt',
                                    'dial_color' => 'Màu mặt',
                                    'case_diameter' => 'Đường kính mặt',
                                    'diameter' => 'Đường kính',
                                    'case_color' => 'Màu vỏ',
                                    'face_shape' => 'Hình dáng mặt',
                                    'shape' => 'Hình dáng',
                                    'collection' => 'Bộ sưu tập',
                                    'features' => 'Tính năng',
                                    'function' => 'Chức năng',
                                    'warranty' => 'Bảo hành'
                            ];

                            if (is_array($specs) && !empty($specs)):
                        ?>
                            <table class="table table-striped">
                                <tbody>
                                    <?php foreach($specs as $key => $value): 
                                        $displayKey = $specMap[$key] ?? $key;
                                        if (!isset($specMap[$key])) {
                                            $displayKey = ucfirst(str_replace('_', ' ', $key));
                                        }
                                    ?>
                                        <tr>
                                            <th style="width: 200px;"><?php echo htmlspecialchars($displayKey); ?></th>
                                            <td><?php echo htmlspecialchars($value); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Không có thông số kỹ thuật.</p>
                        <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted fst-italic">Chưa cập nhật thông số.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
