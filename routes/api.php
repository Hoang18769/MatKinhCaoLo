<?php

use App\Http\Controllers\API\APIAccountController;
use App\Http\Controllers\API\APICategoryController;
use App\Http\Controllers\API\APIColorController;
use App\Http\Controllers\API\APICustomerController;
use App\Http\Controllers\API\APIDiscountController;
use App\Http\Controllers\API\APIHomeController;
use App\Http\Controllers\API\APIOrderController;
use App\Http\Controllers\API\APIOrderDetailController;
use App\Http\Controllers\API\APIPaymentController;
use App\Http\Controllers\API\APIProductController;
use App\Http\Controllers\API\APIProductVariantController;
use App\Http\Controllers\API\APISizeController;
use App\Http\Controllers\API\SocialLoginController;
use \App\Http\Controllers\API\APIFavoriteController;
use \App\Http\Controllers\API\APIFeedbackController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//========= API FRONTEND =========================
Route::middleware('auth:sanctum')->group(function ( ) {
    Route::get('user', function(Request $request){
        return [
            'user' =>$request->user(),
            'currentToken' => $request->bearerToken()
        ];
    });
    Route::post('/logout',[APIAccountController::class, 'logout']);
});

//========= LOGIN ACCOUNT =========================
Route::post('/login',[APIAccountController::class, 'auth']);
Route::post('/register', [APIAccountController::class, 'store']);

//========= RESET ACCOUNT =========================
Route::post('password/email',[APIAccountController::class, 'sendResetLinkEmail']);
Route::post('password/reset',[APIAccountController::class, 'resetPassword']);
//========= USER ACCOUNT =========================
Route::get('/account', [APIAccountController::class, 'index']);
Route::get('/account/{id}', [APIAccountController::class, 'show']);
Route::put('/account/{id}', [APIAccountController::class, 'update']);


//========= USER HOME =========================
Route::get('/home', [APIHomeController::class, 'index']);
Route::get('/home/{id}', [APIHomeController::class, 'show']);
//========= USER CATEGORY =========================
Route::get('/categories', [\App\Http\Controllers\API\APICategoryController::class, 'index']);
Route::get('/categories/{id}', [APICategoryController::class, 'show']);

//========= USER SIZE =========================
Route::get('/size', [APISizeController::class, 'index']);
Route::get('/size/{id}', [APISizeController::class, 'show']);
//========= USER COLOR =========================
Route::get('/color', [APIColorController::class, 'index']);
Route::get('/color/{id}', [APIColorController::class, 'show']);
//========= USER PRODUCT =========================
Route::get('/product', [APIProductController::class, 'index']);
Route::get('/product/{id}', [APIProductController::class, 'show']);
Route::get('/products/search', [APIProductController::class, 'search']);
Route::get('/products', [APIProductController::class, 'getProducts']);

Route::get('/products/categories', [APIProductController::class, 'getCategoriesProducts']);
//========= USER ProductVariant =========================
Route::get('/products/{id}/variants', [APIProductVariantController::class, 'index']);
Route::get('/variants/{id}', [APIProductVariantController::class, 'show']);
Route::post('/variants', [APIProductVariantController::class, 'store']);

//========= USER ORDER =========================
Route::get('/order', [APIOrderController::class, 'index']);
Route::get('/order/{id}', [APIOrderController::class, 'show']);
Route::post('/orders',[APIOrderController::class, 'store']);
Route::put('/order/{id}',[APIOrderController::class, 'update']);

//========= USER ORDERDETAIL =========================
Route::get('/order/{id}/orderdetail', [APIOrderDetailController::class, 'index']);
Route::get('/orderdetail/{id}', [APIOrderDetailController::class, 'show']);
//========= USER CUSTOMER =========================
Route::get('/customer', [APICustomerController::class, 'index']);
Route::get('/customer/{id}', [APICustomerController::class, 'show']);

/////////////////Thanh toán thường.
Route::post('/customer', [APICustomerController::class, 'store']);
Route::put('/customers/{id}', [APICustomerController::class, 'update']);
Route::get('/customer/{id}/order-history', [APICustomerController::class, 'getOrderHistory']);

//========= USER PAYMENT =========================
Route::get('/payment', [APIPaymentController::class, 'index']);
Route::get('/payment/{id}', [APIPaymentController::class, 'show']);
//========= USER DISSCOUNT =========================
Route::get('/discount', [APIDiscountController::class, 'index']);
Route::get('/discount/{id}', [APIDiscountController::class, 'show']);
//========= USER API LOGIN GOOGLE =========================
Route::get('/auth/google',[SocialLoginController::class, 'goToGoogle']);
Route::get('/login-google',[SocialLoginController::class, 'loginGoogle']);
//========= USER API FAVORITE =========================

Route::get('/favorites', [APIFavoriteController::class, 'index']);
Route::get('/favorites/{id}', [APIFavoriteController::class, 'show']);
Route::post('/favorites-add', [APIFavoriteController::class, 'store']);
Route::delete('/favorites-delete', [APIFavoriteController::class, 'destroy']);
Route::get('/favorites/count/{id}', [APIFavoriteController::class, 'countFavorites']); // đếm số lượng yêu thích của KH

//========= USER API FEEDBACK =========================
Route::get('/feedback', [APIFeedbackController::class, 'index']);
Route::post('/feedback-add', [APIFeedbackController::class, 'store']);
Route::get('/feedback/{id}', [APIFeedbackController::class, 'show']);


//========= THANH TOÁN ONLINE MOMO =========================
// Route::post('/momo-payment', [APIOrderController::class, 'createPayment']);
Route::post('/momo-payment', [APIOrderController::class, 'storeMomo']);




