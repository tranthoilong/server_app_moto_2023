<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $data = Category::select(['id', 'name', 'image', 'status'])->get();
            $data->transform(function ($category) {
                $category->image = Storage::url("category/{$category->image}");
                return $category;
            });
            return response()->json([
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            // return response()->json([
            //     'message' => $th
            // ], 500);
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
    public function store(StoreCategoryRequest $request)
    {
        try {
            $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
            $path = public_path('storage/category');
            Storage::disk('public')->put("/category/{$imageName}", file_get_contents($request->image));

            Category::create([
                'name' => $request->name,
                'image' => $imageName,
            ]);
            return response()->json([
                'message' => 'create successful'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went really wrong !'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $data = Category::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        return response()->json([
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $data = Category::find($id);
            if (!$data) {
                return response()->json([
                    'message' => 'Category not found'
                ], 404);
            }
            $data->fill([
                'name' => $request->name,
            ]);
            if ($request->hasFile('image')) {
                $storege = Storage::disk('public');

                if ($storege->exists($data->image)) {
                    $storege->delete($data->image);
                }
                $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
                $storege->put("/category/{$imageName}", file_get_contents($request->image));
                $data->image = $imageName;
            }
            $data->save();
            return response()->json(['message' => 'Update successful']);
        } catch (\Throwable $th) {
            return $th;
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
