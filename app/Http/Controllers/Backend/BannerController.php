<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Customer;
use App\Models\Post;
use Carbon\Carbon;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    public function AllBanner(){
        $banner = Banner::latest()->get();
        return view('backend.banner.all_banner',compact('banner'));
    }

    public function AddBanner(){
        $slugs = Post::latest()->get();
        return view('backend.banner.add_banner',compact('slugs'));
    } // End Method


    public function StoreBanner(Request $request){


        $validateData = $request->validate([
            'name' => 'required|max:200',
            'image' => 'required',
            'slug' => 'required',
            'status' => 'required',
        ]);


        $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/banner', $filename);


        Banner::insert([
            'name' => $request->name,
            'image' => $filename,
            'slug' => $request->slug,
            'status' => $request->status,
            'created_at' => Carbon::now(),
        ]);


        $notification = array(
            'message' => 'Banner Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.banner')->with($notification);
    } // End Method

    public function EditBanner($id){
        $slugs = Post::latest()->get();
        $banner = Banner::findOrFail($id);
        return view('backend.banner.edit_banner',compact('banner','slugs'));

    } // End Method


    public function UpdateBanner(Request $request){


        $banner_id = $request->id;
        $banner = Banner::find($banner_id);

        if ($request->file('image')) {

            $file = $request->file('image');
            Storage::delete('public/banner/'.$banner->image);
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/banner', $filename);

            Banner::findOrFail($banner_id)->update([

                'name' => $request->name,
                'image' => $filename,
                'slug' => $request->slug,
                'status' => $request->status,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Customer Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);

        } else{

            Banner::findOrFail($banner_id)->update([

                'banner_name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
                'created_at' => Carbon::now(),

            ]);


            $notification = array(
                'message' => 'Banner Updated Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);

        } // End else Condition


    } // End Method


    public function DeleteBanner($id){

        $banner_img = Banner::findOrFail($id);
        $img = $banner_img->images;
        unlink($img);

        Banner::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // End Method
}
