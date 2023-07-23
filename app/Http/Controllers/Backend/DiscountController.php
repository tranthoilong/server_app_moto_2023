<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Discount;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class DiscountController extends Controller
{
    public function AllDiscount(){
        $discount = Discount::latest()->get();
        return view('backend.discount.all_discount',compact('discount'));
    }

    public function OrderUseDiscount(Request $request)
    {
        $discountCode = $request->input('discount_code');

        $orders = Order::where('status', 4)
            ->when($discountCode, function ($query) use ($discountCode) {
                return $query->where('discount_code', $discountCode);
            })
            ->whereNotNull('discount_code')
            ->get();

        return view('backend.discount.order_use_discount', compact('orders'));
    }


    public function AddDiscount(){
        return view('backend.discount.add_discount');
    } // End Method


    public function StoreDiscount(Request $request){

        $validateData = $request->validate([
            'discount_code' => 'required|max:200',
            'minimum_amount' => 'required',
            'discount_percent' => 'required',
            'entry_deadline' => 'required',
            'limited_entry' => 'required',
            'short_description' => 'required',
            'status' => 'required',
        ]);



        Discount::insert([
            'discount_code' => $request->discount_code,
            'discount_percent' => $request->discount_percent,
            'minimum_amount' => $request->minimum_amount,
            'limited_entry' => $request->limited_entry,
            'entry_deadline' => $request->entry_deadline,
            'short_description' => $request->short_description,
            'remaining_entry' => $request->limited_entry,
            'number_entry' => 0,
            'status' => $request->status,
            'created_at' => Carbon::now(),
        ]);


        $notification = array(
            'message' => 'Discount Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.discount')->with($notification);
    } // End Method

    public function EditDiscount($id){

        $discount = Discount::findOrFail($id);
        return view('backend.discount.edit_discount',compact('discount'));

    } // End Method


    public function UpdateDiscount(Request $request){


        $discount_id = $request->id;

        $limitedEntry = (int) $request->limited_entry;
        $re_entry = Discount::where('id',$discount_id)->first();
        $numberEntry = (int) $re_entry->number_entry;
        $remainingEntry = $limitedEntry - $numberEntry;
        $remainingEntryString = (string) $remainingEntry;

        Discount::findOrFail($discount_id)->update([
            'discount_code' => $request->discount_code,
            'discount_percent' => $request->discount_percent,
            'minimum_amount' => $request->minimum_amount,
            'limited_entry' => $request->limited_entry,
            'entry_deadline' => $request->entry_deadline,
            'short_description' => $request->short_description,
            'remaining_entry' => $remainingEntryString,
            'status' => $request->status,
            'created_at' => Carbon::now(),
        ]);


        $notification = array(
                'message' => 'Discount Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.discount')->with($notification);


    } // End Method


    public function DeleteDiscount($id){

        Discount::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Discount Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method
}
