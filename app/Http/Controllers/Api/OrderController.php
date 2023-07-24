<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_details;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function huyDonHang($orderId)
    {
     
        $order = Order::find($orderId);
        if (!$order || $order->status == 2) {
            return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        }
        // if ($order->status == 2) {
        //     return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
        // }
        $lsData = Order_details::where('order_id', $orderId)->get();

        // return response()->json(['message' => $orderDetails], 404);
        try {
            foreach ($lsData as $orderDetail) {
                $product = Product::find($orderDetail->product_id);

                if ($product) {
                    // return response()->json(['message' => $product], 200);
                  
                    $product->number += $orderDetail->quantity;
                    $product->like--;
                    $product->save();
                }
            }
            if ($order->payment == 2) {
                $user = User::find($order->user_id);
                if ($user) {
                    $user->score += $order->total_price;
                    $user->save();
                }
            }
            $order->status = 2;
            $order->save();
            return response()->json(['message' => 'Đã hủy đơn hàng và trả lại số lượng sản phẩm'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Đã xảy ra lỗi trong quá trình hủy đơn hàng'], 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $perPage = $request->per_page ?? 10;
            $status = $request->status;

            $query = Order::with(['user' => function ($query) {
                $query->select('id', 'name', 'image', 'gender', 'email', 'phone', 'address');
            }])
                ->where('user_id', auth()->user()->id)
                ->orderBy('created_at', 'desc');

            if ($status !== null) { // Check if status parameter is provided
                $query->where('status', $status);
            }

            $userOrders = $query->paginate($perPage);

           
            $userOrders->getCollection()->transform(function ($order) {
                $orderDetails = Order_details::where('order_id', $order->id)->get();

              
                $orderDetails->transform(function ($orderDetail) {
                    $product = Product::findOrFail($orderDetail->product_id);
                    $orderDetail->product = $product;
                    return $orderDetail;
                });

                $order->product = $orderDetails;
                return $order;
            });

            $currentPageItemCount = count($userOrders->items());
            $totalItemCount = $userOrders->total();
            $data = [
                'status' => 200,
                'data' => $userOrders->items(),
                'current_page' => $userOrders->currentPage(),
                'last_page' => $userOrders->lastPage(),
                'per_page' => $perPage,
                'total_items' => $totalItemCount,
            ];

            return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return $th;
        }
    }


    public function getOrdersByStatus($status)
    {
        $orderDetails = Order_details::with('order', 'order.product')
            ->whereHas('order', function ($query) use ($status) {
                $query->where('status', $status)
                    ->where('user_id', auth()->user()->id);
            })
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => $orderDetails->items(),
            'current_page' => $orderDetails->currentPage(),
            'last_page' => $orderDetails->lastPage(),
            'per_page' => $orderDetails->perPage(),
            'total_items' => $orderDetails->total(),
        ]);
    }

    public function getOrderCountByStatus()
    {
        // Khởi tạo mảng trạng thái mặc định
        $defaultStatus = [0, 1, 2, 3, 4, 5];

      
        $statusCounts = Order::where('user_id', auth()->user()->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

      
        foreach ($defaultStatus as $status) {
            if (!array_key_exists($status, $statusCounts)) {
                $statusCounts[$status] = 0;
            }
        }

        // Định dạng lại kết quả
        $formattedCounts = [];
        foreach ($statusCounts as $status => $count) {
            $formattedCounts["status_$status"] = $count;
        }

        return response()->json(['status' => 200, 'data' => $formattedCounts]);
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
        try {
            $order = new Order();
            $order->user_id = auth()->user()->id;
            $order->save();

            return response()->json(['message' => 'Order created successfully'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
