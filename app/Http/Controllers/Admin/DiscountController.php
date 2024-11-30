<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Discount\StoreDiscountRequest;
use App\Http\Requests\Discount\UpdateDiscountRequest;

use App\Models\Discount;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discount.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.discount.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreDiscountRequest $request)
    {

        $expirationDateInput = $request->input('expiration_date');

        try {
            $expiration_date = Carbon::createFromFormat('d/m/Y H:i', $expirationDateInput);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Định dạng ngày tháng không hợp lệ: ' . $e->getMessage());
        }
        $expiration_date = Carbon::createFromFormat('d/m/Y H:i', $request->input('expiration_date'));

        $dateNow = Carbon::now();
        if ($expiration_date < $dateNow) {
            return redirect()->back()->with('error', 'Ngày và giờ hết hạn phải lớn hơn hoặc bằng ngày và giờ hiện tại.');
        }

        $discount = new Discount();
        $discount->code = $request->input('code');
        $discount->expiration_date = $expiration_date;
        $discount->discount = (float) str_replace(',', '', $request->input('discount'));
        $discount->limit_number = (int) str_replace(',', '', $request->input('limit_number'));
        $discount->payment_limit = (int) str_replace(',', '', $request->input('payment_limit'));


        $discount->save();

        return redirect()->route('discount.index')->with('success', 'Mã giảm giá đã được thêm thành công.');
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
        $discount = Discount::findOrFail($id);
        return view('admin.discount.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, string $id)
    {

        $discount = Discount::findOrFail($id);

        // Kiểm tra xem mã giảm có tồn tại không
        if (!$discount) {
            return redirect()->back()->with('error', 'Không tìm thấy mã giảm.');
        }
        $expiration_date = Carbon::createFromFormat('d/m/Y H:i', $request->input('expiration_date'));

        $dateNow = Carbon::now();
        if ($expiration_date < $dateNow) {
            return redirect()->back()->with('error', 'Ngày và giờ hết hạn phải lớn hơn hoặc bằng ngày và giờ hiện tại.');
        }
        $discount->code = $request->input('code');
        $discount->expiration_date = $expiration_date;
        $discount->discount = (float) str_replace(',', '', $request->input('discount'));
        $discount->limit_number = (int) str_replace(',', '', $request->input('limit_number'));
        $discount->number_used = (int) str_replace(',', '', $request->input('number_used'));
        $discount->payment_limit = (int) str_replace(',', '', $request->input('payment_limit'));
        $discount->save();

        return redirect()->route('discount.index')->with('success', 'Mã giảm đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
    public function delete(string $id)
    {
        $discount = Discount::find($id);

        // Kiểm tra xem mã giảm giá có tồn tại trong bất kỳ đơn hàng nào không
        $ordersUsingDiscount = Order::where('id_discount', $id)->exists();

        if ($ordersUsingDiscount) {
            return redirect()->back()->with('error', 'Không thể xóa mã giảm giá vì nó được sử dụng trong các đơn hàng.');
        }

        $discount->delete();

        return redirect()->back()->with('success', 'Mã giảm giá đã được xóa thành công.');
    }
}
