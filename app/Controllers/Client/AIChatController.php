<?php
namespace App\Controllers\Client;

use Core\Controller;
use App\Services\AIService;
use App\Models\ProductModel;

class AIChatController extends Controller {
    private $aiService;
    private $productModel;

    public function __construct() {
        $this->aiService = new AIService();
        $this->productModel = new ProductModel();
    }

    public function chat() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $message = $input['message'] ?? '';
        $history = $input['history'] ?? [];

        if (empty($message)) {
            echo json_encode(['error' => 'Message is empty']);
            return;
        }

        // Fetch concise list of products to provide context to the AI
        $products = $this->productModel->getAll(1, 50, '', null, null, 'latest');

        $reply = $this->aiService->chatWithAssistant($message, $history, $products);

        echo json_encode(['reply' => $reply]);
    }
}
