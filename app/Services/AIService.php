<?php
namespace App\Services;

use GuzzleHttp\Client;

class AIService {
    private $client;
    private $apiKey;

    public function __construct() {
        // Đảm bảo lấy đúng Key từ .env
        $this->apiKey = $_ENV['GEMINI_API_KEY'] ?? '';
        
        $this->client = new Client([
            // Bỏ v1beta ở đây để tránh trùng lặp khi nối chuỗi
            'base_uri' => 'https://generativelanguage.googleapis.com/',
            'timeout'   => 30.0,
            'verify'    => false, 
        ]);
    }

    public function parseSearchQuery($query, $categories, $brands) {
        if (empty($this->apiKey)) return [];

        $catsStr = implode(", ", array_map(function($c) { return "ID " . $c['id'] . ": " . $c['name']; }, $categories));
        $brandsStr = implode(", ", array_map(function($b) { return "ID " . $b['id'] . ": " . $b['name']; }, $brands));

        $prompt = "Phân tích câu: '$query'. Danh mục: [$catsStr]. Thương hiệu: [$brandsStr]. " .
                  "Trả về JSON: {\"search\": string, \"category_id\": int, \"brand_id\": int, \"min_price\": int, \"max_price\": int}. Không giải thích.";

        try {
            // Sử dụng đường dẫn đầy đủ từ phiên bản v1beta
            $url = "v1beta/models/gemini-2.5-flash:generateContent?key=" . $this->apiKey;

            $response = $this->client->post($url, [
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.1,
                        'responseMimeType' => 'application/json'
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $content = $data['candidates'][0]['content']['parts'][0]['text'];
                return json_decode(trim($content), true) ?? [];
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Log chi tiết lỗi từ Google để debug
            error_log("Gemini 404/Client Error: " . $e->getResponse()->getBody()->getContents());
            return [];
        } catch (\Exception $e) {
            error_log("Gemini API General Error: " . $e->getMessage());
            return [];
        }
        return [];
    }

    public function chatWithAssistant($message, $history, $products) {
        if (empty($this->apiKey)) return "Hệ thống AI hiện đang bảo trì, vui lòng thử lại sau.";

        // Prepare product context
        $productContext = "Dưới đây là danh sách một số sản phẩm hiện có (ID, Tên, Giá, Thương hiệu, Giới tính, Tồn kho):\n";
        foreach ($products as $p) {
            $productContext .= "- ID {$p['id']}: {$p['name']} | Giá: {$p['price']} | Hãng: {$p['brand_name']} | Danh mục: {$p['category_name']} | Tồn: {$p['stock']}\n";
        }

        $systemInstruction = "Bạn là 'Chuyên gia Tư vấn Đồng hồ Luxury' của cửa hàng Watch Store. " .
                             "Mục tiêu của bạn là giúp khách hàng tìm được chiếc đồng hồ ưng ý nhất dựa trên nhu cầu của họ. " .
                             "Hãy tư vấn một cách lịch sự, chuyên nghiệp, và đầy cảm hứng. " .
                             "Bạn chỉ nên tư vấn các sản phẩm có trong danh sách sau đây. Nếu khách hỏi sản phẩm không có, hãy khéo léo giới thiệu các sản phẩm tương tự trong danh sách.\n\n" .
                             $productContext . "\n\n" .
                             "Cách trả lời:\n" .
                             "- Nếu gợi ý sản phẩm, hãy in đậm ID và Tên sản phẩm, kèm theo giá và lý do tại sao nó phù hợp.\n" .
                             "- Không cần giải thích bạn là AI. Trả lời trực tiếp như nhân viên thực thụ.\n" .
                             "- Có thể sử dụng HTML cơ bản như <strong>, <br>, <ul>, <li> nếu cần thiết để định dạng danh sách (Không dùng markdown *).";

        // Convert history for Gemini
        $contents = [];
        foreach ($history as $h) {
            $contents[] = [
                'role' => $h['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $h['content']]]
            ];
        }
        
        // Add current message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        try {
            $url = "v1beta/models/gemini-2.5-flash:generateContent?key=" . $this->apiKey;

            $response = $this->client->post($url, [
                'json' => [
                    'system_instruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.6,
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            }
        } catch (\Exception $e) {
            error_log("Gemini Chat API Error: " . $e->getMessage());
            return "Xin lỗi, tôi đang gặp chút vấn đề kết nối. Bạn vui lòng liên hệ hotline 1900.1234 để được hỗ trợ lập tức nhé!";
        }
        return "Xin lỗi, tôi không thể xử lý yêu cầu lúc này.";
    }
}