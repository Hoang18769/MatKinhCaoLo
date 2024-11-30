<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class APICustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();

        if ($customers->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy Thông tin Khách Hàng '
            ], 404);
        }
        return response()->json([
            'results' => $customers
        ], 200);
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
        $customers = Customer::create($request->all());
        return response()->json($customers, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $customers = Customer::findOrFail($id);
            return response()->json($customers, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Thông tin Khách Hàng không tìm thấy'
            ], 404);
        }
    }

    public function getOrderHistory($id)
    {
        // Tìm customer theo ID
        $customer = Customer::with([
            'orders.orderDetails.productVariant.product',
            'orders.orderDetails.sizes',
            'orders.orderDetails.colors',
            'orders.feedbackOrder',
            'orders.discount',
        ])->find($id);

        if (!$customer) {
            return response()->json(['message' => 'Không tìm thấy khách hàng'], 404);
        }

        // Định dạng dữ liệu trả về
        $orders = $customer->orders->map(function ($order) {
            return [
                'id_order' => $order->id_order,
                'name_order' => $order->name_order,
                'code_order' => $order->code_order,
                'date_order' => $order->date_order,
                'total_order' => $order->total_order,
                'phone_order' => $order->phone_order,
                'status_order' => $order->status_order,
                'note' => $order->note,
                'address_order' => $order->address_order,
                'province_order' => $order->province_order,
                'district_order' => $order->district_order,
                'commune_order' => $order->commune_order,
                'shippingfee' => $order->shippingfee,
                'order_details' => $order->orderDetails->map(function ($detail) {
                    $product = $detail->product;
                    $productVariant = $detail->productVariant;
                    return [
                        'id_orderdetail' => $detail->id_orderdetail,
                        'quantity' => $detail->quantity,
                        'totalprice' => $detail->totalprice,
                        'id_product' => $detail->id_product,
                        //'id_product_variants' => $detail['id_variant']['id_variant'],
                        'id_product_variants' => $productVariant ? $productVariant->id_product_variants : null,
                        'product' => $product ? [
                            'id_product' => $product->id_product,
                            'name_product' => $product->name_product,
                            'sku' => $product->sku,
                            'avt_product' => $product->avt_product,
                            'image_product' => $product->image_product,
                            'sortdect_product' => $product->sortdect_product,
                            'desc_product' => $product->desc_product,
                            'price_product' => $product->price_product,
                            'sellprice_product' => $product->sellprice_product,
                        ] : null,
                        'sizes' => $detail->sizes->map(function ($size) {
                            return [
                                'id_size' => $size->id_size,
                                'desc_size' => $size->desc_size,
                            ];
                        }),
                        'colors' => $detail->colors->map(function ($color) {
                            return [
                                'id_color' => $color->id_color,
                                'desc_color' => $color->desc_color,
                            ];
                        }),
                    ];
                }),
                'feedback' => $order->feedbackOrder,
                'discount' => $order->discount,
            ];
        });

        // Trả về dữ liệu order history của customer
        return response()->json([
            'customer' => $customer->name_customer,
            'orders' => $orders,
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator =  Validator::make($request->all(), [
            'name_customer' => 'required|string|max:255',
            'phone_customer' => 'required|digits:10|unique:customers,phone_customer,' . $id . ',id_customer',
            'address_customer' => 'nullable|string|max:255',
            'id_account' => 'nullable|exists:accounts,id_account',
            'id_google' => 'nullable|string|max:255',
            'id_facebook' => 'nullable|string|max:255',
        ], [
            'name_customer.required' => 'Vui lòng nhập tên khách hàng.',
            'phone_customer.required' => 'Vui lòng nhập số điện thoại khách hàng.',
            'phone_customer.digits' => 'Số điện thoại phải có 10 chữ số.',
            'phone_customer.unique' => 'Số điện thoại đã tồn tại, vui lòng nhập số điện thoại khác.',
        ]);
        try {
            // Tìm kiếm khách hàng
            $customer = Customer::find($id);

            // Nếu không tìm thấy khách hàng, trả về lỗi 404
            if (!$customer) {
                return response()->json([
                    'message' => 'Không tìm thấy khách hàng với ID này.',
                ], 404);
            }
            // Lấy dữ liệu đã được xác thực từ Validator
            $validatedData = $validator->validated();

            // Cập nhật thông tin khách hàng
            $customer->update($validatedData);

            // Trả về phản hồi JSON
            return response()->json([
                'message' => 'Cập nhật thông tin khách hàng thành công.',
                'customer' => $customer,
            ], 200);
        } catch (\Exception $e) {
            // Xử lý trường hợp cập nhật thất bại
            return response()->json([
                'message' => 'Cập nhật thông tin khách hàng thất bại.',
                'error' => $e->getMessage(),
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
