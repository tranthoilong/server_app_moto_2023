<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }
    /**
     * Get all reviews by product id.
     */
    public function getByProductId($id, Request $request)
    {
        $perPage = $request->input('per_page', 30); // Number of items per page
        $page = $request->input('page', 1); // Current page

        $reviews = Review::with(['user' => function ($query) {
            $query->select('id', 'name', 'image');
        }])
            ->where('product_id', $id)
            ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.*', 'users.name', 'users.image')
            ->orderBy('created_at', 'desc') // Sort by created_at in descending order
            ->paginate($perPage, ['*'], 'page', $page);

        // Iterate through the reviews to get the URL of the user's image
        foreach ($reviews as $review) {
            $user = $review->user;
            if ($user && $user->image) {
                $user->image_url = Storage::url("user/{$user->image}");
            }
        }

        $totalRating = Review::where('product_id', $id)
            ->sum('rating');

        $commentCount = $reviews->total();

        $averageRating = $commentCount > 0 ? $totalRating / $commentCount : 0;

        $totalStars = min(5, $totalRating); // Ensure total stars is capped at 5

        return response()->json([
            'data' => $reviews->items(),
            'total_stars' => $totalStars,
            'total_pages' => $reviews->lastPage(),
            'current_page' => $reviews->currentPage(),
        ]);
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
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:0|max:5',
            'time' => 'required|string',

        ]);

        $user = auth()->user();
        $reviewData = $request->all();
        $reviewData['user_id'] = $user->id;

        $review = Review::create($reviewData);
        return response()->json(['data' => $review], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:0|max:5',
        ]);

        $user = auth()->user();
        $review = Review::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->comment = $request->input('comment');
        $review->rating = $request->input('rating');
        $review->save();

        return response()->json(['data' => $review], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $review = Review::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], 200);
    }
}
