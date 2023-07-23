<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Order_details;
use App\Models\Orderdetails;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function stripe_checkout(Request $request) {

        $rtotal = $request->total;
        $rpay = $request->pay;
        $mtotal = $rtotal - $rpay;

        $data['user_id'] = $request->customer_id;
        if($request->order_status = 'pending') {
            $data['status'] = 4;
        }
        $data['id'] = Order::generateId();
        $data['total_products'] = $request->total_products;
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;
        $data['address'] = $request->address;
        $data['total_price'] = $request->total;
        $data['payment'] = 2;
        $data['discount_code'] = session('discount');
        $data['discount'] = $request->discount;
        $data['created_at'] = Carbon::now();

        $order_id = Order::insertGetId($data);
        $contents = Cart::content();

        $pdata = array();
        foreach($contents as $content){
            $pdata['order_id'] = $data['id'];
            $pdata['product_id'] = $content->id;
            $pdata['quantity'] = $content->qty;
            $pdata['price'] = $content->total;

            $insert = Order_details::insert($pdata);

        } // end foreach


        $notification = array(
            'message' => 'Order Pending Successfully',
            'alert-type' => 'success'
        );



        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $session = \Stripe\Checkout\Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'vnd',
                        'product_data' => [
                            'name' => 'Thanh toán bằng thẻ thanh toán quốc tế',
                        ],
                        'unit_amount'  => $request->total,
                    ],
                    'quantity'   => 1,
                ],
            ],
            'mode'        => 'payment',
            'success_url' => route('order_stripe_complete', ['orderId' => $data['id'],'amount' => $request->total]),
            'cancel_url'  => route('stripe_checkout'),
        ]);

        return redirect()->away($session->url);
    }

    public function zalo_payment(Request $request) {
        $config = [
            "app_id" => 2553,
            "key1" => "PcY4iZIKFCIdgZvA6ueMcMHHUbRLYjPL",
            "key2" => "kLtgPl8HHhfvMuDHPwKfgfsY4Ydm9eIz",
            "endpoint" => "https://sb-openapi.zalopay.vn/v2/create"
        ];

        $embeddata = '{}'; // Merchant's data
        $items = '[]'; // Merchant's data
        $transID = $_POST["order_id"]; //Random trans id
        $order = [
            "app_id" => $config["app_id"],
            "app_time" => round(microtime(true) * 1000), // miliseconds
            "app_trans_id" => date("ymd") . "_" . $transID, // translation missing: vi.docs.shared.sample_code.comments.app_trans_id
            "app_user" => "user123",
            "item" => $items,
            "embed_data" => $embeddata,
            "amount" => $_POST["total"],
            "description" => "Snik - Payment for the order #$transID",
            "bank_code" => "zalopayapp"
        ];

// appid|app_trans_id|appuser|amount|apptime|embeddata|item
        $data = $order["app_id"] . "|" . $order["app_trans_id"] . "|" . $order["app_user"] . "|" . $order["amount"]
            . "|" . $order["app_time"] . "|" . $order["embed_data"] . "|" . $order["item"];
        $order["mac"] = hash_hmac("sha256", $data, $config["key1"]);

        $context = stream_context_create([
            "http" => [
                "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                "method" => "POST",
                "content" => http_build_query($order)
            ]
        ]);

        $resp = file_get_contents($config["endpoint"], false, $context);
        $result = json_decode($resp, true);

        foreach ($result as $key => $value) {
            echo "$key: $value<br>";
        }

        return redirect()->to($result['order_url']);
    }

    public function momo_qp_payment(Request $request) {
        function execPostRequest($url, $data)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            $jsonResult = json_decode($result, true);
            dd($jsonResult);
            if ($jsonResult['payUrl'] != null)
                header('Location: ' . $jsonResult['payUrl']);
            return $result;
        }

        $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/pos';
        $accessKey = 'F8BBA842ECF85';
        $secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';
        $orderInfo = 'pay with MoMo';
        $partnerCode = 'MOMO';
        $redirectUrl = 'https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b';
        $ipnUrl = 'https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b';
        $amount = '50000';
        $orderId = time() . "";
        $requestId = time() ."";
        $extraData ='';
        $partnerName = 'MoMo Payment';
        $storeId = 'Test Store';
        $paymentCode = 'L/U2a6KeeeBBU/pQAa+g8LilOVzWfvLf/P4XOnAQFmnkrKHICj51qrOTUQ+YrX8/Xs1YD4IOdyiGSkCfV6Je9PeRzl3sO+mDzXNG4enhigU3VGPFh67a37dSwItMJXRDuK64DCqv35YPQtiAOVVZV35/1XBw1rWopmRP03YMNgQWedGLHwmPSkRGoT6XtDSeypJtgbLZ5KIOJsdcynBdFEnHAuIjvo4stADmRL8GqdgsZ0jJCx/oq5JGr8wY+a4g9KolEOSTLBTih48RrGZq3LDBbT4QGBjtW+0W+/95n8W0Aot6kzdG4rWg1NB7EltY6/A8RWAHJav4kWQoFcxgfA==';
        $orderGroupId ='';
        $autoCapture =True;
        $lang = 'vi';

        if (!empty($_POST)) {

            //before sign HMAC SHA256 signature
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&paymentCode=" . $paymentCode . "&requestId=" . $requestId;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);
            $data = array(
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                'storeId' => 'MomoTestStore',
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'autoCapture' => $autoCapture,
                'extraData' => $extraData,
                'paymentCode' => $paymentCode,
                'orderGroupId' => $orderGroupId,
                'signature' => $signature);
            $result = execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);  // decode json
            return redirect()->to($jsonResult['payUrl']);
            //Just a example, please check more in there
        }
    }

    public function momo_qr_payment(Request $request)
    {
        $rtotal = $request->total;
        $rpay = $request->pay;
        $mtotal = $rtotal - $rpay;

        $data['user_id'] = $request->customer_id;
        if($request->order_status = 'pending') {
            $data['status'] = 4;
        }
        $data['id'] = Order::generateId();
        $data['total_products'] = $request->total_products;
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;
        $data['address'] = $request->address;
        $data['total_price'] = $request->total;
        $data['payment'] = 2;
        $data['discount_code'] = session('discount');
        $data['discount'] = $request->discount;
        $data['created_at'] = Carbon::now();

        $order_id = Order::insertGetId($data);
        $contents = Cart::content();

        $pdata = array();
        foreach($contents as $content){
            $pdata['order_id'] = $order_id;
            $pdata['product_id'] = $content->id;
            $pdata['quantity'] = $content->qty;
            $pdata['price'] = $content->total;

            $insert = Order_details::insert($pdata);

        } // end foreach


        $notification = array(
            'message' => 'Order Pending Successfully',
            'alert-type' => 'success'
        );

        $currenturl = url()->current();

        $baseUrl = url('/');

        function execPostRequest($url, $data)
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);
            return $result;
        }

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";


        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $_POST["total"];
        $orderId = $data['id'];
        $redirectUrl = "${baseUrl}/order_momo_complete";
        $ipnUrl = "${baseUrl}/order_momo_complete";
        $extraData = "";


            $requestId = time() . "";
            $requestType = "captureWallet";
            //$extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
            //before sign HMAC SHA256 signature
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);
            $data = array('partnerCode' => $partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature);
            $result = execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);  // decode json
            //Just a example, please check more in there
            return redirect()->to($jsonResult['payUrl']);
        }

    public function vnpay_payment(Request $request) {
        if ($request->customer_id == 1) {
            $customerId = Customer::insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'created_at' => Carbon::now(),
            ]);
            // Sử dụng $customerId để lưu id của khách hàng mới được tạo
        }


        $rtotal = $request->total;
        $rpay = $request->pay;
        $mtotal = $rtotal - $rpay;

        $data = array();
        if ($request->customer_id == 1){
            $data['customer_id'] = $customerId;
        } else {
            $data['customer_id'] = $request->customer_id;
        }
        $data['order_status'] = $request->order_status;
        $data['total_products'] = $request->total_products;
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;

        $data['total'] = $request->total;
        $data['payment_status'] = "Vnpay";
        $data['pay'] = $request->pay;
        $data['discount_code'] = session('discount');
        $data['discount'] = $request->discount;
        $data['due'] = $mtotal;
        $data['created_at'] = Carbon::now();

        $order_id = Order::insertGetId($data);
        $contents = Cart::content();

        $pdata = array();
        foreach($contents as $content){
            $pdata['order_id'] = $order_id;
            $pdata['product_id'] = $content->id;
            $pdata['quantity'] = $content->qty;
            $pdata['unitcost'] = $content->price;
            $pdata['total'] = $content->total;

            $insert = Orderdetails::insert($pdata);

        } // end foreach


        Cart::destroy();

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/order_vnpay_complete";
        $vnp_TmnCode = "UZG319A9";//Mã website tại VNPAY
        $vnp_HashSecret = "CSYUQDSGAIQVPKAIQFNYFTRQZILTQLBY"; //Chuỗi bí mật

        $vnp_TxnRef = $_POST['order_id']; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $_POST["total"] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
//Add Params of 2.0.1 Version
        //$vnp_ExpireDate = $_POST['txtexpire'];
//Billing
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
            //"vnp_ExpireDate"=>$vnp_ExpireDate

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

//var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array('code' => '00'
        , 'message' => 'success'
        , 'data' => $vnp_Url);
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
        // vui lòng tham khảo thêm tại code demo
    }
}
