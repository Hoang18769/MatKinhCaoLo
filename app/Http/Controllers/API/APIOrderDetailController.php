<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class APIOrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($OrderId)
    {
        // $orderDeatils = OrderDetail::all();

        // if ($orderDeatils->isEmpty()) {
        //     return response()->json([
        //         'message' => 'Không tìm thấy Chi tiết Đơn Hàng'
        //     ], 404);
        // }
        // return response()->json([
        //     'results' => $orderDeatils
        // ], 200);

        $orderDeatils = OrderDetail::where('id_order', $OrderId)->get();
        // isEmpty kt có rỗng hay không. nếu có báo lỗi
        if($orderDeatils->isEmpty()){
            return response()->json([
                'message'=>'Chi tiết đơn hàng không tìm thấy'
            ],404);
        }
        return response()->json($orderDeatils, 200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Lấy chi tiết đơn hàng cùng với sản phẩm, kích thước và màu sắc
            $orderDetail = OrderDetail::with([
                'productVariant.product',
                'sizes',
                'colors'
            ])->findOrFail($id);

            // Trả về phản hồi JSON với thông tin chi tiết đơn hàng
            return response()->json([
                'message' => 'Chi tiết đơn hàng',
                'order_detail' => $orderDetail
            ], 200);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ và trả về phản hồi lỗi
            return response()->json([
                'message' => 'Không tìm thấy chi tiết đơn hàng',
                'error' => $e->getMessage()
            ], 404);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
