<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->has('name_filter')) {
            $keywords = explode(' ', $request->input('name_filter'));

            $query->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where(function ($subQuery) use ($word) {
                        $subQuery->where('name_customer', 'like', '%' . $word . '%');
                    });
                }
            });
        }

        // Lọc theo email
        if ($request->has('email_filter')) {
            $query->where('email_customer', 'like', '%' . $request->input('email_filter') . '%');
        }

        // Lọc theo số điện thoại
        if ($request->has('phone_filter')) {
            $query->where('phone_customer', 'like', '%' . $request->input('phone_filter') . '%');
        }
        $customerss = $query->orderBy('created_at', 'desc')->paginate(10);
        $customers = $query->get();

        return view('admin.customer.index',compact('customers','customerss'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customers = Customer::find($id);
        return view('admin.customer.edit', compact('customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, string $id)
    {
        // Tìm khách hàng theo ID
        $customer = Customer::find($id);

        // Nếu không tìm thấy khách hàng, hiển thị thông báo lỗi và chuyển hướng về trang trước
        if (!$customer) {
            return redirect()->back()->with('error', 'Không tìm thấy khách hàng');
        }

        // Validate dữ liệu từ form
        $validatedData = $request->validated(); // validated là phương thức của Laravel ở bên Reques


        // Kiểm tra xem tên tài khoản và email có thay đổi không
        if ($validatedData['email_customer'] !== $customer->email_customer) {
            return redirect()->back()->with(['error' => 'Không được phép sửa email tài khoản']);
        }
        // Cập nhật thông tin khách hàng
        $customer->name_customer = $validatedData['name_customer'];
        $customer->phone_customer = $validatedData['phone_customer'];
        $customer->address_customer = $validatedData['address_customer'];
        // $customer->email_customer = $validatedData['email_customer'];

        // Lưu các thay đổi vào cơ sở dữ liệu
        $customer->save();

        // // Cập nhật thông tin email_account của account tương ứng
        // $account = $customer->account;
        // if ($account) {
        //     $account->email_account = $validatedData['email_customer'];
        //     $account->save();
        // }

        // Chuyển hướng về trang danh sách khách hàng với thông báo thành công
        return redirect()->route('customer.index')->with('success', 'Đã cập nhật thông tin khách hàng');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
    public function delete($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return redirect()->back()->with('error', 'Không tìm thấy khách hàng');
        }
        // Kiểm tra xem khách hàng có đơn hàng hay không
        if ($customer->orders()->exists()) {
            return redirect()->back()->with('error', 'Không thể xóa khách hàng vì khách hàng này có đơn hàng ');
        }
        // Xóa khách hàng
        $accountId = $customer->id_account;
        $customer->delete();

        // Xóa tài khoản nếu không còn khách hàng nào kết nối
        $relatedCustomers = Customer::where('id_account', $accountId)->count();
        if ($relatedCustomers === 0) {
            $account = Account::find($accountId);
            if ($account) {
                $account->delete();
            }
        }
        return redirect()->back()->with('success', 'Đã xoá khách hàng và tài khoản liên kết thành công');
    }
}
