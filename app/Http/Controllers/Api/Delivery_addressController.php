<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery_address;
use Illuminate\Http\Request;

class Delivery_addressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveryAddresses = Delivery_address::with(['province', 'district', 'ward'])
            ->where('user_id', auth()->user()->id)
            ->get();

        // Tạo mảng mới để chứa dữ liệu đã mở rộng với tên của tỉnh, huyện và phường
        $formattedAddresses = [];
        foreach ($deliveryAddresses as $address) {
            $formattedAddress = [
                'id' => $address->id,
                'name' => $address->name,
                'phone_number' => $address->phone_number,
                'address' => $address->address,
                'idProvince' => $address->province->province_id,
                'province' => $address->province->name,
                'idDistrict' => $address->district->district_id,
                'district' => $address->district->name,
                'idWard' => $address->ward->wards_id,
                'ward' => $address->ward->name,
            ];
            $formattedAddresses[] = $formattedAddress;
        }

        // Sắp xếp mảng theo thời gian gần nhất
        $formattedAddresses = collect($formattedAddresses)->sortByDesc('id')->values()->all();

        return response()->json(['data' => $formattedAddresses]);
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
        $validatedData = $request->validate([
            'idProvince' => 'required|integer',
            'idDistrict' => 'required|integer',
            'idWard' => 'required|integer',
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'ship' => 'integer'
        ]);

        // Lấy ID người dùng hiện tại từ Auth
        $validatedData['user_id'] = auth()->user()->id;

        // Tạo địa chỉ giao hàng mới
        $deliveryAddress = Delivery_address::create($validatedData);

        return response()->json(['message' => 'Delivery address created', 'data' => $deliveryAddress]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery_address $deliveryAddress)
    {
        if ($deliveryAddress->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['data' => $deliveryAddress]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery_address $delivery_address)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'idProvince' => 'required|integer',
            'idDistrict' => 'required|integer',
            'idWard' => 'required|integer',
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'ship' => 'integer'
        ]);

        // Find the delivery address by ID and user ID
        $deliveryAddress = Delivery_address::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        // If the delivery address does not exist or does not belong to the authenticated user, return an error response
        if (!$deliveryAddress) {
            return response()->json(['error' => 'Delivery address not found'], 404);
        }

        // Update the delivery address with the validated data
        $deliveryAddress->update($validatedData);

        return response()->json(['message' => 'Delivery address updated', 'data' => $deliveryAddress]);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deliveryAddress = Delivery_address::findOrFail($id);

        if ($deliveryAddress->user_id != auth()->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deliveryAddress->delete();

        return response()->json(['status' => 200, 'message' => 'Delivery address deleted successfully'], 200);
    }
}
