<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //// ->orderBy('created_at', 'desc')
        $categories = Category::search()->orderBy('created_at', 'desc')->paginate(10);
        if ($categories->isEmpty()) {
            toastr()->warning("Không tìm thấy danh mục sản phẩm");
            return redirect()->route("category.index");
        }
        return view('admin.category.index', compact('categories'))->with("i", (request()->input("page", 1) - 1) * $categories->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::where('status_category', 1)
            ->whereNull('id_parent')
            ->get();
        return view('admin.category.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {

        $validatedData = $request->validated(); // validated là phương thức của Laravel ở bên Reques
        try {
            Category::create($validatedData);
            session()->flash('success', 'Danh mục đã được thêm thành công');
            return redirect()->route('category.index');
        } catch (\Exception $e) {

            return redirect()->route('category.create')->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        ///withCount('products')-> để hiện ra cột danh mục số lượng chứa bao nhiêu sản phẩm
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        $parentCategories = Category::where('status_category', 1)
            ->whereNull('id_parent')
            ->get();
        return view('admin.category.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        if ($request->input('status_category') == 0 && $category->childCategories()->exists()) {
            return redirect()->back()->with('error', 'Không thể ngừng kích hoạt vì danh mục đang được sử dụng làm danh mục cha cho danh mục khác.');
        }
        if ($request->input('status_category') == 0 && $category->products()->exists()) {
            return redirect()->back()->with('error', 'Không thể ngừng kích hoạt vì danh mục đang chứa sản phẩm.');
        }
        // $validatedData = $request->validated();
        $validatedData = $request->validate([
            'name_category' => 'required|unique:categories,name_category,'. $id. ',id_category',
            'id_parent' => 'nullable|exists:categories,id_category'
        ], [
            'name_category.required' => 'Vui lòng điền thông tin danh mục',
            'name_category.unique' => 'Tên danh mục này đã tồn tại, vui lòng nhập tên khác.'
        ]);
        // Kiểm tra nếu danh mục cha là chính nó
        if ($request->input('id_parent') == $id) {
            return redirect()->back()->with('error', 'Không thể đặt danh mục cha là chính nó.');
        }

        // Kiểm tra nếu danh mục có danh mục con
        if ($category->childCategories()->exists() && $request->input('id_parent')) {
            return redirect()->back()->with('error', 'Đã là danh mục cha thì không thể thành danh mục con.');
        }
        $validatedData['status_category'] = $request->has('status_category') ? 1 : 0;
        $category->update($validatedData);

        session()->flash('success', 'Chỉnh sửa danh mục thành công');
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'Không tìm thấy danh mục');
        }

        $pr_Prodcut = Product::where('id_category',$id)->first();
        if ($pr_Prodcut) {
            return redirect()->back()->with('error', 'Danh mục này có chứa sản phẩm. Vui lòng xóa các sản phẩm bên trong trước khi xóa danh mục này.');
        }

        $childCategories = Category::where('id_parent', $category->id_category)->first();
        if ($childCategories) {
            return redirect()->back()->with('error', 'Danh mục này có danh mục con. Vui lòng xóa các danh mục con trước khi xóa danh mục này.');
        }
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Đã xoá danh mục thành công');
    }
}
