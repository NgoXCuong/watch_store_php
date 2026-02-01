<?php
namespace App\Services;

class VNPayService {
    private $tmnCode;
    private $hashSecret;
    private $url;
    private $returnUrl;

    public function __construct() {
        $this->tmnCode = $_ENV['VNPAY_TMN_CODE'];
        $this->hashSecret = $_ENV['VNPAY_HASH_SECRET'];
        $this->url = $_ENV['VNPAY_URL'];
        $this->returnUrl = $_ENV['VNPAY_RETURN_URL'];
    }

    public function createPaymentUrl($orderId, $amount, $orderInfo) {
        $vnp_Url = $this->url;
        $vnp_Returnurl = $this->returnUrl;
        $vnp_TmnCode = $this->tmnCode;
        $vnp_HashSecret = $this->hashSecret;

        $vnp_TxnRef = $orderId; // Mã đơn hàng
        $vnp_OrderInfo = $orderInfo;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100; // VNPay uses VND * 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = ''; // Để trống để người dùng chọn ngân hàng
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
        // Add Created Date
        $vnp_CreateDate = date('YmdHis');

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData["vnp_BankCode"] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    public function checkSecureHash($inputData) {
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        
        // Remove vnp_SecureHashType if present (some old versions)
        if (isset($inputData['vnp_SecureHashType'])) {
            unset($inputData['vnp_SecureHashType']);
        }

        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        
        return $secureHash === $vnp_SecureHash;
    }
}
