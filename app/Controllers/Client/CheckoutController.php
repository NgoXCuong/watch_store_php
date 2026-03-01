<?php
namespace App\Controllers\Client;

use Core\Controller;
use App\Services\VNPayService;

class CheckoutController extends Controller {
    private $cartModel;
    private $orderModel;
    private $productModel;
    private $voucherModel;

    public function __construct() {
        $this->cartModel = $this->model('CartModel');
        $this->orderModel = $this->model('OrderModel');
        $this->productModel = $this->model('ProductModel');
        $this->voucherModel = $this->model('VoucherModel');
    }

    // Hiển thị trang thanh toán
    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        // Lấy thông tin giỏ hàng
        $cartItems = $this->cartModel->getCartByUserId($userId);
        $cartTotal = $this->cartModel->getCartTotal($userId);

        // Kiểm tra giỏ hàng trống
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng của bạn đang trống';
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        // Lấy thông tin user
        $userModel = $this->model('UserModel');
        $user = $userModel->findById($userId);

        // Lấy danh sách địa chỉ của user
        $userAddressModel = $this->model('UserAddressModel');
        $addresses = $userAddressModel->getByUserId($userId);

        $this->view('client/checkout/index', [
            'title' => 'Thanh toán - Watch Store',
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'user' => $user,
            'addresses' => $addresses,
            'errors' => $_SESSION['errors'] ?? [],
            'old_input' => $_SESSION['old_input'] ?? [],
            'layout' => 'client'
        ]);

