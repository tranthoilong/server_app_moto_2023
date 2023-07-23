<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class SupplierController extends Controller
{
    public function AllSupplier(){

        $supplier = Supplier::latest()->get();
        return view('backend.supplier.all_supplier',compact('supplier'));

    } // End Method


    public function AddSupplier(){
         return view('backend.supplier.add_supplier');
    } // End Method



     public function StoreSupplier(Request $request){

        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:customers|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'image' => 'required',
        ]);

         // Lấy tệp tin ảnh từ request
         $file = $request->file('image');

         // Tạo tên tệp tin duy nhất
         $filename = $file->getClientOriginalName();

         // Lưu tệp tin vào thư mục lưu trữ
         $file->storeAs('public/supplier', $filename);

        Supplier::insert([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'city' => $request->city,
            'image' => $filename,
            'created_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Supplier Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.supplier')->with($notification);
    } // End Method


 public function EditSupplier($id){

        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.edit_supplier',compact('supplier'));

    } // End Method


     public function UpdateSupplier(Request $request){

        $supplier_id = $request->id;
        $supplier = Supplier::find($supplier_id);
        if ($request->file('image')) {

            $file = $request->file('image');
            Storage::delete('public/supplier/'. $supplier->image);
            $filename = $file->getClientOriginalName();

            $file->storeAs('public/supllier', $filename);


        Supplier::findOrFail($supplier_id)->update([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'city' => $request->city,
            'image' => $filename,
            'created_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Supplier Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.supplier')->with($notification);

        } else{

            Supplier::findOrFail($supplier_id)->update([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'shopname' => $request->shopname,
            'type' => $request->type,
            'account_holder' => $request->account_holder,
            'account_number' => $request->account_number,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'city' => $request->city,
            'created_at' => Carbon::now(),

        ]);

         $notification = array(
            'message' => 'Supplier Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.supplier')->with($notification);

        } // End else Condition


    } // End Method



 public function DeleteSupplier($id){

        $supplier_img = Supplier::findOrFail($id);
        $img = $supplier_img->image;
        unlink($img);

        Supplier::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Supplier Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method

 public function DetailsSupplier($id){

        $supplier = Supplier::findOrFail($id);
        return view('backend.supplier.details_supplier',compact('supplier'));

    } // End Method

}
