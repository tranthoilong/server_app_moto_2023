<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getData(Request $request)
    {
        try {
            $products = Product::select('id', 'category_id', 'name', 'image', 'description', 'number', 'price', 'like', 'status')
                ->when($request->has('category_id'), function ($query) use ($request) {
                    $categoryId = $request->input('category_id');
                    if ($categoryId != 1) {
                        $query->where('category_id', $categoryId);
                    }
                })
                ->when($request->has('search'), function ($query) use ($request) {
                    $search = $request->input('search');
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('price', 'like', "%{$search}%");
                    });
                })
                ->when($request->has('min_price'), function ($query) use ($request) {
                    $minPrice = $request->input('min_price');
                    $query->where('price', '>=', $minPrice);
                })
                ->when($request->has('max_price'), function ($query) use ($request) {
                    $maxPrice = $request->input('max_price');
                    $query->where('price', '<=', $maxPrice);
                })
                // ->orderBy('like', 'desc')
                // ->orderByRaw('`number` = 0 asc, `like` desc')
                ->when($request->has('tag'), function ($query) use ($request) {
                    $tag = $request->input('tag');
                    if ($tag == 0) {
                        $query->orderBy('price', 'desc'); //Gia cao den thap
                    } else if ($tag == 1) {
                        $query->orderBy('price', 'asc'); //Gia thap den cao
                    } else if ($tag == 2) {
                        $query->orderByRaw('`number` = 0 asc, `like` desc');
                        // $query->orderBy('like', 'desc'); //Sp yêu thích nhiều nhất
                    } else if ($tag == 3) {
                        $query->orderByRaw('`price` asc, `like` desc'); //Sp yêu thích nhiều nhất và rẻ nhất
                    } else {
                        $query->orderByRaw('`number` = 0 asc, `like` desc');
                    }
                })
                ->with(['category', 'favorites' => function ($query) {
                    $query->where('user_id', auth()->user()->id);
                }])
                ->paginate(20);

            $products->getCollection()->transform(function ($product) {
                $product->image = Storage::url('product/' . $product->image);
                $product->love = $product->favorites->isNotEmpty() ? 1 : 0;
                unset($product->favorites);
                return $product;
            });

            return response()->json([
                'data' => $products->items(),
                'total_pages' => $products->lastPage(),
                'current_page' => $products->currentPage(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    // public function index(Request $request)
    // {
    //     try {
    //         $products = Product::select('name', 'image', 'description', 'number', 'price', 'like', 'status')
    //             ->when($request->has('search'), function ($query) use ($request) {
    //                 $search = $request->input('search');
    //                 $query->where('name', 'like', "%{$search}%")
    //                     ->orWhere('price', 'like', "%{$search}%");
    //             })
    //             ->when($request->has('min_price'), function ($query) use ($request) {
    //                 $minPrice = $request->input('min_price');
    //                 $query->where('price', '>=', $minPrice);
    //             })
    //             ->when($request->has('max_price'), function ($query) use ($request) {
    //                 $maxPrice = $request->input('max_price');
    //                 $query->where('price', '<=', $maxPrice);
    //             })
    //             ->orderBy('like', 'desc')
    //             ->paginate(10);


    //         $products->getCollection()->transform(function ($product) {
    //             $product->image = Storage::url($product->image);
    //             return $product;
    //         });

    //         return response()->json([
    //             'data' => $products->items(),
    //             'total_pages' => $products->lastPage(),
    //             'current_page' => $products->currentPage(),
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         return $th;
    //     }
    // }
    public function index(Request $request)
    {
        try {
            $products = Product::select('id', 'category_id', 'name', 'image', 'description', 'number', 'price', 'like', 'status')
                ->when($request->has('category_id'), function ($query) use ($request) {
                    $categoryId = $request->input('category_id');
                    if ($categoryId != 1) {
                        $query->where('category_id', $categoryId);
                    }
                })
                ->when($request->has('search'), function ($query) use ($request) {
                    $search = $request->input('search');
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('price', 'like', "%{$search}%");
                    });
                })
                ->when($request->has('min_price'), function ($query) use ($request) {
                    $minPrice = $request->input('min_price');
                    $query->where('price', '>=', $minPrice);
                })
                ->when($request->has('max_price'), function ($query) use ($request) {
                    $maxPrice = $request->input('max_price');
                    $query->where('price', '<=', $maxPrice);
                })
                // ->orderBy('like', 'desc')
                // ->orderByRaw('`number` = 0 asc, `like` desc')
                ->when($request->has('tag'), function ($query) use ($request) {
                    $tag = $request->input('tag');
                    if ($tag == 0) {
                        $query->orderBy('price', 'desc'); //Gia cao den thap
                    } else if ($tag == 1) {
                        $query->orderBy('price', 'asc'); //Gia thap den cao
                    } else if ($tag == 2) {
                        $query->orderByRaw('`number` = 0 asc, `like` desc');
                        // $query->orderBy('like', 'desc'); //Sp yêu thích nhiều nhất
                    } else if ($tag == 3) {
                        $query->orderByRaw('`price` asc, `like` desc'); //Sp yêu thích nhiều nhất và rẻ nhất
                    } else {
                        $query->orderByRaw('`number` = 0 asc, `like` desc');
                    }
                })
                ->paginate(20);

            $products->getCollection()->transform(function ($product) {
                $product->image = Storage::url('product/' . $product->image);
                return $product;
            });
            return response()->json([
                'data' => $products->items(),
                'total_pages' => $products->lastPage(),
                'current_page' => $products->currentPage(),
            ], 200);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function search(Request $request)
    {
        try {
            $products = Product::select('id', 'category_id', 'name', 'image', 'description', 'number', 'price', 'like', 'status')
                ->when($request->has('category_id'), function ($query) use ($request) {
                    $categoryId = $request->input('category_id');
                    if ($categoryId != 1) {
                        $query->where('category_id', $categoryId);
                    }
                })
                ->when($request->has('search'), function ($query) use ($request) {
                    $search = $request->input('search');
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%")
                            ->orWhere('price', 'like', "%{$search}%");
                    });
                })
                ->when($request->has('min_price'), function ($query) use ($request) {
                    $minPrice = $request->input('min_price');
                    $query->where('price', '>=', $minPrice);
                })
                ->when($request->has('max_price'), function ($query) use ($request) {
                    $maxPrice = $request->input('max_price');
                    $query->where('price', '<=', $maxPrice);
                })
                ->when($request->has('tag'), function ($query) use ($request) {
                    $tag = $request->input('tag');
                    if ($tag == 0) {
                        $query->orderBy('price', 'desc'); //Gia cao den thap
                    } else if ($tag == 1) {
                        $query->orderBy('price', 'asc'); //Gia thap den cao
                    } else if ($tag == 2) {
                        $query->orderByRaw('`number` = 0 asc, `like` desc');
                        // $query->orderBy('like', 'desc'); //Sp yêu thích nhiều nhất
                    } else if ($tag == 3) {
                        $query->orderByRaw('`price` asc, `like` desc'); //Sp yêu thích nhiều nhất và rẻ nhất
                    } else {
                        $query->orderByRaw('`number` = 0 asc, `like` desc');
                    }
                })
                ->get();

            $products->transform(function ($product) {
                $product->image = Storage::url('product/' . $product->image);
                return $product;
            });

            return response()->json([
                'data' => $products,
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
    // public function store(StoreProductRequest $request)
    // {
    //     //
    //     try {
    //         $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
    //         Product::create([
    //             'name' => $request->name,
    //             'image' => $imageName,
    //             'description' => $request->description, 'number' => $request->number,
    //             'price' => $request->price,
    //             'like' => $request->like
    //         ]);
    //         Storage::disk('public')->put($imageName, file_get_contents($request->image));
    //         return response()->json(['message' => 'create successful'], 200);
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return response()->json([
    //             'message' => "Something went really wrong!"
    //         ], 500);
    //     }
    // }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $product = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.id', 'products.category_id', 'categories.name as category_name', 'products.name', 'products.image', 'products.description', 'products.number', 'products.price', 'products.like', 'products.status')
                ->where('products.id', $id)
                ->first();

            if (!$product) {
                return response()->json([
                    'message' => 'Product not found'
                ], 404);
            }

            $product->image = Storage::url('product/' . $product->image);

            return response()->json([
                'data' => $product
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong! $th"
            ], 500);
        }
    }
    public function showDataById(Request $request)
    {
        try {
            $productIds = $request->input('product_ids');

            $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.id', 'products.category_id', 'categories.name as category_name', 'products.name', 'products.image', 'products.description', 'products.number', 'products.price', 'products.like', 'products.status')
                ->whereIn('products.id', $productIds)
                ->get();

            if ($products->isEmpty()) {
                return response()->json([
                    'message' => 'No products found'
                ], 404);
            }

            $products->transform(function ($product) {
                $product->image = Storage::url('product/' . $product->image);
                return $product;
            });

            return response()->json([
                'data' => $products
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went wrong! $th"
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
