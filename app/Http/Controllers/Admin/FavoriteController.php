<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favoriteCounts = Favorite::select('id_product', DB::raw('count(id_customer) as favorite_count'))
        ->groupBy('id_product')
        ->get();

        $favorites = Favorite::with(['product', 'customer'])->get();

        return view('admin.favorites.index', compact('favoriteCounts', 'favorites'));
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
        $customerIds = explode(',', $id); // Tách chuỗi ID thành mảng

        // Lấy danh sách khách hàng từ danh sách ID
        $customers = Customer::whereIn('id_customer', $customerIds)->get();
        return view('admin.favorites.view',compact('customers'));
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