        // Xóa session sau khi hiển thị
        unset($_SESSION['errors'], $_SESSION['old_input']);
    }

    // Áp dụng voucher
    public function applyVoucher() {
        header('Content-Type: application/json');

        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid method']);
            exit;
        }

        $voucherCode = trim($_POST['voucher_code'] ?? '');
        $userId = $_SESSION['user']['id'];
        $cartTotal = $this->cartModel->getCartTotal($userId);

        if (empty($voucherCode)) {
            echo json_encode(['error' => 'Vui lòng nhập mã voucher']);
            exit;
        }

        // Kiểm tra voucher
        $voucher = $this->voucherModel->isValid($voucherCode, $cartTotal);

        if (!$voucher) {
            echo json_encode(['error' => 'Mã voucher không hợp lệ hoặc đã hết hạn']);
            exit;
        }

        // Tính giảm giá
        $discount = $this->voucherModel->calculateDiscount($voucher, $cartTotal);

        echo json_encode([
            'success' => true,
            'voucher' => [
                'code' => $voucher['code'],
                'description' => $voucher['description'],
                'discount' => $discount
            ],
            'new_total' => $cartTotal - $discount
        ]);
        exit;
    }

    // Xử lý thanh toán
    public function process() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        // Lấy thông tin giỏ hàng
        $cartItems = $this->cartModel->getCartByUserId($userId);
        $cartTotal = $this->cartModel->getCartTotal($userId);

        // Kiểm tra giỏ hàng trống
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng của bạn đang trống';
            header('Location: ' . BASE_URL . '/cart');
            exit;
        }

        // Validate dữ liệu
        $addressId = $_POST['address_id'] ?? 'new';
        $userAddressModel = $this->model('UserAddressModel');

        if ($addressId !== 'new') {
            $selectedAddress = $userAddressModel->findByIdAndUserId($addressId, $userId);
            if ($selectedAddress) {
                $data = [
                    'full_name' => $selectedAddress['recipient_name'],
                    'email' => $user['email'] ?? trim($_POST['email'] ?? ''), // Use user's email if possible
                    'phone_number' => $selectedAddress['recipient_phone'],
                    'address' => $selectedAddress['address_line'],
                    'city' => $selectedAddress['city'],
                    'district' => $selectedAddress['district'],
                    'ward' => '', // Assume ward is integrated in address_line or empty since we don't have it
                    'payment_method' => $_POST['payment_method'] ?? 'cod',
                    'notes' => trim($_POST['notes'] ?? ''),
                    'voucher_code' => trim($_POST['voucher_code'] ?? ''),
                    'address_id' => $addressId
                ];
            } else {
                $_SESSION['errors'] = ['address_id' => 'Địa chỉ không hợp lệ'];
                header('Location: ' . BASE_URL . '/checkout');
                exit;
            }
        } else {
            $data = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone_number' => trim($_POST['phone_number'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'city' => trim($_POST['city'] ?? ''),
                'district' => trim($_POST['district'] ?? ''),
                'ward' => trim($_POST['ward'] ?? ''),
                'payment_method' => $_POST['payment_method'] ?? 'cod',
                'notes' => trim($_POST['notes'] ?? ''),
                'voucher_code' => trim($_POST['voucher_code'] ?? ''),
                'address_id' => 'new'
            ];
        }

        $errors = [];

        // Validation
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Họ tên không được để trống';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }

        if (empty($data['phone_number'])) {
            $errors['phone_number'] = 'Số điện thoại không được để trống';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $data['phone_number'])) {
            $errors['phone_number'] = 'Số điện thoại không hợp lệ';
        }

        if (empty($data['address'])) {
            $errors['address'] = 'Địa chỉ không được để trống';
        }

        if (empty($data['city'])) {
            $errors['city'] = 'Tỉnh/Thành phố không được để trống';
        }

        if (empty($data['district'])) {
            $errors['district'] = 'Quận/Huyện không được để trống';
        }

        if (!in_array($data['payment_method'], ['cod', 'bank_transfer', 'momo', 'vnpay'])) {
            $errors['payment_method'] = 'Phương thức thanh toán không hợp lệ';
        }

        // Kiểm tra voucher nếu có
        $discount = 0;
        if (!empty($data['voucher_code'])) {
            $voucher = $this->voucherModel->isValid($data['voucher_code'], $cartTotal);
            if (!$voucher) {
                $errors['voucher_code'] = 'Mã voucher không hợp lệ';
            } else {
                $discount = $this->voucherModel->calculateDiscount($voucher, $cartTotal);
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }

        // Tính tổng tiền sau giảm giá
        $finalTotal = $cartTotal - $discount;

        // Tạo địa chỉ giao hàng
        $shippingAddress = $data['address'];
        if (!empty($data['ward'])) {
            $shippingAddress .= ', ' . $data['ward'];
        }
        if (!empty($data['district'])) {
            $shippingAddress .= ', ' . $data['district'];
        }
        $shippingAddress .= ', ' . $data['city'];

        // Lấy voucher_id từ voucher_code nếu có
        $voucherId = null;
        if (!empty($data['voucher_code'])) {
            $voucher = $this->voucherModel->findByCode($data['voucher_code']);
            $voucherId = $voucher ? $voucher['id'] : null;
        }

        // Tạo đơn hàng theo cấu trúc database
        $orderData = [
            'user_id' => $userId,
            'full_name' => $data['full_name'],
            'phone_number' => $data['phone_number'],
            'shipping_address' => $shippingAddress,
            'total_amount' => $finalTotal,
            'shipping_fee' => 0.00, // Miễn phí vận chuyển
            'discount_amount' => $discount,
            'voucher_id' => $voucherId,
            'note' => $data['notes'],
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'payment_status' => 'unpaid'
        ];

        // Bắt đầu transaction
        $this->orderModel->beginTransaction();

        try {
            // Tạo đơn hàng
            $orderId = $this->orderModel->createOrder($orderData);

            if (!$orderId) {
                throw new \Exception('Không thể tạo đơn hàng');
            }

            // Thêm chi tiết đơn hàng (trigger sẽ tự động cập nhật tồn kho)
            $this->orderModel->createOrderDetails($orderId, $cartItems);

            // Xóa giỏ hàng
            $this->cartModel->clearCart($userId);

            // Commit transaction
            $this->orderModel->commit();

            // Nếu thanh toán qua VNPay
            if ($data['payment_method'] === 'vnpay') {
                $vnpayService = new VNPayService();
                $paymentUrl = $vnpayService->createPaymentUrl($orderId, $finalTotal, "Thanh toan don hang #" . $orderId);
                header('Location: ' . $paymentUrl);
                exit;
            }

            // Chuyển hướng đến trang thành công
            $_SESSION['success'] = 'Đặt hàng thành công!';
            header('Location: ' . BASE_URL . '/checkout/success/' . $orderId);
            exit;

        } catch (\Exception $e) {
            // Rollback transaction
            $this->orderModel->rollback();

            $_SESSION['errors'] = ['general' => 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại.'];
            $_SESSION['old_input'] = $data;
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }
    }

    // Trang thành công
    public function success($orderId) {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = (int)$orderId;

        // Lấy thông tin đơn hàng
        $order = $this->orderModel->findById($orderId);

        // Kiểm tra quyền truy cập
        if (!$order || $order['user_id'] != $userId) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng';
            header('Location: ' . BASE_URL . '/orders');
            exit;
        }

        $this->view('client/checkout/success', [
            'title' => 'Đặt hàng thành công - Watch Store',
            'order' => $order,
            'layout' => 'client'
        ]);
    }

    // Xử lý thông tin trả về từ VNPay
    public function vnpayReturn() {
        $vnpayService = new VNPayService();
        $inputData = $_GET;

        // Kiểm tra chữ ký
        if ($vnpayService->checkSecureHash($inputData)) {
            $orderId = $inputData['vnp_TxnRef'];
            $vnp_ResponseCode = $inputData['vnp_ResponseCode'];
            
            // Lấy thông tin đơn hàng hiện tại
            $order = $this->orderModel->findById($orderId);

            if ($order) {
                if ($vnp_ResponseCode == '00') {
                    // Thanh toán thành công
                    // Cập nhật trạng thái thanh toán
                    $this->orderModel->updatePaymentStatus($orderId, 'paid');
                    
                    $_SESSION['success'] = 'Thanh toán VNPay thành công!';
                    header('Location: ' . BASE_URL . '/checkout/success/' . $orderId);
                    exit;
                } else {
                    // Thanh toán thất bại hoặc bị hủy
                    $_SESSION['errors'] = ['payment' => 'Giao dịch thanh toán thất bại hoặc bị hủy.'];
                    header('Location: ' . BASE_URL . '/checkout');
                    exit;
                }
            } else {
                $_SESSION['errors'] = ['payment' => 'Không tìm thấy đơn hàng.'];
                header('Location: ' . BASE_URL . '/checkout');
                exit;
            }
        } else {
            // Sai chữ ký
            $_SESSION['errors'] = ['payment' => 'Chữ ký không hợp lệ!'];
            header('Location: ' . BASE_URL . '/checkout');
            exit;
        }
    }


}
