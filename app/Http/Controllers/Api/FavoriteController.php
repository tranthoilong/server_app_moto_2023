<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::join('products', 'favorites.product_id', '=', 'products.id')
            ->where('favorites.user_id', auth()->user()->id)
            ->select('favorites.id', 'favorites.user_id', 'favorites.product_id', 'products.name', 'products.price', 'products.image', 'products.like')
            ->orderBy('favorites.created_at', 'desc')
            ->paginate(20);

        $favorites->getCollection()->transform(function ($favorite) {
            $favorite->image = Storage::url('product/'.$favorite->image);
            return $favorite;
        });

        return response()->json([
            'data' => $favorites->items(),
            'total_pages' => $favorites->lastPage(),
            'current_page' => $favorites->currentPage()
        ], 200);
    }


    public function store(Request $request)
    {
       
        $favorite = Favorite::where('user_id', auth()->user()->id)
            ->where('product_id', $request->product_id)
            ->first();

      
        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 201, 'message' => 'Product is already in favorites'], 400);
        }

     
        $favorite = Favorite::create([
            'user_id' => auth()->user()->id,
            'product_id' => $request->product_id,
        ]);

      
        $product = Product::findOrFail($request->product_id);
        $product->like += 1;
        $product->save();

        return response()->json(['status' => 200, 'data' => $favorite], 200);
    }



    public function update(Request $request, $id)
    {
        $favorite = Favorite::findOrFail($id);

        $favorite->update([
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status' => 200, 'data' => $favorite], 200);
    }

    public function destroy($id)
    {
        $favorite = Favorite::findOrFail($id);

       
        if ($favorite->exists()) {
            $product = Product::findOrFail($favorite->product_id);
            $product->like -= 1;
            $product->save();
        }

        $favorite->delete();

        return response()->json(['status' => 200, 'message' => 'Favorite deleted successfully'], 200);
    }
}
