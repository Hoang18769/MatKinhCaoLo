<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\RegistrationConfirmation;
use Illuminate\Support\Facades\Log;

class APIAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Account::all();
        if ($accounts->isEmpty()) {
            return response()->json([
                'message' => 'Khong tim thay Tai Khoan'
            ], 404);
        }
        return response()->json([
            'results' => $accounts
        ], 200);
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
    // Đăng ký tài khoản khách hàng
    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name_account' => 'required|string|min:6|unique:accounts|regex:/^[a-z0-9]*$/',
             'password_account' => 'required|string|min:6',
            // 'password_account' => [
            //     'required',
            //     'string',
            //     'min:6',
            //     'regex:/[a-z]/',
            //     'regex:/[A-Z]/',
            //     'regex:/[0-9]/',
            // ],
            'name_customer' => 'required|string|max:255',
            'email_customer' => 'required|string|email|max:255|unique:customers|unique:accounts,email_account',
            'phone_customer' => 'required|digits:10|unique:customers',
            'address_customer' => 'nullable|string|max:255',
        ], [
           'name_account.required' => 'Vui long nhap ten tai khoan.',
           'name_account.unique'=>'Ten tai khoan da ton tai.',
           'name_account.min' => 'Tai khoan phai co it nhat 6 ky tu.',
           'name_account.regex' => 'Ten tai khoan khong duoc de khoang trong va chu hoa',
            // 'email_account.required' => 'Vui long nhap email tai khoan.',
            // 'email_account.email' => 'Email tai khoan khong hop le.',
            // 'email_account.unique' => 'Email tai khoan đa đuoc su dung.',
            'password_account.required' => 'Vui long nhap mat khau tai khoan.',
            'password_account.min' => 'Mat khau phai co it nhat 6 ky tu.',
            //'password_account.regex' => 'Mật khẩu phải chứa ít nhất một chữ hoa, một chữ thường và một số.',
            'name_customer.required' => 'Vui long nhap ten khach hang.',
            'email_customer.required' => 'Vui lang nhap email khach hang.',
            'email_customer.email' => 'Email khach hang khong hop le.',
            'email_customer.unique' => 'Email khach hang đa đuoc su dung.',
            'phone_customer.required' => 'Vui long nhap so dien thoai khach hang.',
            'phone_customer.digits' => 'So dien thoai khach hang phai co 10 chu so.',
            'phone_customer.unique' => 'So dien thoai khach hang da ton tai.',
            'address_customer.required' => 'Vui long nhap dia chi khach hang.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        $email = $request->email_customer;
        // Tao tài khoản mới
        $account = Account::create([
            'name_account' => $request->name_account,
            'email_account' => $email,
            'password_account' => md5($request->password_account),
            'status_account' => 1 // hoặc giá trị mặc định bạn muốn
        ]);

        // Tạo khách hàng mới và liên kết với tài khoản
        $customer = Customer::create([
            'name_customer' => $request->name_customer,
            'email_customer' => $email,
            'phone_customer' => $request->phone_customer,
            'address_customer' => $request->address_customer,
            'id_google' => 0,
            'id_facebook' => 0,
            'id_account' => $account->id_account
        ]);

         // Gửi email xác nhận đăng ký
        Mail::to($email)->send(new RegistrationConfirmation($account));
        return response()->json([
            'message' => 'Dang ky thanh cong tai khoan',
            'account' => $account,
            'customer' => $customer
        ], 201);
    }

    // Đăng nhập tài khoản
    public function auth(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator =  Validator::make($request->all(), [
            'login' => 'required|string',
            'password_account' => 'required|string|min:6',
        ], [
            'login.required' => 'Vui long nhap ten tai khoan hoac email.',
            'password_account.required' => 'Vui lang nhap mat khau.',
            'password_account.min' => 'Mat khau phai co it nhat 6 ky tu.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        }

        // Tìm tài khoản theo name_account hoặc email_account
        $accounts = Account::where('name_account', $request->login)
            ->orWhere('email_account', $request->login)
            ->first();

        // Kiểm tra tài khoản và mật khẩu
        if (!$accounts || md5($request->password_account) !== $accounts->password_account) {
            return response()->json([
                'error' => 'Dang nhap tai khoan that bai. Email hoac mat khau khong dung.'
            ], 401);
        }

        // Kiểm tra trạng thái tài khoản
        if ($accounts->status_account == 0) {
            return response()->json([
                'error' => 'Tài khoản của bạn đang bị khóa không thể đăng nhập!'
            ], 403);
        }

        $customer = $accounts->customer;
        // Ẩn thông tin customer trong account
        $accounts->makeHidden('customer');
        // Tạo token xác thực
        $token = $accounts->createToken('new_account')->plainTextToken;

        return response()->json([
            'message' => 'Đang nhap thanh cong!',
            'account' => $accounts,
            'customer' => $customer,
            'currentToken' => $token
        ], 200);
    }

    // Đăng xuất tài khoản
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Dang xuat tai khoan thanh cong. '
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $accounts = Account::findOrFail($id);
            return response()->json($accounts, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tai khoan khong tim thay'
            ], 404);
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_account' => 'required|email|exists:accounts,email_account',
        ],[
            'email_account.required' => 'Vui long nhap Email.',
            'email_account.exists' => 'Email không tồn tại trong hệ thống.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = $request->input('email_account');
        $account = Account::where('email_account', $email)->first();

        if ($account->status_account == 0) {
            return response()->json(['error' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.'], 403);
        }

        $token = Str::random(60);
        $account->update(['reset_password_token' => $token]);

        // Gửi email chứa link khôi phục mật khẩu tới email của người dùng
        $emailData = [
            'token' => $token,
            'email' => $account->email_account,
            'name_account' => $account->name_account,
        ];

        Mail::send('emails.forgot-password', $emailData, function ($message) use ($emailData) {
            $message->to($emailData['email'])
                ->subject('Yêu cầu khôi phục mật khẩu');
        });

        return response()->json([
            'success' => 'Chúng tôi đã gửi hướng dẫn khôi phục mật khẩu vào email của bạn.',
            'token' => $token]
        , 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ],[
            'password.required' => 'Vui long nhap mat khau.',
            'password.confirmed' => 'Xác nhận trường mật khẩu không khớp.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $account = Account::where('reset_password_token', $request->input('token'))->first();

        if (!$account) {
            return response()->json(['error' => 'Đường dẫn khôi phục mật khẩu không hợp lệ.'], 400);
        }

        $password = $request->input('password');

        // Kiểm tra mật khẩu trùng với mật khẩu hiện tại
        if (md5($password) === $account->password_account) {
            return response()->json(['error' => 'Mật khẩu mới không được trùng với mật khẩu hiện tại.'], 400);
        }

        $account->update([
            'password_account' => md5($password),
            'reset_password_token' => null,
        ]);

        // $account->reset_password_token
        return response()->json(['success' => 'Mật khẩu đã được khôi phục thành công. Vui lòng đăng nhập.'], 200);
    }
}
