<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Favorite;

class APIFavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favorites = \App\Models\Favorite::all();
        if ($favorites->isEmpty()) {
            return response()->json([
                'message' => 'Khong tim thay san pham Yeu Thich'
            ], 404);
        }
        return response()->json([
            'results' => $favorites
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'id_customer' => 'required|exists:customers,id_customer',
            'id_product' => 'required|exists:products,id_product',
        ], [
            'id_customer.required' => 'Vui lòng nhập ID khách hàng.',
            'id_customer.exists' => 'Khách hàng không tồn tại.',
            'id_product.required' => 'Vui lòng nhập ID sản phẩm.',
            'id_product.exists' => 'Sản phẩm không tồn tại.',
        ]);
        // Kiểm tra xem mục yêu thích đã tồn tại chưa
        $existingFavorite = Favorite::where('id_customer', $request->id_customer)
            ->where('id_product', $request->id_product)
            ->first();

        if ($existingFavorite) {
            return response()->json([
                'message' => 'Sản phẩm đã tồn tại trong danh sách yêu thích.'
            ], 200);
        }
        // Thêm vào mục yêu thích và xử lý mọi ngoại lệ tiềm ẩn
        try {
            // $customerId = Customer::where('id_account',$request->id_customer)->first()->id_customer;
            $favorite = Favorite::create([
                'id_customer' => $request->id_customer,
                'id_product' => $request->id_product,
            ]);
            return response()->json([
                'message' => 'Đã thêm sản phẩm vào danh sách yêu thích.',
                'favorite' => $favorite,
            ], 201);
        } catch (\Exception $e) {
            // Xử lý ngoại lệ và trả về phản hồi JSON kèm theo thông báo lỗi
            return response()->json([
                'message' => 'Thêm vào danh sách yêu thích thất bại.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::find($id);

    if (!$customer) {
        return response()->json([
            'message' => 'Khách hàng không tồn tại.',
        ], 404);
    }

    $favorites = Favorite::where('id_customer', $id)
        ->with('product') // Tải mối quan hệ sản phẩm
        ->get();

    if ($favorites->isEmpty()) {
        return response()->json([
            'message' => 'Không có sản phẩm yêu thích.',
        ], 404);
    }

    return response()->json([
        'message' => 'Danh sách sản phẩm yêu thích.',
        'favorites' => $favorites,
    ], 200);
    }

    public function countFavorites($id_customer)
    {
        $customer = Customer::find($id_customer);

        if (!$customer) {
            return response()->json([
                'message' => 'Khách hàng không tồn tại.',
            ], 404);
        }

        $favoriteCount = Favorite::where('id_customer', $id_customer)->count();

        return response()->json([
            'message' => 'Số lượng sản phẩm yêu thích.',
            'favorite_count' => $favoriteCount,
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
    public function destroy(Request $request)
    {
        $request->validate([
            'id_customer' => 'required|exists:customers,id_customer',
            'id_product' => 'required|exists:products,id_product',
        ], [
            'id_customer.required' => 'Vui lòng nhập ID khách hàng.',
            'id_customer.exists' => 'Khách hàng không tồn tại.',
            'id_product.required' => 'Vui lòng nhập ID sản phẩm.',
            'id_product.exists' => 'Sản phẩm không tồn tại.',
        ]);

        $favorite = Favorite::where('id_customer', $request->id_customer)
            ->where('id_product', $request->id_product)
            ->first();

        if (!$favorite) {
            return response()->json([
                'message' => 'Sản phẩm không có trong danh sách yêu thích.'
            ], 404);
        }

        try {
            $favorite->delete();

            return response()->json([
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Xóa sản phẩm khỏi danh sách yêu thích thất bại.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
