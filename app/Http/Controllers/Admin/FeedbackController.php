<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $feedbacks = Feedback::all();

        $feedbacks = Feedback::with(['product', 'customer'])->get();
       // Lấy thông tin sản phẩm và trung bình đánh giá của sản phẩm đó
        $productsWithFeedback = Feedback::select('id_product', DB::raw('ROUND(AVG(rating),2 ) as average_rating'))
        ->groupBy('id_product')
        ->with('product')
        ->get();
        return view('admin.feedbacks.index', compact('feedbacks','productsWithFeedback'));
    }


    public function activate($id)
    {
        // Tìm feedback cần kích hoạt/ngừng kích hoạt
        $feedback = Feedback::findOrFail($id);

        // Kiểm tra trạng thái hiện tại và đổi ngược lại
        if ($feedback->feedback_status == 1) {
            $feedback->feedback_status = 0;
            $message = 'Ẩn bình luận';
        } else {
            $feedback->feedback_status = 1;
            $message = 'Hiện bình luận';
        }

        // Lưu lại thay đổi
        $feedback->save();

        // Trả về thông báo và điều hướng lại trang trước
        return redirect()->back()->with('success', $message);
    }

    // public function delete($id)
    // {
    //     $feedback = Feedback::find($id);
    //     // $feedback->delete();
    //     // return redirect()->back()->with('success', 'Đã xoá Feedback khách hàng thành công');
    //     if ($feedback) {
    //         $feedback->delete();
    //         return redirect()->back()->with('success', 'Đã xoá Feedback khách hàng thành công');
    //     } else {
    //         return redirect()->back()->with('error', 'Không tìm thấy Feedback');
    //     }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show1(string $id, string $productId)
    {
        $customerIds = explode(',', $id); // Tách chuỗi ID thành mảng

        // Lấy danh sách khách hàng từ danh sách ID
        $customers = Customer::whereIn('id_customer', $customerIds)->get();
        // $feedbacks = Feedback::with(['product', 'customer'])->get();
        // Lấy danh sách feedbacks liên quan đến sản phẩm cụ thể
        $feedbacks = Feedback::with(['product', 'customer'])
        ->where('id_product', $productId)
        ->get();
        return view('admin.feedbacks.view',compact('customers','feedbacks'));
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
