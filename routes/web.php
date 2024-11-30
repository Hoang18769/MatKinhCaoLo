<?php

use App\Http\Controllers\Admin\AccountController;
use Illuminate\Support\Facades\Route;
//===========================================
//===========================================
// FRONTEND
//===========================================
//===========================================





//===========================================
//===========================================
// BACKEND
//===========================================
//===========================================
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\API\SocialLoginController;
use \App\Http\Controllers\Admin\FavoriteController;
use \App\Http\Controllers\Admin\FeedbackController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//========= Đăng nhập =========================
//========= BACKEND =========================
Route::get('/admin/login', [LoginController::class, 'showLogin'])->name('admin.showlogin');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login');
Route::get('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

//========= Đăng nhập Google Test=========================
Route::get('/auth/google',[SocialLoginController::class, 'goToGoogle'])->name('goToGoogle');
Route::get('/login-google',[SocialLoginController::class, 'loginGoogle']);

//========= Đăng nhập Facebook Test=========================
// Route::get('/auth/facebook',[SocialLoginController::class, 'goToFacebook'])->name('goToFacebook');
// Route::get('/login-facebook',[SocialLoginController::class, 'loginFacebook']);

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [DashBoardController::class, 'showDashboard'])->name('admin.index');

    //========= QUẢN LÝ Tài Khoản =========================
    Route::resource('account', AccountController::class);
    Route::get('/account/activate/{id}', [AccountController::class, 'activate'])->name('accounts.activate');
    //========= QUẢN LÝ Đơn Hàng =========================
    Route::resource('order', OrderController::class);
    Route::get('order/update_status/{id}/{status}', [OrderController::class, 'updateStatus'])->name('orders.update_status');
    Route::get('order/delete/{id}', [OrderController::class, 'delete'])->name('orders.delete');
    Route::get('order/{id}/print', [OrderController::class, 'printInvoice'])->name('orders.print');


    //========= QUẢN LÝ Khách Hàng =========================
    Route::resource('customer', CustomerController::class);
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
    //========= QUẢN LÝ DANH MỤC SẢN PHẨM =========================
    Route::resource('category', CategoryController::class);
    //========= QUẢN LÝ BANNER =========================
    Route::resource('banner', BannerController::class);
    Route::get('banners/activate/{id}', [BannerController::class, 'activate'])->name('banner.activate');
    //========= QUẢN LÝ SẢN PHẨM =========================
    Route::resource('product', ProductController::class);
    Route::delete('/product/delete-variant/{id}', [ProductController::class, 'deleteVariant'])->name('delete.variant');
    //============== Quản ký màu sắc =========================
    Route::resource('color', ColorController::class);
    //============== Quản lý kích thước ======================
    Route::resource('size', SizeController::class);
    //============== Quản lý Mã Giảm Giá ======================
    Route::resource('discount', DiscountController::class);
    Route::get('/discount/delete/{id}', [DiscountController::class, 'delete'])->name('discount.delete');
    //============== Quản lý Yêu Thích ======================
    Route::resource('favorite', FavoriteController::class);

    //============== Quản lý Yêu Thích ======================
    Route::resource('feedback', FeedbackController::class);
    Route::get('/feedback/activate/{id}', [FeedbackController::class, 'activate'])->name('feedback.activate');
    Route::get('/feedbacks/{id}/{productId}', [FeedbackController::class, 'show1'])->name('feedback.show1');
    //Route::get('/feedback/delete/{id}', [FeedbackController::class, 'delete'])->name('feedback.delete');

});






