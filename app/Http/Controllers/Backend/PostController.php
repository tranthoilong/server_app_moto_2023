<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function AllPost() {
        $post = Post::latest()->get();
        return view('backend.post.all_post',compact('post'));
    }

    public function AddPost() {
        return view('backend.post.add_post');
    }

    public function ImageUpload(Request $request) {
        if($request->hasFile('upload'))
        {
            $originName=$request->file('upload')->getClientOriginalName();
            $fileName=pathinfo($originName, PATHINFO_FILENAME);
            $extension=$request->file('upload')->getClientOriginalExtension();
            $fileName=$fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('/upload/post/content/'), $fileName);
            $url=asset('/upload/post/content/' . $fileName);
            return response()->json(['fileName'=>$fileName, 'uploaded'=>1, 'url'=>$url]);
        }
    }

    public function StorePost(Request $request) {

        $validateData = $request->validate([
            'title' => 'required|max:200',
            'short_description' => 'required|max:200',
            'content' => 'required',
            'image' => 'required',
            'slug' => 'required',
            'status' => 'required',
        ]);
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/post/'.$name_gen);
        $save_url = 'upload/post/'.$name_gen;

        Post::insert([
            'title' => $request->title,
            'short_description' => $request->short_description,
            'content' => $request->content,
            'image' => $save_url,
            'slug' => $request->slug,
            'status' => $request->status,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Post Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.post')->with($notification);
    }

    public function EditPost($id) {
        $post = Post::findOrFail($id);
        return view('backend.post.edit_post',compact('post'));
    }

    public function UpdatePost(Request $request) {
        $post_id = $request->id;

        if($request->file('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/post/'.$name_gen);
            $save_url = 'upload/post/'.$name_gen;

            Post::findOrFail($post_id)->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'content' => $request->content,
                'image' => $save_url,
                'slug' => $request->slug,
                'status' => $request->status,
                'created_at' => Carbon::now(),
            ]);
        } else {
            Post::findOrFail($post_id)->update([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'content' => $request->content,
                'slug' => $request->slug,
                'status' => $request->status,
                'created_at' => Carbon::now(),
            ]);
        }

        $notification = array(
            'message' => 'Post Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.post')->with($notification);
    }

    public function DeletePost($id) {
        $post_img = Post::findOrFail($id);
        $img = $post_img->image;
        unlink($img);

        Post::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Product Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
