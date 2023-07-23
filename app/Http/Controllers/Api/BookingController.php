<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $bookingDate = $request->input('booking_date') ? Carbon::parse($request->input('booking_date'))->format('Y-m-d') : null;
        $orderBy = $request->input('order_by');
        $orderOptions = [
            '1' => 'created_at',
            '2' => 'booking_time',

        ];

        if (!isset($orderOptions[$orderBy])) {
            $orderBy = '1';
        }

        $userBookings = Booking::where('customer_id', auth()->user()->id)
            ->when($bookingDate, function ($query) use ($bookingDate) {
                return $query->whereDate('booking_time', $bookingDate);
            })
            ->where('status', true)
            ->orderByDesc($orderOptions[$orderBy])
            ->get();

        $updatedBookings = $userBookings->map(function ($booking) {
            $bookingTime = Carbon::parse($booking->booking_time)->startOfDay();
            $currentTime = Carbon::now()->startOfDay();

            if ($bookingTime->gt($currentTime)) {
                $booking->color = '2';
            } elseif ($bookingTime->eq($currentTime)) {
                $booking->color = '1';
            } else {
                $booking->color = '0';
            }

            return $booking;
        });

        $data = [
            'status' => 200,
            'data' => $updatedBookings,
            'total_items' => $updatedBookings->count(),
        ];

        return response()->json($data, 200);
    }

    public function updateBookingStatus($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['status' => 404, 'message' => 'Booking not found.'], 404);
        }

        $booking->status = false;
        $booking->save();

        return response()->json(['status' => 200, 'message' => 'Booking status updated successfully.'], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }








    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_time' => 'required|date',
            'note' => 'nullable|string'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {
            $booking = new Booking();
            $booking->customer_id = auth()->user()->id;
            $booking->mechanic_id = 0;
            $booking->booking_time = $request->input('booking_time');
            $booking->note = $request->input('note');
            $booking->address = $request->input('address');
            $booking->service = $request->input('service');


            $booking->save();

            return response()->json(['message' => 'create successful'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }




    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_time' => 'required|date',
            'note' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        try {
            $booking = new Booking();
            $booking->customer_id = auth()->user()->id;
            $booking->mechanic_id = 0;
            $booking->booking_time = $request->input('booking_time');
            $booking->note = $request->input('note');
            $booking->address = $request->input('address');
            $booking->service = $request->input('service');

            $booking->save();

            return response()->json(['message' => 'Create successful'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong!'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);


        if ($booking->customer_id !== auth()->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $booking->fill($request->all());
        $booking->save();

        return response()->json(['message' => 'Booking updated successfully'], 200);
    }
}
