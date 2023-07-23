<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OtpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function addOtp(Request $request)
    {
        try {
            $email = $request->input('email');
            // Kiểm tra xem email đã tồn tại trong bảng User hay chưa
            // $existingUser = User::where('email', $email)->exists();
            // if ($existingUser) {
            //     // Xóa các bản ghi có cùng email trong bảng Otp trước khi tạo bản ghi mới

            // }
            Otp::where('email', $email)->delete();

            $otp = new Otp();
            $otp->email = $email;
            $otp->otp = $request->input('otp'); // Hàm generateOtp() là hàm tạo mã OTP
            $otp->created_at = Carbon::now(); // Gán giá trị cho trường created_at
            $otp->save();

            return response()->json([
                'message' => 'OTP added successfully',
            ], 200);
            // return response()->json([
            //     'message' => 'Email already exists',
            // ], 404);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    public function checkOtp(Request $request)
    {
        try {
            $email = $request->input('email');
            $otp = $request->input('otp');

            $otpRecord = Otp::where('email', $email)->where('otp', $otp)->first();

            if ($otpRecord) {
                $otpRecord->delete();
                return response()->json([
                    'message' => 'OTP is valid',
                ], 200);
            }
            return response()->json([
                'message' => 'Invalid OTP',
            ], 404);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Otp $otp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Otp $otp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Otp $otp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Otp $otp)
    {
        //
    }
}