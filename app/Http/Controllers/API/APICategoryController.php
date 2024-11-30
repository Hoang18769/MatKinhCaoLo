<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\Category;
class APICategoryController extends Controller
{
    //
    function index()
    {
        return response()->json([
            'results'=>Category::all()
        ],200);
    }
    function show($id)
    {
        // return response()->json([
        //     'results'=>Category::find($id)
        // ],200);
        $categories = Category::find($id);
        if(!$categories){
            return response()->json([
                'message'=>'Danh mục sản phẩm Không tìm thấy'
            ],404);
        }
        return response()->json([
            'categories'=>$categories
        ],200);
    }
}

