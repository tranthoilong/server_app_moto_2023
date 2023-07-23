<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Models\Banner;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $data = Banner::select(['id', 'name', 'image', 'status'])->get();
            $data->transform(function ($banner) {
                $banner->image = Storage::url($banner->image);
                return $banner;
            });
            return response()->json([
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return $th;
        }
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
    public function store(StoreBannerRequest $request)
    {
        //
        try {
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            Banner::create([
                'name' => $request->name,
                // 'note' => $request->note,
                'image' => $imageName,
            ]);
            Storage::disk('public')->put($imageName, file_get_contents($request->image));
            return response()->json([
                'message' => 'create successful'
            ], 200);
        } catch (\Throwable $th) {
            // return response()->json([
            //     'message' => 'Something went really wrong !'
            // ], 500);
            return $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        //
    }
}
