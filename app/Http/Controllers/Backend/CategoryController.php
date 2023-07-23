<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function AllCategory(){

        $category = Category::latest()->get();
        return view('backend.category.all_category',compact('category'));

    }// End Method


    public function StoreCategory(Request $request){

        // Lấy tệp tin ảnh từ request
        $file = $request->file('photo');

        // Tạo tên tệp tin duy nhất
        $filename = $file->getClientOriginalName();

        // Lưu tệp tin vào thư mục lưu trữ
        $file->storeAs('public/category', $filename);

        // Insert dữ liệu và tên tệp tin vào CSDL
        Category::insert([
            'name' => $request->category_name,
            'image' => $filename,
            'created_at' => Carbon::now(),
        ]);

         $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);
    }// End Method


    public function EditCategory($id){
        $category = Category::findOrFail($id);
        return view('backend.category.edit_category',compact('category'));

    }// End Method


    public function UpdateCategory(Request $request){

        $category_id = $request->id;
        $category = Category::find($category_id);
        if($request->hasFile('category_image')) {
            $file = $request->file('category_image');
            Storage::delete('public/category/'. $category->image);
            $filename = $file->getClientOriginalName();

            $file->storeAs('public/category', $filename);

            Category::findOrFail($category_id)->update([
                'name' => $request->category_name,
                'image' => $filename,
                'created_at' => Carbon::now(),
            ]);
        } else {
            Category::findOrFail($category_id)->update([
                'name' => $request->category_name,
                'created_at' => Carbon::now(),
            ]);
        }



         $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);

    }// End Method


    public function DeleteCategory($id){

        Category::findOrFail($id)->delete();

         $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


    }// End Method


}
