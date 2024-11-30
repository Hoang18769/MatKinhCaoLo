<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::orderBy('created_at','desc')->paginate(10);
        return view('admin.accounts.index',compact('accounts'));
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
        $account = Account::find($id);
        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, string $id)
    {
        $account = Account::find($id);
        if (!$account) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }
        $validatedData = $request->validated(); // validated là phương thức của Laravel ở bên Reques

        // Kiểm tra xem tên tài khoản và email có thay đổi không
        if ($validatedData['name_account'] !== $account->name_account || $validatedData['email_account'] !== $account->email_account) {
            return back()->withErrors(['error' => 'Không được phép sửa tên hoặc email tài khoản']);
        }
        // Kiểm tra xem mật khẩu thay đổi có trùng với mật khẩu hiện tại không
        if ($request->filled('password_account') && md5($validatedData['password_account']) === $account->password_account) {
            return back()->withErrors(['error' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại']);
        }
        $account->name_account = $validatedData['name_account'];
        $account->email_account = $validatedData['email_account'];

        if ($request->filled('password_account')) {
            $account->password_account = md5($validatedData['password_account']);
        }
        $account->save();

        session()->flash('success', 'Chỉnh sửa tài khoản thành công');
        return redirect()->route('account.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function activate($id){
        $account = Account::find($id);

        if (!$account) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }

        $account->status_account = $account->status_account == 1 ? 0 : 1;
        $account->save();

        $message = $account->status_account == 1 ? 'Mở khóa' : 'Khóa';

        return redirect()->back()->with('success', 'Thay đổi trạng thái tài khoản thành công');
    }
}
