<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Order_details;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\MailController;

class OrderController extends Controller
{

    public function order_browsing($order_id)
    {
        $order = Order::find($order_id);

        $order->status = 3;
        $order->save();
        $notification = array(
            'message' => 'Duyệt hóa đơn thành công!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function order_check($order_id)
    {
        $order = Order::find($order_id);

        $order->status = 4;
        $order->save();
        $notification = array(
            'message' => 'Thành công!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function order_cancel($order_id) {
        $order = Order::find($order_id);

        $order->status = 2;
        $order->save();
        $notification = array(
            'message' => 'Hủy thành công!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function order_stripe_complete(Request $request)
    {

        $discount = Discount::where('discount_code', session('discount'))->first();
        if ($discount != null) {
            $re_entry = str_replace(',', '', $discount->remaining_entry);
            $num_entry = str_replace(',', '', $discount->number_entry);
            $re_entry = floatval($re_entry - 1);
            $num_entry = floatval($num_entry + 1);

            $discount->remaining_entry = $re_entry;
            $discount->number_entry = $num_entry;
            $discount->update();
        }


        $order = Order::where('id', $request->query('orderId'))->first();
        $order->status = 4;
        $order->save();

        $customer = $order;
        $contents = Cart::content();
        $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
        $tax = floatval(str_replace(',', '', Cart::tax()));
        $discount = floatval(str_replace(',', '', Cart::discount()));
        $total = floatval(str_replace(',', '', Cart::total()));

        $data = [
            'sub_total' => $subtotal,
            'vat' => $tax,
            'discount' => $discount,
            'total' => $total
        ];

        Cart::destroy();


        $notification = array(
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        );

        session()->forget('discount');

        $mailController = new MailController();
        $mailController->index();

        return view('backend.order.order_invoice', compact('contents', 'order', 'customer', 'data'));
    }
    public function order_momo_complete(Request $request)
    {
        $discount = Discount::where('discount_code', session('discount'))->first();
        if ($discount != null) {
            $re_entry = str_replace(',', '', $discount->remaining_entry);
            $num_entry = str_replace(',', '', $discount->number_entry);
            $re_entry = floatval($re_entry - 1);
            $num_entry = floatval($num_entry + 1);

            $discount->remaining_entry = $re_entry;
            $discount->number_entry = $num_entry;
            $discount->update();
        }


        $order = Order::where('id', $request->query('orderId'))->first();
        $order->status = 4;
        $order->save();

        $customer = $order;
        $contents = Cart::content();
        $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
        $tax = floatval(str_replace(',', '', Cart::tax()));
        $discount = floatval(str_replace(',', '', Cart::discount()));
        $total = floatval(str_replace(',', '', Cart::total()));

        Cart::destroy();

        $data = [
            'sub_total' => $subtotal,
            'vat' => $tax,
            'discount' => $discount,
            'total' => $total
        ];

        $notification = array(
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        );

        $mailController = new MailController();
        $mailController->index();

        return view('backend.order.order_invoice', compact('contents', 'order', 'customer', 'data'))->with($notification);
    }

    public function order_vnpay_complete(Request $request)
    {
        $discount = Discount::where('discount_code', session('discount'))->first();
        if ($discount != null) {
            $re_entry = str_replace(',', '', $discount->remaining_entry);
            $num_entry = str_replace(',', '', $discount->number_entry);
            $re_entry = floatval($re_entry - 1);
            $num_entry = floatval($num_entry + 1);

            $discount->remaining_entry = $re_entry;
            $discount->number_entry = $num_entry;
            $discount->update();
        }


        $order = Order::where('invoice_no', $request->query('orderId'))->first();
        $order->status = 3;
        $order->save();

        $customer = Customer::find($order->customer_id);
        $contents = Cart::content();
        $subtotal = floatval(str_replace(',', '', Cart::subtotal()));
        $tax = floatval(str_replace(',', '', Cart::tax()));
        $discount = floatval(str_replace(',', '', Cart::discount()));
        $total = floatval(str_replace(',', '', Cart::total()));

        $data = [
            'sub_total' => $subtotal,
            'vat' => $tax,
            'discount' => $discount,
            'total' => $total
        ];

        Cart::destroy();

        $notification = array(
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        );

        $mailController = new MailController();
        $mailController->index();

        return view('backend.order.order_invoice', compact('contents', 'order', 'customer', 'data'));
    }

    public function FinalInvoice(Request $request)
    {

        $rtotal = $request->total;
        $rpay = $request->pay;
        $mtotal = $rtotal - $rpay;

        $data = array();
        $data['customer_id'] = $request->customer_id;
        $data['order_date'] = $request->order_date;
        $data['order_status'] = $request->order_status;
        $data['total_products'] = $request->total_products;
        $data['sub_total'] = $request->sub_total;
        $data['vat'] = $request->vat;

        $data['invoice_no'] = $request->order_id;
        $data['total'] = $request->total;
        $data['payment_status'] = $request->payment_status;
        $data['pay'] = $request->pay;
        $data['due'] = $mtotal;
        $data['created_at'] = Carbon::now();

        $order_id = Order::insertGetId($data);
        $contents = Cart::content();

        $pdata = array();
        foreach ($contents as $content) {
            $pdata['order_id'] = $order_id;
            $pdata['product_id'] = $content->id;
            $pdata['quantity'] = $content->qty;
            $pdata['unitcost'] = $content->price;
            $pdata['total'] = $content->total;

            $insert = Order_details::insert($pdata);
        } // end foreach


        $notification = array(
            'message' => 'Order Complete Successfully',
            'alert-type' => 'success'
        );

        Cart::destroy();

        $order = Order::where('invoice_no', $request->order_id)->first();
        $customer = Customer::find($order->customer_id);

        return view('backend.order.order_invoice', compact('contents', 'order', 'customer', 'data'))->with($notification);
    } // End Method


    public function PendingOrder()
    {

        $orders = Order::where('order_status', 'pending')->get();
        return view('backend.order.pending_order', compact('orders'));
    } // End Method

    public function CompleteOrder()
    {

        $orders = Order::latest()->get();
        return view('backend.order.complete_order', compact('orders'));
    } // End Method


    public function OrderDetails($order_id)
    {
        $order = Order::where('id', $order_id)->first();

        $orderItem = Order_details::with('product')->where('order_id', $order_id)->orderBy('id', 'DESC')->get();
        return view('backend.order.order_details', compact('order', 'orderItem'));
    } // End Method


    public function OrderStatusUpdate(Request $request)
    {

        $order_id = $request->id;


        $product = Order_details::where('order_id', $order_id)->get();
        foreach ($product as $item) {
            Product::where('id', $item->product_id)
                ->update(['product_store' => DB::raw('product_store-' . $item->quantity)]);
        }

        Order::findOrFail($order_id)->update(['order_status' => 'complete']);

        $notification = array(
            'message' => 'Order Done Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pending.order')->with($notification);
    } // End Method


    public function StockManage()
    {

        $product = Product::latest()->get();
        return view('backend.stock.all_stock', compact('product'));
    } // End Method


    public function OrderInvoice($order_id)
    {

        $order = Order::where('id', $order_id)->first();
        $customer = User::find($order->user_id);

        $contents = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('order_details.*', 'products.name as product_name', 'products.price as product_price')
            ->where('order_details.order_id', $order_id)
            ->orderBy('order_details.id', 'DESC')
            ->get();

        $data = [
            'sub_total' => $order->sub_total,
            'vat' => $order->vat,
            'discount' => $order->discount,
            'total' => $order->total_price
        ];

        return view('backend.order.order_invoice', compact('contents', 'order', 'customer', 'data'));
    } // End Method


    public function PendingDue()
    {

        $alldue = Order::where('due', '>', '0')->orderBy('id', 'DESC')->get();
        return view('backend.order.pending_due', compact('alldue'));
    } // End Method


    public function OrderDueAjax($id)
    {

        $order = Order::findOrFail($id);
        return response()->json($order);
    } // End Method


    public function UpdateDue(Request $request)
    {

        $order_id = $request->id;
        $due_amount = $request->due;
        $pay_amount = $request->pay;

        $allorder = Order::findOrFail($order_id);
        $maindue = $allorder->due;
        $maindpay = $allorder->pay;

        $paid_due = $maindue - $due_amount;
        $paid_pay = $maindpay + $due_amount;

        Order::findOrFail($order_id)->update([
            'due' => $paid_due,
            'pay' => $paid_pay,
        ]);

        $notification = array(
            'message' => 'Due Amount Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('pending.due')->with($notification);
    } // End Method


}
