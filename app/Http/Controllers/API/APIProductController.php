<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class APIProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category', 'variants.size', 'variants.color'])->get();

        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy danh sách sản phẩm'
            ], 404);
        }
        return response()->json([
            'results' => $products
        ], 200);
    }
    // public function index()
    // {
    //     $products = Product::with(['category', 'variants.size', 'variants.color'])->get();

    //     if ($products->isEmpty()) {
    //         return response()->json([
    //             'message' => 'KhГґng tГ¬m thбєҐy danh sГЎch sбєЈn phбє©m'
    //         ], 404);
    //     }
    //     return response()->json([
    //         'results' => $products
    //     ], 200);
    // }

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

    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        // Tìm sản phẩm cùng với danh mục và các biến thể
        $product = Product::with(['category','variants.size', 'variants.color'])->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy Chi tiết sản phẩm'
            ], 404);
        }

        return response()->json([
            'results' => $product
        ], 200);
    }
    public function getProducts(Request $request)
    {
        // Lấy giá trị 'limit' từ query parameters, mặc định là 5 nếu không có giá trị nào được cung cấp
        $limit = $request->query('limit', 5);

         // Lấy sản phẩm cùng với danh mục của chúng
        $products = Product::with('category')->limit($limit)->get();
        if (!$products) {
            return response()->json([
                'message' => 'Không tìm thấy 5 sản phẩm'
            ], 404);
        }
        return response()->json([
            'results' => $products
        ], 200);
    }
    public function search(Request $request)
    {
        // Nhận từ khóa tìm kiếm từ query parameters
        $search = $request->query('search');

        // Tạo query để tìm kiếm sản phẩm
        $query = Product::query();

        if ($search) {
            // Tìm kiếm theo tên sản phẩm hoặc danh mục
            $query->where('name_product', 'LIKE', "%{$search}%")
                ->orWhereHas('category', function($q) use ($search) {
                    $q->where('name_category', 'LIKE', "%{$search}%");
                });

            // Tìm kiếm theo giá sản phẩm nếu từ khóa là số
            if (is_numeric($search)) {
                $query->orWhere('price_product', '<=', $search);
            }
        }
        // Lấy kết quả truy vấn
        $products = $query->get();
        if ($products->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm kiếm thấy sản phẩm'
            ], 404);
        }
        return response()->json([
            'results' => $products
        ], 200);
    }
    public function getCategoriesProducts()
    {
        $categories = Category::where('status_category', 1)->with('products')->get();
        if (!$categories) {
            return response()->json([
                'message' => 'Danh mục sản phẩm không chứa sản phẩm nào'
            ], 404);
        }
        return response()->json([
            'results' => $categories
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

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Product::destroy($id);
        // return response()->json(null, 204);
    }
}
