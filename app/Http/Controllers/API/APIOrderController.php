<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Mail\OrderConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class APIOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();

        if ($orders->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy Đơn Hàng'
            ], 404);
        }
        return response()->json([
            'results' => $orders
        ], 200);
    }

    public function show(string $id)
    {
        try {
            // Lấy đơn hàng cùng với chi tiết đơn hàng, khách hàng, phương thức thanh toán và giảm giá
            $order = Order::with([
                'orderDetails.product1', // Thêm thông tin sản phẩm của chi tiết đơn hàng
                'orderDetails.sizes',
                'orderDetails.colors',
            ])->findOrFail($id);

            // Trả về phản hồi JSON với thông tin đơn hàng
            return response()->json([
                'message' => 'Thông tin đơn hàng',
                'order' => $order
            ], 200);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ và trả về phản hồi lỗi
            return response()->json([
                'message' => 'Không tìm thấy đơn hàng',
                'error' => $e->getMessage()
            ], 404);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_customer' => 'required',
            'id_payment' => 'required|exists:payments,id_payment',
            'name_order' => 'required|string|max:255',
            'email_order' => 'required|email|max:255',
            'phone_order' => 'required|string|max:20',
            'address_order' => 'required|string|max:255',
            'total_order' => 'required|numeric',
            'note' => 'required|string',
            'id_discount' => 'nullable|exists:discounts,id_discount',
            'province_order' => 'nullable|string',
            'district_order' => 'nullable|string',
            'commune_order' => 'nullable|string',
            'shippingfee' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => ' Xác thực không thành công', 'errors' => $validator->errors()], 400);
        }
        DB::beginTransaction();
        try {
            // Tạo mã đơn hàng
            $codeOrder = 'ĐH' . str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);

            $customerId = Customer::where('id_account', $request->id_customer)->first()->id_customer;
            // Lưu thông tin đơn hàng
            $order = Order::create([
                'id_customer' => $customerId,
                'id_payment' => $request->id_payment,
                'name_order' => $request->name_order,
                'code_order' => $codeOrder,
                'date_order' => now()->timezone('Asia/Ho_Chi_Minh'),
                'email_order' => $request->email_order,
                'phone_order' => $request->phone_order,
                'address_order' => $request->address_order,
                'total_order' => $request->total_order,
                'note' => $request->note,
                'id_discount' => $request->id_discount ?? null,
                'status_order' => 1,
                'province_order' => $request->province_order ?? null,
                'district_order' => $request->district_order ?? null,
                'commune_order' => $request->commune_order ?? null,
                'weight_order' => 500,
                'shippingfee' => $request->shippingfee,
            ]);

            //// Lưu chi tiết đơn hàng
            $totalProducts = 0; // Tổng số sản phẩm
            $exceededProducts = []; // Mảng để lưu trữ id_product có số lượng vượt quá tồn kho
            foreach ($request->order_list as $detail) {
                // ///Điều kiện khi mua hàng
                // $totalProducts += $detail['quantity'];
                // if ($totalProducts > 10) {
                //     return response()->json([
                //         'message' => 'Thêm đơn hàng thất bại',
                //         'error' => 'Chỉ được phép mua tối đa 10 sản phẩm 1 lần'
                //     ], 400);
                // }

                $orderDetail = new OrderDetail([
                    'id_order' => $order->id_order,
                    'quantity' => $detail['quantity'],
                    'totalprice' => $detail['sellprice_product'],
                    'id_product' => $detail['id_product'],
                    'id_product_variants' => $detail['id_variant']['id_variant'],
                ]);
                $orderDetail->save();

                //// Cập nhật số lượng mua của sản phẩm
                $product = Product::find($detail['id_product']);
                $product->number_buy += $detail['quantity'];
                $product->save();

                ///Cập nhật số lượng tồn kho của biến thể sản phẩm
                $productVariant = ProductVariant::find($detail['id_variant']['id_variant']);
                if ($productVariant) {
                    $productVariant->quantity -= $detail['quantity'];
                    $productVariant->save();
                }

                // // Cập nhật số lượng tồn kho của biến thể sản phẩm
                $productVariant = ProductVariant::find($detail['id_variant']['id_variant']);
                if ($productVariant) {
                    if ($productVariant->quantity < $detail['quantity']) {
                        $exceededProducts[] = [
                            'id_product' => $detail['id_product'],
                            'name_product' => $product->name_product,
                            'id_variant' => $detail['id_variant']['id_variant'],
                            'quantity' => $productVariant->quantity
                        ];
                    } else {
                        $productVariant->quantity -= $detail['quantity'];
                        $productVariant->save();
                    }
                }
            }

            if (!empty($exceededProducts)) {
                // Trả về JSON chứa thông tin sản phẩm vượt quá số lượng
                return response()->json([
                    'message' => 'Thêm đơn hàng thất bại',
                    'error' => 'Sản phẩm vượt quá số lượng tồn kho',
                    'product_variant' => $exceededProducts
                ], 400);
            }

            ////Cập nhật số lượt sử dụng mã giảm giá
            if ($request->id_discount) {
                $discount = Discount::find($request->id_discount);
                if ($discount && $discount->expiration_date >= now() && $discount->number_used < $discount->limit_number) {
                    $discount->increment('number_used');
                    $discount->decrement('limit_number');
                    $discount->save();
                } else {
                    return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc
                    đã hết hạn hoặc đã sử dụng quá giới hạn'], 400);
                }
            }

            Mail::to($order->email_order)->send(new OrderConfirmation($order));
            DB::commit();
            return response()->json(['message' => 'Đơn hàng đã được thêm thành công', 'order' => $order], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Thêm đơn hàng thất bại', 'error' => $e->getMessage()], 500);
        }
    }

    ////Thanh toán API MOMO
    public function storeMomo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_customer' => 'required',
            'id_payment' => 'required|exists:payments,id_payment',
            'name_order' => 'required|string|max:255',
            'email_order' => 'required|email|max:255',
            'phone_order' => 'required|string|max:20',
            'address_order' => 'required|string|max:255',
            'total_order' => 'required|numeric',
            'note' => 'required|string',
            'id_discount' => 'nullable|exists:discounts,id_discount',
            //'id_discount' => 'exists:discounts,id_discount',
            'province_order' => 'nullable|string',
            'district_order' => 'nullable|string',
            'commune_order' => 'nullable|string',
            'shippingfee' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => ' Xác thực không thành công', 'errors' => $validator->errors()], 400);
        }

        DB::beginTransaction();
        try {
            // Tạo mã đơn hàng
            $codeOrder = 'ĐH' . str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);
            $customerId = Customer::where('id_account', $request->id_customer)->first()->id_customer;

            // Tạo yêu cầu thanh toán qua Momo
            $momoResponse = $this->createPayment($request);
            //dd($momoResponse);
            if (!isset($momoResponse['payUrl'])) {
                // Nếu không lấy được payUrl từ Momo, rollback và trả về lỗi
                DB::rollBack();
                return response()->json(['message' => 'Tạo thanh toán Momo thất bại'], 500);
            }

            // Lưu thông tin đơn hàng
            $order = Order::create([
                'id_customer' => $customerId,
                'id_payment' => $request->id_payment,
                'name_order' => $request->name_order,
                'code_order' => $codeOrder,
                'date_order' => now()->timezone('Asia/Ho_Chi_Minh'),
                'email_order' => $request->email_order,
                'phone_order' => $request->phone_order,
                'address_order' => $request->address_order,
                'total_order' => $request->total_order,
                'note' => $request->note,
                'id_discount' => $request->id_discount ?? null,
                //'id_discount' => 4,
                'status_order' => 1,
                'province_order' => $request->province_order ?? null,
                'district_order' => $request->district_order ?? null,
                'commune_order' => $request->commune_order ?? null,
                'weight_order' => 500,
                'shippingfee' => $request->shippingfee,
            ]);
            // $exceededProducts = []; // Mảng để lưu trữ id_product có số lượng vượt quá tồn kho
            foreach ($request->order_list as $detail) {
                // ///Điều kiện khi mua hàng
                // $totalProducts += $detail['quantity'];
                // if ($totalProducts > 10) {
                //     return response()->json([
                //         'message' => 'Thêm đơn hàng thất bại',
                //         'error' => 'Chỉ được phép mua tối đa 10 sản phẩm 1 lần'
                //     ], 400);
                // }

                $orderDetail = new OrderDetail([
                    'id_order' => $order->id_order,
                    'quantity' => $detail['quantity'],
                    'totalprice' => $detail['sellprice_product'],
                    'id_product' => $detail['id_product'],
                    'id_product_variants' => $detail['id_variant']['id_variant'],
                ]);
                $orderDetail->save();

                //// Cập nhật số lượng mua của sản phẩm
                $product = Product::find($detail['id_product']);
                $product->number_buy += $detail['quantity'];
                $product->save();

                ///Cập nhật số lượng tồn kho của biến thể sản phẩm
                $productVariant = ProductVariant::find($detail['id_variant']['id_variant']);
                if ($productVariant) {
                    $productVariant->quantity -= $detail['quantity'];
                    $productVariant->save();
                }

                // // Cập nhật số lượng tồn kho của biến thể sản phẩm
                $productVariant = ProductVariant::find($detail['id_variant']['id_variant']);
                if ($productVariant) {
                    if ($productVariant->quantity < $detail['quantity']) {
                        $exceededProducts[] = [
                            'id_product' => $detail['id_product'],
                            'name_product' => $product->name_product,
                            'id_variant' => $detail['id_variant']['id_variant'],
                            'quantity' => $productVariant->quantity
                        ];
                    } else {
                        $productVariant->quantity -= $detail['quantity'];
                        $productVariant->save();
                    }
                }
            }
            // Lưu chi tiết đơn hàng và các xử lý khác như trong đoạn mã của bạn

            ////Cập nhật số lượt sử dụng mã giảm giá
            if ($request->id_discount) {
                $discount = Discount::find($request->id_discount);
                if ($discount && $discount->expiration_date >= now() && $discount->number_used < $discount->limit_number) {
                    $discount->increment('number_used');
                    $discount->decrement('limit_number');
                    $discount->save();
                } else {
                    return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc
                    đã hết hạn hoặc đã sử dụng quá giới hạn'], 400);
                }
            }

            // Gửi email xác nhận đơn hàng
            Mail::to($order->email_order)->send(new OrderConfirmation($order));
            DB::commit();
            // Trả về URL thanh toán Momo
            return response()->json(['message' => 'Đơn hàng đã được thêm thành công', 'order' => $order, 'payUrl' => $momoResponse['payUrl']], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Thêm đơn hàng thất bại', 'error' => $e->getMessage()], 500);
        }
    }


    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);

        // Check for cURL errors
        if ($result === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['message' => 'cURL error', 'error' => $error], 500);
        }
        //close connection
        curl_close($ch);
        return $result;
    }

    public function createPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua ATM MoMo";
        $amount = $request->total_order;
        $orderId = time() . "";
        //$redirectUrl = "https://frontend.matkinhcaolo.io.vn/paymentgateway";
        //$ipnUrl = "https://frontend.matkinhcaolo.io.vn/paymentgateway";
        $redirectUrl = "http://localhost:3000/cart";
        $ipnUrl = "http://localhost:3000/cart";
        $extraData = "";


        $requestId = time() . "";
        $requestType = "payWithATM";
        // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        //dd($signature);
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));

        /// mới thay đổi chỗ này
        if (is_array($result)) {
            // Nếu $result là mảng, thì có thể đã được json_decode trước đó
            $jsonResult = $result;
        } else {
            $jsonResult = json_decode($result, true);
        }
        // $jsonResult = json_decode($result, true);  // decode json
        // Kiểm tra nếu $jsonResult là null do lỗi json_decode
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from Momo');
        }

        //return response()->json(['payUrl' => $jsonResult['payUrl']]);
        return $jsonResult;
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    // Hàm tạo mã ngẫu nhiên với tiền tố "DH"
    private function generateRandomOrderCode($length = 9)
    {
        $characters = '123456789'; // Chỉ bao gồm các số từ 1 đến 9
        $charactersLength = strlen($characters);
        $randomString = 'DH'; // Bắt đầu với tiền tố "DH"
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orders = Order::find($id);

        if (!$orders) {
            return response()->json([
                'message' => 'Đơn Hàng không tìm thấy'
            ], 404);
        }

        try {
            $orders->update($request->all());
            return response()->json([
                'message' => 'Đơn Hàng đã được cập nhật thành công',
                'data' => $orders
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Lỗi khi cập nhật Đơn hàng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
