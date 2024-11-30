<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function goToGoogle()
    {
        // return Socialite::driver('google')->redirect();
        //return Socialite::driver('google')->stateless()->redirect();
        return response()->json([
            'url' => Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl(),
        ]);
    }




    // public function loginGoogle()
    // {
    //     $googleUser = Socialite::driver('google')->stateless()->user();
    //     dd($googleUser);
    // }


    public function loginGoogle()
    {
        try {

            $googleUser = Socialite::driver('google')->stateless()->user();

            // $refreshToken = $googleUser->refreshToken; // Đây là refresh_token mà bạn cần lưu lại
            // $expiresIn = $googleUser->expiresIn;

            // Kiểm tra xem người dùng đã tồn tại chưa dựa trên id_google
            $customer = Customer::where('id_google', $googleUser->getId())->first();
            if (!$customer) {
                // Kiểm tra email để phát hiện xung đột
                $accountWithEmail = Customer::where('email_customer', $googleUser->getEmail())->first();
                if ($accountWithEmail) {
                    return response()->json(['message' => 'Email này đã được liên kết với một tài khoản Google khác.'], 400);
                }

                // Tạo tài khoản mới
                $account = Account::create([
                    'name_account' => ' ',
                    'email_account' => $googleUser->getEmail(),
                    'password_account' => '',
                    'status_account' => 1,
                ]);

                // Tạo thông tin khách hàng mới
                $customer = Customer::create([
                    'name_customer' => $googleUser->getName(),
                    'email_customer' => $googleUser->getEmail(),
                    'id_google' => $googleUser->getId(),
                    'phone_customer' => '',
                    'address_customer' => '',
                    'id_facebook' => 0,
                    'id_account' => $account->id_account,
                ]);
            } else {
                // Nếu tài khoản đã tồn tại, lấy thông tin tài khoản liên kết
                $account = $customer->account;
            }
            // Tạo token truy cập cho API
            $token = $account->createToken('GoogleAuthToken')->plainTextToken;
            // Trả về phản hồi với token và thông báo thành công
            return response()->json([
                'message' => 'Đăng nhập thành công',
                'user' => $customer,
                'token' => $token,
                // 'refresh_token' => $refreshToken,
                // 'expires_in' => $expiresIn,

            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đăng nhập Google thất bại', 'error' => $e->getMessage()], 500);
        }
    }
}
    // $scopes = [
    //     'https://www.googleapis.com/auth/webmasters',
    //     'https://www.googleapis.com/auth/webmasters.readonly',
    //     'https://www.googleapis.com/auth/analytics.readonly',
    //     'https://www.googleapis.com/auth/userinfo.profile',
    //     'https://www.googleapis.com/auth/userinfo.email',
    //   ];
    // $parameters = ['access_type' => 'offline'];
    // return Socialite::driver('google')->stateless()->redirect();
