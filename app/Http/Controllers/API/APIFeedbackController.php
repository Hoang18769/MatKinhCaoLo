<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use \App\Models\Feedback;
use Illuminate\Support\Facades\Validator;
use \App\Models\Order;
use \App\Models\OrderDetail;
use \App\Models\ProductVariant;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class APIFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feedbacks = Feedback::all();
        if ($feedbacks->isEmpty()) {
            return response()->json([
                'message' => 'Khong tim thay Feedback'
            ], 404);
        }
        return response()->json([
            'results' => $feedbacks
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
        // Validate dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'id_customer' => 'required',
            'id_product' => 'required|exists:products,id_product',
            'id_order' => 'required|exists:orders,id_order',
            'id_product_variants' => 'required',
            'comment' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ], [
            'id_product_variants' => 'Các biến thể sản phẩm id đã chọn không hợp lệ.',
            'comment' => 'Vui lòng nhập feedback.',
            'rating' => 'Vui lòng đánh giá sản phẩm.',
            'between' => 'Xếp hạng nằm từ 1 đến 5.',
            'integer' => 'Vui lòng nhập số.'

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Kiểm tra đơn hàng thuộc về khách hàng và chứa sản phẩm đã mua
        $order = Order::where('id_order', $request->id_order)
            ->where('id_customer', $request->id_customer)
            ->first();

        if (!$order) {
            return response()->json(['error' => 'Đơn hàng không được tìm thấy hoặc không thuộc về khách hàng.'], 404);
        }

        // Kiểm tra xem biến thể sản phẩm có tồn tại và thuộc về sản phẩm này không
        $productVariant = ProductVariant::where('id_product_variants', $request->id_product_variants)
            ->where('id_product', $request->id_product)
            ->first();

        if (!$productVariant) {
            return response()->json(['error' => 'Biến thể không thuộc về sản phẩm này.'], 400);
        }


        /// Kiểm tra xem khách hàng đã gửi feedback cho sản phẩm này chưa
        $existingFeedback = Feedback::where('id_customer', $request->id_customer)
            ->where('id_order', $request->id_order)
            ->where('id_product', $request->id_product)
            ->where('id_product_variants', $request->id_product_variants)
            ->first();

        if ($existingFeedback) {
            return response()->json(['error' => 'Bạn đã gửi phản hồi cho sản phẩm này rồi.'], 400);
        }

        try {
            $feedback = Feedback::create([
                'id_customer' => $request->id_customer,
                'id_product' => $request->id_product,
                'id_order' => $request->id_order,
                'id_product_variants' => $request->id_product_variants,
                'comment' => $request->comment,
                'rating' => $request->rating,
                'feedback_status'=> 1,
            ]);

            return response()->json(['message' => 'Feedback thêm thành công', 'feedback' => $feedback], 201);
        } catch (QueryException $e) {
            // Xử lý lỗi khi lưu dữ liệu vào cơ sở dữ liệu
            return response()->json(['error' => 'Feedback thất bại. Vui lòng thử lại sau.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Lấy danh sách các feedback theo id_product
        // $feedbacks = Feedback::where('id_product', $id)
        //     ->with(['customer', 'productVariant.size','productVariant.color'])
        //     ->get();
        $feedbacks = Feedback::where('id_product', $id)
            ->with(['productVariant.size','productVariant.color'])
            ->get();
        if ($feedbacks->isEmpty()) {
            return response()->json([
                'message' => 'Chưa có Feedback cho sản phẩm này.'
            ], 200);
        }
        $result = $feedbacks->map(function ($feedback) {
            return [
                'id_feedback'=>$feedback->id_feedback,
                'id_customet'=>$feedback->id_customer,
                'id_variant '=>$feedback->id_product_variants,
                'customer'=>$feedback->customer->name_customer ?? 'N/A',
                'comment' => $feedback->comment,
                'rating' => $feedback->rating,
                'feedback_status'=>$feedback->feedback_status,
                'desc_color' => $feedback->productVariant->color->desc_color ?? 'N/A',
                'desc_size' => $feedback->productVariant->size->desc_size ?? 'N/A',

            ];
        });
        return response()->json([
            'id_product' => $id,
            'feedbacks' => $result
        ], 200);
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
