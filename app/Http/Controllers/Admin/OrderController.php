<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('paymentmethod')->orderBy('created_at', 'desc');

        // Lọc theo tên, mã, số điện thoại, email
        $nameCodeEmailPhone = $request->input('namecodeemailphone_filter');
        if ($nameCodeEmailPhone) {
            $query->where(function ($q) use ($nameCodeEmailPhone) {
                $q->where('name_order', 'like', '%' . $nameCodeEmailPhone . '%')
                    ->orWhere('code_order', 'like', '%' . $nameCodeEmailPhone . '%')
                    ->orWhere('email_order', 'like', '%' . $nameCodeEmailPhone . '%')
                    ->orWhere('phone_order', 'like', '%' . $nameCodeEmailPhone . '%');
            });
        }

        $paymentFilter = $request->input('payment_filter');
        if ($paymentFilter) {
            $query->whereHas('paymentmethod', function ($q) use ($paymentFilter) {
                $q->where('id_payment', $paymentFilter);
            });
        }

        $statusFilter = $request->input('status_filter');
        if ($statusFilter !== null) {
            $query->where('status_order', $statusFilter);
        }

        $startDate = $request->input('start_date_filter');
        $endDate = $request->input('end_date_filter');

        if ($startDate && $endDate) {
            $query->whereBetween('date_order', [
                Carbon::createFromFormat('d/m/Y H:i', $startDate)->format('Y-m-d H:i:s'),
                Carbon::createFromFormat('d/m/Y H:i', $endDate)->format('Y-m-d H:i:s')
            ]);
        } elseif ($startDate) {
            $query->where('date_order', '>=', Carbon::createFromFormat('d/m/Y', $startDate)->format('Y-m-d H:i:s'));
        } elseif ($endDate) {
            $query->where('date_order', '<=', Carbon::createFromFormat('d/m/Y', $endDate)->format('Y-m-d H:i:s'));
        }

        $paymentMethods = Payment::pluck('name_payment', 'id_payment');

        $orders = $query->paginate(10);
        //->orderBy('created_at', 'desc') đơn hàng mới lên đầu
        return view('admin.order.index', compact('orders', 'paymentMethods'));
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
        $order = Order::findOrFail($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }
        // dd($order->discount);
        $orderDetails = $order->orderDetails;
        return view('admin.order.view', compact('order', 'orderDetails'));
    }
    public function delete($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }
        if($order->status_order === 5){
            // Xóa các chi tiết đơn hàng liên quan
        $order->orderDetails()->delete();

        // Xóa đơn hàng
        $order->delete();

        return redirect()->route('admin.order.index')->with('success', 'Đơn hàng đã được xoá thành công.');
        }
        // //Kiểm tra đơn hàng đang giao hàng
        if($order->status_order === 3){
            return redirect()->back()->with('error','Không thể xóa đơn đang giao hàng');
        }
        //Kiểm tra đơn hàng đâ giao thành công
        if($order->status_order === 4){
            return redirect()->back()->with('error','Không thể xóa đơn đã giao thành công');
        }
        // Kiểm tra nếu đơn hàng chưa được hủy
        if ($order->status_order !== 0) {
            return redirect()->back()->with('error', 'Vui lòng hủy đơn trước khi xóa');
        }
        // Xóa các chi tiết đơn hàng liên quan
        $order->orderDetails()->delete();
        // Xóa đơn hàng
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Đơn hàng đã được xoá thành công.');
    }

    public function updateStatus($id, $status)
    {
        $order = Order::find($id);

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }

        $order->status_order = $status;
        $order->save();

        if ($status == 5 || $status == 0 ){
            foreach ($order->orderDetails as $orderDetail) {
                // Tìm sản phẩm và kích thước tương ứng để tăng số lượng
                $productSize = ProductVariant::where('id_product', $orderDetail->productid)
                    ->where('id_size', $orderDetail->sizeid)
                    ->where('id_color', $orderDetail->colorid) // Tìm theo màu sắc
                    ->first();
                if ($productSize) {
                    // Tăng số lượng sản phẩm và kích thước tương ứng
                    $productSize->quantity += $orderDetail->quantity;
                    $productSize->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.');
    }

    public function printInvoice($id)
    {
        $order = Order::findOrFail($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');
        }
        $orderDetails = $order->orderDetails;

        // Trả về view hoặc PDF để in hoá đơn
        return view('admin.order.invoice', compact('order', 'orderDetails'));
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
