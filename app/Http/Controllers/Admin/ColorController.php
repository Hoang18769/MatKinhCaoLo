<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Color\StoreColorRequest;
use App\Http\Requests\Color\UpdateColorRequest;

use App\Models\Color;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::all();
        return view('admin.color.colors', compact('colors'));
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
    public function store(StoreColorRequest $request)
    {
        try {
            $color = new Color();
            $color->desc_color = $request->input('desc_color');
            $color->save();
            session()->flash('success', 'Màu sắc đã được thêm thành công');
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $color = Color::find($id);
        $colors = Color::all();

        return view('admin.color.colors', compact('color', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColorRequest $request, string $id)
    {

        try {
            $color = Color::find($id);
            $color->desc_color = $request->input('desc_color');
            $color->save();

            session()->flash('success', 'Màu sắc đã được cập nhật thành công');
            return redirect()->route('color.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $color = Color::find($id);

        $productVariants = ProductVariant::where('id_color', $id)->exists();

        if ($productVariants) {
            return redirect()->back()->with('error', 'Không thể xóa màu sắc vì màu sắc đang được sử dụng trong sản phẩm.');
        }

        $color->delete();

        return redirect()->back()->with('success', 'Đã xóa màu sắc thành công.');
    }
}
