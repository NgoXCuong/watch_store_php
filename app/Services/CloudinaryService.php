<?php
namespace App\Services;

// Tắt cảnh báo PHP cũ
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryService {
    private $cloudinary;
    private $config;

    public function __construct() {
        $this->config = \App\Config\CloudinaryConfig::getConfig();

        // CẤU HÌNH QUAN TRỌNG: TẮT SSL VERIFICATION
        // Code này ép buộc cURL không kiểm tra chứng chỉ nữa.
        $configParams = [
            'cloud' => [
                'cloud_name' => $this->config['cloud_name'],
                'api_key'    => $this->config['api_key'],
                'api_secret' => $this->config['api_secret']
            ],
            'url' => [
                'secure' => false // Dùng HTTP cho URL hiển thị
            ],
            'api' => [
                 'upload_prefix' => 'http://api.cloudinary.com' // Dùng HTTP cho Upload API để tránh lỗi SSL
            ],
            'http' => [
                'verify' => false,
                'timeout' => 60
            ]
        ];

        // Áp dụng cấu hình Global
        Configuration::instance($configParams);

        // Khởi tạo đối tượng Cloudinary với cấu hình đã tắt SSL
        $this->cloudinary = new Cloudinary($configParams);
    }

    public function uploadImage($filePath, $options = []) {
        try {
            $uploadOptions = [
                'folder' => $options['folder'] ?? 'watch_store/products',
                'resource_type' => 'auto'
            ];

            // Thực hiện Upload
            $result = $this->cloudinary->uploadApi()->upload($filePath, $uploadOptions);
            $data = (array)$result;

            return [
                'public_id' => $data['public_id'],
                'url'       => $data['url'],
                'width'     => $data['width'] ?? 0,
                'height'    => $data['height'] ?? 0,
                'format'    => $data['format'] ?? 'jpg',
                'bytes'     => $data['bytes'] ?? 0
            ];

        } catch (\Exception $e) {
            echo "\n[ERROR] Upload Failed: " . $e->getMessage() . "\n";
            return null;
        }
    }

    // Các hàm phụ trợ giữ nguyên
    public function uploadImages($filePaths, $options = []) {
        $results = [];
        foreach ($filePaths as $filePath) {
            $result = $this->uploadImage($filePath, $options);
            if ($result) {
                $results[] = $result;
            }
        }
        return $results;
    }

    public function deleteImage($publicId) {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId);
            $resultArray = (array)$result;
            return isset($resultArray['result']) && $resultArray['result'] === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getOptimizedUrl($publicId, $transformations = []) {
        $defaultTransformations = [
            'width' => 400, 'height' => 400, 'crop' => 'fill', 'quality' => 'auto'
        ];
        $transformations = array_merge($defaultTransformations, $transformations);
        return "https://res.cloudinary.com/" . $this->config['cloud_name'] . "/image/upload/" .
               http_build_query($transformations) . "/" . $publicId;
    }
}