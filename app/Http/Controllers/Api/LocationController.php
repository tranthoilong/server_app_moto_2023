<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function getProvinces()
    {
        $provinces = Province::all()->toArray();
        return response()->json(['data' => $provinces]);
    }

    public function getDistrictsByProvince($provinceId)
    {
        $districts = District::where('province_id', $provinceId)->get()->toArray();
        return response()->json(['data' => $districts]);
    }

    public function getWardsByDistrict($districtId)
    {
        $wards = Ward::where('district_id', $districtId)->get()->toArray();
        return response()->json(['data' => $wards]);
    }
    public function getData()
    {
        $provinces = Province::with('districts.wards')->get()->toArray();
        $data = [];

        foreach ($provinces as $province) {
            $formattedDistricts = [];
            foreach ($province['districts'] as $district) {
                $formattedWards = [];
                foreach ($district['wards'] as $ward) {
                    $formattedWards[] = [
                        'wards_id' => $ward['wards_id'],
                        'district_id' => $ward['district_id'],
                        'name' => $ward['name']
                    ];
                }
                $formattedDistricts[] = [
                    'district_id' => $district['district_id'],
                    'province_id' => $district['province_id'],
                    'name' => $district['name'],
                    'wards' => $formattedWards
                ];
            }
            $data[] = [
                'province_id' => $province['province_id'],
                'name' => $province['name'],
                'districts' => $formattedDistricts
            ];
        }

        return response()->json(['data' => $data]);
    }
}
