<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery_address;
use App\Models\Order;
use App\Models\Order_details;
use App\Models\Product;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function getOrderDetails(Request $request)
    {
        $perPage = $request->per_page ?? 10;
        $searchOrderId = $request->input('order_id');

        $query = Order_details::where('user_id', auth()->user()->id);

        if ($searchOrderId) {
            $query->where('order_id', $searchOrderId);
        }

        $userOrderDetails = $query->orderBy('created_at', 'desc')->paginate($perPage);

       
        $userOrderDetails->getCollection()->transform(function ($orderDetail) {
            $product = Product::findOrFail($orderDetail->product_id);
            $orderDetail->product = $product;
            $orderDetail->product_name = $product->name;
            return $orderDetail;
        });


        $userOrderDetails->getCollection()->transform(function ($orderDetail) {
            $order = Order::findOrFail($orderDetail->order_id);
            $user = User::findOrFail($order->user_id);
            $orderDetail->user = $user;
            return $orderDetail;
        });

        $currentPageItemCount = count($userOrderDetails->items());
        $totalItemCount = $userOrderDetails->total();
        $data = [
            'status' => 200,
            'data' => $userOrderDetails->items(),
            'current_page' => $userOrderDetails->currentPage(),
            'last_page' => $userOrderDetails->lastPage(),
            'per_page' => $perPage,
            'total_items' => $totalItemCount,
        ];

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }





    public function getOrderProducts(Request $request, $order_id)
    {
        $order = Order::with('order_details')->findOrFail($order_id);

        $productIds = $order->order_details->pluck('product_id');

        $products = Product::whereIn('id', $productIds)
            ->select('id', 'name', 'image', 'number', 'price')
            ->get();

        $products->transform(function ($product) {
            $product->image = Storage::url('product/' . $product->image);
            return $product;
        });

        $orderProducts = [];
        foreach ($order->order_details as $orderDetail) {
            $product = $products->where('id', $orderDetail->product_id)->first();

            $orderProduct = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'number' => $product->number,
                'price' => $product->price,

                'quantity' => $orderDetail->quantity,
            ];

            $orderProducts[] = $orderProduct;
        }

        $response = [
            "status" => 200,
            'data' => $orderProducts,
        ];

        return response()->json($response);
    }



    public function store(Request $request)
    {
        $requestData = $request->json()->all();
        $data['invoice_no'] = 'SPOS' . mt_rand(10000000, 99999999);
        // $customer_id = Customer::find(auth()->user()->id);
        try {
            DB::beginTransaction();

            $order = new Order();
            $order->id = Order::generateId();
            $order->user_id = auth()->user()->id;
            $order->address = $request->input('address');
            $order->name = $request->input('name');
            $order->note = $request->input('note');
            $order->booking_date = $request->input('date_order');
            $order->delivery_date = $request->input('delivery_date');
            $order->payment = $request->input('payment');
            $order->ship = $request->input('ship');
            $order->phone = $request->input('phone');

            $order->total_products = 1;
            // $order->invoice_no = $data['invoice_no'];

            $order->save();
           
            $orderDetails = $requestData['order_details'];
            $invalidQuantityProducts = [];
            $totalPrice = 0; 

            foreach ($orderDetails as $orderDetailData) {
                if (isset($orderDetailData['product_id']) && isset($orderDetailData['quantity'])) {
                    $product = Product::findOrFail($orderDetailData['product_id']);
                    $orderDetailQuantity = $orderDetailData['quantity'];

                    if ($orderDetailQuantity > $product->number) {
                 
                        $invalidQuantityProducts[] = $product->name;
                    } else {
                        $orderDetail = new Order_details();
                        $orderDetail->user_id = auth()->user()->id;
                        $orderDetail->order_id = $order->id;
                        $orderDetail->product_id = $orderDetailData['product_id'];
                        $orderDetail->quantity = $orderDetailQuantity;
                        $orderDetail->price = $orderDetailData['price'];

                   
                        $orderDetail->status = 1;
                        $orderDetail->save();

                       
                        $product->number -= $orderDetailQuantity;
                        $product->like += $orderDetailQuantity;
                        $product->save();

                    
                        $productPrice =   $orderDetail->price * $orderDetailQuantity;
                        $totalPrice += $productPrice;
                        $totalPrice += $order->ship;
                        // if ($deliveryAddress) {
                        //     $totalPrice += $deliveryAddress->ship;
                        // }
                    }
                }
            }

            if (!empty($invalidQuantityProducts)) {
      
                DB::rollBack();

                $message = 'Số lượng không hợp lệ cho các sản phẩm sau: ' . implode(', ', $invalidQuantityProducts);
                return response()->json(['message' => $message], 400);
            }

         
            $order->total_price = $totalPrice;
            $order->save();

            DB::commit(); 

            return response()->json(['message' => 'Đơn hàng đã được lưu'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); 

          
            return response()->json(['message' => 'Đã xảy ra lỗi trong quá trình lưu đơn hàng'], 500);
        }
    }
}
