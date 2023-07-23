<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use Gloudemans\Shoppingcart\Facades\Cart;


class PosController extends Controller
{
    public function Pos(Request $request){
        $product = Product::latest()->get();
        $customer = User::latest()->get();
        return view('backend.pos.pos_page',compact('product','customer'));

    } // End Method


    public function AddCart_Barcode(Request $request){

        $product = Product::where('id',$request->query('barcode'))->first();

        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'weight' => 20,
            'options' => ['size' => 'large']]);


        $notification = array(
            'message' => 'Product Added Successfully',
            'alert-type' => 'success'
        );

        return \redirect()->back()->with($notification);
    }

    public function AddCart(Request $request){

        Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'qty' => $request->qty,
            'price' => $request->price,
            'weight' => 20,
            'options' => ['size' => 'large']]);


        $response = [
            'message' => 'Product Added Successfully',
            'alert-type' => 'success'
        ];

        return response()->json($response);


    } // End Method


    public function AllItem(){

        $product_item = Cart::content();

        return view('backend.pos.text_item',compact('product_item'));

    } // End Method


    public function CartUpdate(Request $request,$rowId){

        $qty = $request->qty;
        $update = Cart::update($rowId,$qty);

         $notification = array(
            'message' => 'Cart Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method

    public function DiscountCart(Request $request) {
        $discount_code = Discount::where('discount_code', $request->discount_code)
            ->whereDate('entry_deadline', '>=', now()) // Thêm điều kiện ngày hết hạn >= ngày hiện tại
            ->first();

        if (!empty($discount_code)) {

            Cart::setGlobalDiscount((int) $discount_code->discount_percent);
            session()->put('discount', $discount_code->discount_code);
        }

        return redirect()->back();
    }


    public function CartRemove($rowId){

        Cart::remove($rowId);

        $notification = array(
            'message' => 'Cart Remove Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method


    public function CreateInvoice(Request $request){

         $contents = Cart::content();
         $cust_id = $request->customer_id;
         $customer = User::where('id',$cust_id)->first();
         return view('backend.invoice.product_invoice',compact('contents','customer'));

    } // End Method




}
