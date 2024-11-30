<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
class DashBoardController extends Controller
{
    // Hiển thị trang Dashboard
    public function showDashboard()
    {
        // Tính tổng doanh thu
        $totalRevenue = Order::whereIn('status_order', [1,2, 3, 4])->sum('total_order');

        // Lấy ngày đầu tuần và cuối tuần hiện tại
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Tính tổng doanh thu cho tuần này
        $weeklyRevenue = Order::whereIn('status_order', [1, 2, 3, 4])
                            ->whereBetween('date_order', [$startOfWeek, $endOfWeek])
                            ->sum('total_order');
        // Tính tổng doanh thu trong ngày hôm nay
        $totalRevenueToday = Order::whereIn('status_order', [1, 2, 3, 4])
        ->whereDate('date_order', Carbon::today())
        ->sum('total_order');
        // Sản phẩm hết hàng
        $outOfStockProducts = ProductVariant::where('quantity', 0)->count();

        // Khách hàng mới
        $currentDate = Carbon::now();
        $oneWeekAgo = $currentDate->subWeek();
        $newCustomers = Customer::where('created_at', '>=', $oneWeekAgo)->count();

        // Đơn hàng mới
        $newOrders = Order::where('created_at', '>=', $oneWeekAgo)->count();


        //===========================================================
        //=====Doanh thu Tuần
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek()->format('Y-m-d');
        $endOfWeek = $now->endOfWeek()->format('Y-m-d');
        $orders = Order::select('total_order', 'date_order')
            ->whereBetween('date_order', [$startOfWeek, $endOfWeek])
            ->orderBy('date_order', 'asc')
            ->get();

        $categories = [];
        $revenues = [];


        foreach ($orders as $order) {
            $categories[] = Carbon::parse($order->date_order)->format('d/m/Y');
            $revenues[] = (int) $order->total_order;
        }
        //===========================================================
        //=====Doanh thu Ngày
        $today = Carbon::now()->format('Y-m-d');
        $orders = Order::select('total_order', 'date_order')
            ->whereDate('date_order', $today)
            ->orderBy('date_order', 'asc')
            ->get();

        $categories1 = [];
        $revenues1 = [];

        foreach ($orders as $order) {
            $categories1[] = Carbon::parse($order->date_order)->format('d/m/Y');
            $revenues1[] = (int) $order->total_order;
        }
        //===========================================================
        // Lấy dữ liệu từ bảng orders
        $orders = Order::select('status_order', 'date_order')->orderBy('date_order', 'asc')
            ->whereIn('status_order', [1, 2, 3, 4,5])
            ->get();

        // Tạo các biến cho từng loại status_order
        $deliveryStatus = [
            'Chờ xác nhận' => 0,
            'Đã xác nhận' => 0,
            'Đang giao' => 0,
            'Đã giao' => 0,
            'Hoàn trả' => 0,
        ];

        // Lặp qua dữ liệu và đếm số lượng cho từng loại status_order
        foreach ($orders as $order) {
            switch ($order->status_order) {
                case 1:
                    $deliveryStatus['Chờ xác nhận']++;
                    break;
                case 2:
                    $deliveryStatus['Đã xác nhận']++;
                    break;
                case 3:
                    $deliveryStatus['Đang giao']++;
                    break;
                case 4:
                    $deliveryStatus['Đã giao']++;
                    break;
                    case 5:
                        $deliveryStatus['Hoàn trả']++;
                        break;

            }
        }

        // Format lại các cột date_order thành dd/mm/yyyyy và nhóm chúng lại
        $formattedDates = [];
        foreach ($orders as $order) {
            $date = Carbon::parse($order->date_order)->format('d/m/Y');
            $formattedDates[$date] = $date;
        }

        // Tạo dữ liệu series cho biểu đồ
        $series = [];
        foreach ($deliveryStatus as $status => $count) {
            $data = [];
            foreach ($formattedDates as $date) {
                // Nếu có dữ liệu thì lấy số lượng, ngược lại lấy 0
                $data[] = $deliveryStatus[$status] ?? 0;
            }
            $series[] = ['name' => $status, 'data' => $data];
        }
        $latestCustomers = Customer::latest()->take(5)->get();
        $topSellingProducts = Product::orderBy('number_buy', 'desc')->take(5)->get();


        // Truyền các giá trị vào view
        return view('admin.index', compact('totalRevenue','totalRevenueToday', 'outOfStockProducts', 'newCustomers',
         'newOrders', 'categories', 'revenues', 'formattedDates', 'series', 'latestCustomers', 'topSellingProducts',
          'categories1', 'revenues1', 'weeklyRevenue'));
    }
}
