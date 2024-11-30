<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class GHTKService
{
    protected $client;
    protected $apiKey;
    protected $baseUri;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = "7dbe199f498422f574fc035bb24b5162352b341b";
        $this->baseUri = 'https://services.giaohangtietkiem.vn';
    }

    public function checkAPI()
    {
        try {
            $response = $this->client->request('GET', 'https://services.giaohangtietkiem.vn/services/info', [
                'headers' => [
                    'Token' => $this->apiKey,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('GHTK API error: ' . $e->getMessage());
            return false;
        }
    }

    public function getAddressLevel4()
    {
        try {
            $response = $this->client->post($this->baseUri . '/services/address/getAddressLevel4', [
                'headers' => [
                    'Token' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                // Add any JSON payload if required
                'json' => [
                    // JSON data if required by the endpoint
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('GHTK API error: ' . $e->getMessage());
            return ['error' => 'Không thể lấy địa chỉ cấp 4', 'message' => $e->getMessage()];
        }
    }
    public function calculateShippingFee($shippingData)
    {
        // Các thông tin cần thiết để tính phí vận chuyển
        // Thông tin địa chỉ gửi cố định
        $pickAddress = '180 Cao Lỗ';
        $pickProvince = 'Hồ Chí Minh';
        $pickDistrict = 'Quận 8';
        $deliverAddress = $shippingData['deliver_address'];
        $deliverProvince = $shippingData['deliver_province'];
        $deliverDistrict = $shippingData['deliver_district'];
        // Trọng lượng đơn hàng - cố định 1000 gram (1 kg)
        $weight = 500; // Đơn vị gram
        $fee = 0;

        // Nếu đơn hàng được gửi trong cùng tỉnh/thành phố
        if ($pickProvince === $deliverProvince) {
            $fee = 0; // Phí vận chuyển là 0 VNĐ cho cùng tỉnh/thành phố
        } else {
            // Tính phí dựa trên tỉnh/thành phố nhận
            if ($deliverProvince === 'Hà Nội' || $deliverProvince === 'Hồ Chí Minh') {
                $fee += 20000; // Phí cơ bản cho Hà Nội và TP.HCM
            } else {
                $fee += 30000; // Phí cơ bản cho các tỉnh khác
            }

            // Tính phí dựa trên quận/huyện giao hàng
            if ($deliverDistrict === 'Quận 1' || $deliverDistrict === 'Quận Gò Vấp') {
                $fee += 5000; // Phí cho các quận trung tâm trong Hà Nội hoặc TP.HCM
            } else {
                $fee += 10000; // Phí cho các quận/huyện khác
            }
        }

        // Tính phí dựa trên trọng lượng
        $fee += $weight * 10000; // Giả sử phí vận chuyển là 10.000 VNĐ/kg

        return [
            'success' => true,
            'fee' => $fee,
        ];


        // $response = $this->client->post($this->baseUri . '/services/shipment/fee', [
        //     'headers' => [
        //         'Token' => $this->apiKey,
        //     ],
        //     'json' => $shippingData,
        // ]);

        // return json_decode($response->getBody()->getContents(), true);
    }
    public function createOrder($orderData)
    {

    }

}
