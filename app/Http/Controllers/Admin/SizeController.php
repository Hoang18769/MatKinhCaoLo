<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Size\StoreSizeRequest;
use App\Http\Requests\Size\UpdateSizeRequest;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::all();
        return view('admin.size.sizes', compact('sizes'));
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
    public function store(StoreSizeRequest $request)
    {
        try {
            $size = new Size();
            $size->desc_size = $request->input('desc_size');
            $size->save();
            session()->flash('success', 'Kích thước đã được thêm thành công');
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
        $size = Size::find($id);
        $sizes = Size::all(); // Đưa danh sách màu sắc để hiển thị hoặc chọn màu sẵn có trong form (tuỳ nhu cầu)

        return view('admin.size.sizes', compact('size', 'sizes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSizeRequest $request, string $id)
    {
        try {
            $size = Size::find($id);
            $size->desc_size = $request->input('desc_size');
            $size->save();

            session()->flash('success', 'Kích thước đã được cập nhật thành công');
            return redirect()->route('size.index');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $size = Size::find($id);

        $productVariants = ProductVariant::where('id_size', $id)->exists();

        if ($productVariants) {
            return redirect()->back()->with('error', 'Không thể xóa màu sắc vì màu sắc đang được sử dụng trong sản phẩm.');
        }

        $size->delete();

        return redirect()->back()->with('success', 'Đã xóa màu sắc thành công.');
    }
}
