<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request )
    {
        $query = Product::query();
        // Lọc theo tên
        // if ($request->has('name_filter')) {
        //     $query->where('name_product', 'like', '%' . $request->input('name_filter') . '%');
        // }
        if ($request->has('name_filter')) {
            $nameFilter = $request->input('name_filter');
            $keywords = explode(' ', $nameFilter);

            $query->where(function($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->where('name_product', 'like', '%' . $word . '%');
                }
            });
        }

        // Lọc theo sku
        if ($request->has('sku_filter')) {
            $query->where('sku', 'like', '%' . $request->input('sku_filter') . '%');
        }

        // Lọc theo tên danh mục
        if ($request->has('category_filter')) {
            $category = Category::where('name_category', $request->input('category_filter'))->first();
            if ($category) {
                $query->where('id_category', $category->id_category);
            }
        }

        // Lọc theo trạng thái
        if ($request->has('status_filter')) {
            $status = $request->input('status_filter');
            if ($status == 'active') {
                $query->where('status_product', 1);
            } elseif ($status == 'inactive') {
                $query->where('status_product', 0);
            }
        }

        if ($request->has('stock_filter')) {
            $stockFilter = $request->input('stock_filter');

            // Lọc theo tồn kho còn hàng
            if ($stockFilter === 'available') {
                $query->whereHas('variants', function ($q) {
                    $q->where('quantity', '>', 0);
                });
            }

            // Lọc theo tồn kho hết hàng
            if ($stockFilter === 'out_of_stock') {
                $query->whereDoesntHave('variants', function ($q) {
                    $q->where('quantity', '>', 0);
                });
            }
        }

        $products = $query->paginate(10);
        $categories = Category::whereHas('products')->get();

        foreach ($query as $product) {
            $variants = ProductVariant::where('id_product', $product->id_product)->get();

            // Tính tổng số lượng tồn kho dựa trên quantity từ các biến thể của sản phẩm
            $totalStock = $variants->sum('quantity');

            // Gán giá trị vào thuộc tính total_stock
            $product->total_stock = $totalStock;
        }

        // Lấy danh sách các danh mục
        return view('admin.product.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();
        $categoriesTree = $this->buildCategoryTree($categories);
        return view('admin.product.create', compact('categoriesTree', 'sizes', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // dd($request->all());

        $sellPrice = $request->input('sellprice_product') ? (int) str_replace(',', '', $request->input('sellprice_product')) : min(0, PHP_INT_MAX);
        $product = new Product();
        $product->name_product = $request->input('name_product');
        $product->sku = $request->input('sku');
        $product->sortdect_product = $request->input('sortdect_product');
        $product->desc_product = $request->input('desc_product');
        $product->number_buy = 0;
        $product->status_product = 1;
        $product->id_category = $request->input('id_category');
        $product->price_product = (int) str_replace(',', '', $request->input('price_product'));
        $product->sellprice_product = $sellPrice;
        $product->avt_product = $request->input('avt_product'); // Lưu ảnh đại diện

        // Lấy danh sách các đường dẫn URL từ input có tên image_product
        $imageURLs = $request->input('image_product');
        $imageNames = []; // lưu tên URL ảnh
        // Duyệt qua mỗi đường dẫn URL
        foreach ($imageURLs as $url) {
            // Lấy tên tệp từ URL
            $imageNames[] = $url;
        }
        $product->image_product = implode('#', $imageNames); // Sử dụng ký tự ngăn cách "#" giữa các tên hình ảnh
        $product->save();

        $variants = $request->input('variants');
        if ($variants) {
            foreach ($variants as $variant) {
                $variantData = json_decode($variant, true); // Chuyển đổi từ chuỗi JSON thành mảng

                $productVariant = new ProductVariant();
                $productVariant->id_product = $product->id_product; // Chỉnh sửa tên của biến $product tương ứng với sản phẩm đang được thêm
                $productVariant->id_color = $variantData['color'];
                $productVariant->id_size = $variantData['size'];
                $productVariant->quantity = $variantData['quantity'];
                $productVariant->save();
            }
        }

        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được thêm thành công.');
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
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm để sửa đổi.');
        }

        // Lấy danh mục của sản phẩm dựa trên mối quan hệ trong model Product
        $category = $product->category;

        // Tiếp tục lấy thông tin cần thiết khác
        $sizes = Size::all();
        $colors = Color::all();
        $categories = Category::all();
        $categoriesTree = $this->buildCategoryTree($categories);
        $productVariants = ProductVariant::where('id_product', $product->id_product)->get();
        return view('admin.product.edit', compact('product', 'category', 'categoriesTree', 'sizes', 'colors', 'productVariants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {


        $sellPrice = $request->input('sellprice_product') ? (int) str_replace(',', '', $request->input('sellprice_product')) : min(0, PHP_INT_MAX);
        $product = Product::findOrFail($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm');
        }
        $oldAvtProduct = $product->avt_product;
        $product->name_product = $request->input('name_product');
        $product->sku = $request->input('sku');
        $product->sortdect_product = $request->input('sortdect_product');
        $product->desc_product = $request->input('desc_product');
        $product->id_category = $request->input('id_category');
        $product->price_product = (int) str_replace(',', '', $request->input('price_product'));
        $product->sellprice_product = $sellPrice;
        $newAvtProductUrl = $request->input('avt_product');

        // Kiểm tra xem URL ảnh đại diện đã thay đổi hay không
        if ($newAvtProductUrl && $newAvtProductUrl !== $oldAvtProduct) {
            // Loại bỏ URL ảnh đại diện cũ khỏi danh sách image_product nếu tồn tại
            if ($oldAvtProduct) {
                $imageList = explode('#', $product->image_product);
                $imageList = array_diff($imageList, [$oldAvtProduct]);
                $product->image_product = implode('#', $imageList);
            }
            // Cập nhật URL ảnh đại diện mới
            $product->avt_product = $newAvtProductUrl;

            // Thêm URL ảnh mới vào danh sách image_product
            $imageList = explode('#', $product->image_product);
            array_unshift($imageList, $newAvtProductUrl); // Thêm URL ảnh mới vào đầu mảng
            $product->image_product = implode('#', $imageList);
        }

        // Xử lý các URL ảnh trong album
        $imageURLs = $request->input('image_product', []);
        $product->image_product = implode('#', $imageURLs);



        $product->status_product = $request->has('status_product') ? 1 : 0;
        $product->save();

        // Xử lý lưu biến thể
        $variants = $request->input('variants');
        // Xử lý lưu biến thể
        if ($variants) {
            // Lấy danh sách các biến thể được chọn từ giao diện người dùng
            $receivedVariants = collect($variants)->map(function ($variant) {
                return json_decode($variant, true);
            });

            // Lấy danh sách biến thể hiện có trong cơ sở dữ liệu của sản phẩm
            $existingVariants = ProductVariant::where('id_product', $id)->get();

            // Xóa các biến thể không còn được chọn từ cơ sở dữ liệu
            foreach ($existingVariants as $existingVariant) {
                $variantExists = $receivedVariants->contains(function ($receivedVariant) use ($existingVariant) {
                    return $existingVariant->id_color == $receivedVariant['color'] && $existingVariant->id_size == $receivedVariant['size'];
                });

                if (!$variantExists) {
                    $existingVariant->delete();
                }
            }

            // Thêm những biến thể mới
            foreach ($receivedVariants as $receivedVariant) {
                $colorId = intval($receivedVariant['color']);
                $sizeId = intval($receivedVariant['size']);
                $quantity = intval($receivedVariant['quantity']);

                // Kiểm tra xem biến thể đã tồn tại hay chưa
                $existingVariant = ProductVariant::where('id_product', $id )
                    ->where('id_color', $colorId)
                    ->where('id_size', $sizeId)
                    ->first();

                if ($existingVariant) {
                    // Biến thể đã tồn tại, kiểm tra xem số lượng đã được cập nhật chưa
                    if ($existingVariant->quantity != $quantity) {
                        // Nếu số lượng đã cập nhật, cập nhật lại số lượng mới
                        $existingVariant->quantity = $quantity;
                        $existingVariant->save();
                    }
                } else {
                    // Biến thể chưa tồn tại, thêm mới
                    $newVariant = new ProductVariant();
                    $newVariant->id_product = $id;
                    $newVariant->id_color = $colorId;
                    $newVariant->id_size = $sizeId;
                    $newVariant->quantity = $quantity;
                    $newVariant->save();
                }
            }
        }


        return redirect()->route('product.index')->with('success', 'Sản phẩm đã cập nhật thành công.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Không tìm thấy sản phẩm để xóa.');
        }

        // Kiểm tra nếu SP có trong bất kỳ chi tiết đơn hàng nào thông qua ProductVariant
        $productVariantIds = ProductVariant::where('id_product', $id)->pluck('id_product_variants');
        $orderDetailExists = OrderDetail::whereIn('id_product_variants', $productVariantIds)->exists();

        if ($orderDetailExists) {
            return redirect()->back()->with('error', 'Không thể xóa sản phẩm vì sản phẩm đang có trong đơn hàng');
        }

        // // Xóa những size và color tương ứng trong bảng ProductVariant
        // ProductVariant::where('id_product', $id)->delete();
        // // Xóa sản phẩm nếu không có trong OrderDetail
        // $product->delete();
        // return redirect()->back()->with('success', 'Đã xóa sản phẩm thành công.');
        try {
            // Xóa những size và color tương ứng trong bảng ProductVariant
            ProductVariant::where('id_product', $id)->delete();
            // Xóa sản phẩm nếu không có trong OrderDetail
            $product->delete();
            return redirect()->back()->with('success', 'Đã xóa sản phẩm thành công.');
        } catch (\Exception $e) {
            // Nếu có lỗi xảy ra trong quá trình xóa
            return redirect()->back()->with('error', 'Xóa sản phẩm thất bại: ' . $e->getMessage());
        }
    }
    // Hàm đệ quy để xây dựng cây danh mục theo cấp bậc
    private function buildCategoryTree($categories, $parentId = null)
    {
        $categoryTree = [];
        foreach ($categories as $category) {
            if ($category->id_parent === $parentId) {
                $children = $this->buildCategoryTree($categories, $category->id_category);
                if ($children) {
                    $category->setAttribute('children', $children);
                }
                $categoryTree[] = $category;
            }
        }
        return $categoryTree;
    }

    public function deleteVariant($id)
    {
        $variantId = (int) $id;

        $variant = ProductVariant::find($variantId);

        if ($variant) {

            // Nếu không được sử dụng trong OrderDetail, tiến hành xóa biến thể

            // Xoá biến thể thành công, tiến hành làm mới lại danh sách biến thể
            $productId = $variant->id_product;
            $variant->delete();

            $product = Product::with('variants')->find($productId);

            if ($product) {
                $variants = $product->variants;
                // Trả về danh sách biến thể mới sau khi xoá thành công
                return response()->json(['success' => 'Xoá biến thể thành công', 'variants' => $variants], 200);
            }

            return response()->json(['error' => 'Không tìm thấy sản phẩm sau khi xoá biến thể'], 400);
        }

        return response()->json(['error' => 'Biến thể không tồn tại'], 400);
    }
}
