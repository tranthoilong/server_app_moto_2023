<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Order_details;
use App\Models\Post;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $year = $request->query('year');

        // Kiểm tra nếu không có năm được truyền vào, sử dụng năm hiện tại
        if (empty($year)) {
            $year = Carbon::now()->year;
        }

        $fromDate = $year . '-01-01';
        $toDate = $year . '-12-41';

        $today_paid = Order::where('created_at', $fromDate)
            ->where('status', 4)
            ->sum('total_price');

        $total_protuct = Product::select('*')->get();

        $completeorder = Order::where('status', 4)->get();

        $pendingorder = Customer::select('*')->get();

        $total_post = Post::select('*')->get();

        $today = Carbon::now()->format('d-F-Y');

        $totalPaid = Order::sum('total_price');
        $completeOrders = Order::where('status', 4)->get();

        $orders = Order::selectRaw('MONTH(orders.created_at) AS month, COUNT(*) AS order_count, SUM(orders.total_price) AS total, SUM(orders.total_products) AS product_count')
            ->where('orders.status', 4)
            ->whereYear('orders.created_at', $year) // Thêm điều kiện năm
            ->groupBy('month')
            ->get();


        // Tạo một mảng chứa 12 tháng trong năm
        $months = range(1, 12);

        // Khởi tạo các giá trị ban đầu là 0 cho các tháng
        $monthlyOrderCount = array_fill_keys($months, 0);
        $monthlyProductCount = array_fill_keys($months, 0);
        $monthlyTotalCount = array_fill_keys($months, 0);

        // Gán dữ liệu từ $orders vào các tháng tương ứng
        foreach ($orders as $order) {
            $month = (int) $order->month;
            $monthlyOrderCount[$month] = $order->order_count;
            $monthlyProductCount[$month] = $order->product_count;
            $monthlyTotalCount[$month] = $order->total;
        }

        // Tạo các mảng dữ liệu cho biểu đồ
        $chartLabels = [];
        $chartData = [];
        $chartOrderCount = [];
        $chartProductCount = [];

        foreach ($months as $month) {
            $chartLabels[] = $month . '/'. $year;
            $chartData[] = $monthlyTotalCount[$month];
            $chartOrderCount[] = $monthlyOrderCount[$month];
            $chartProductCount[] = $monthlyProductCount[$month];
        }

        $topProducts = Order_details::select('products.name', DB::raw('SUM(order_details.quantity) as total_quantity'))
            ->join('products', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.status', 4)
            ->whereYear('orders.created_at', $year) // Lọc theo năm được truyền vào
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('index', compact('topProducts','total_post', 'chartProductCount', 'fromDate', 'toDate', 'pendingorder', 'completeorder', 'today', 'totalPaid', 'chartData', 'today_paid', 'chartOrderCount', 'chartLabels', 'orders', 'total_protuct', 'completeOrders'));
    }


}
