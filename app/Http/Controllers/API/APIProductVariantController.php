<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class APIProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($productId)
    {
        $variants = ProductVariant::where('id_product', $productId)->get();
        // isEmpty kt có rỗng hay không. nếu có báo lỗi
        if($variants->isEmpty()){
            return response()->json([
                'message'=>'Chi tiết sản phẩm không tìm thấy'
            ],404);
        }
        return response()->json($variants, 200);

        // return response()->json([
        //     'results'=>ProductVariant::all()
        // ],200);
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
        $variant = ProductVariant::create($request->all());
        return response()->json($variant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            return response()->json($variant, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Chi tiết sản phẩm không tìm thấy'
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
        $variant = ProductVariant::findOrFail($id);
        $variant->update($request->all());
        return response()->json($variant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // ProductVariant::destroy($id);
        // return response()->json(null, 204);
        $variant = ProductVariant::find($id);

    if (!$variant) {
        return response()->json([
            'message' => 'Chi tiết sản phẩm không tìm thấy'
        ], 404);
    }

    try {
        $variant->delete();
        return response()->json([
            'message' => 'Chi tiết sản phẩm đã được xóa'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Lỗi khi xóa Chi tiết sản phẩm',
            'error' => $e->getMessage()
        ], 500);
    }
    }
}
