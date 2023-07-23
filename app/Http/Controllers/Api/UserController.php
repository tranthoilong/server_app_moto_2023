<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function getUserInfo(Request $request)
    {
        // Kiểm tra xem người dùng đã đăng nhập hay chưa
        if (!auth()->check()) {
            return response()->json(['message' => 'Bạn chưa đăng nhập', 'status' => 401], 401);
        }

        // Lấy thông tin user
        $user = auth()->user();

        // Tải ảnh của user và trả về URL của ảnh
        $image_url = null;
        if ($user->image) {
            $image_url = Storage::url("user/{$user->image}");
        }

        // Trả về thông tin user và URL của ảnh
        return response()->json([
            'message' => 'Successful',
            'status' => 200,
            'data' => [
                'id' => $user->id,
                'address' => $user->address,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $image_url,
                'score' => $user->score,
                'gender' => $user->gender
            ]
        ]);
    }


    public function updateScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
        ]);

        $user = Auth::user();
        $user->score += $request->score;
        $user->save();

        return response()->json([
            'message' => 'Score updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreUserRequest $request)
    // {

    //     try {

    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required',
    //             'email' => 'required|email|unique:users',
    //             'password' => 'required',
    //             'phone' => ['required', 'numeric', 'regex:/^(0[2-9]|(1[2-9]|3[2-9]|5[6|8|9]|7[0|6-9]|8[1-5]|9[0-9]))\d{7}$/'],
    //             'c_password' => 'required|same:password',
    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 401);
    //         }
    //         $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
    //         $path = public_path('storage/user');
    //         Storage::disk('public')->put("/user/{$imageName}", file_get_contents($request->image));

    //         User::create([
    //             'name' => $request->name,
    //             'image' => $imageName,
    //             'gender' => $request->input('gender', 1),
    //             'email' => $request->input('email'),
    //             'phone' => $request->input('phone'),
    //             'address' => $request->input('address'),
    //             'password' => Hash::make($request->input('password')),
    //         ]);
    //         return response()->json([
    //             'message' => 'create successful'
    //         ], 200);
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'message' => 'Something went really wrong !'
    //         ], 500);
    //     }
    // }
    public function google(Request $request)
    {
        $email = strtolower($request->input('email'));

        $user = User::where('email', $email)->first();

        if ($user) {
            // Email đã tồn tại
            $token = $user->createToken('authToken', ['expires_in' => 10])->plainTextToken;
            return response()->json([
                'status' => 200,
                'token' => 'Bearer ' . $token,
                'id user' => $user->id,
            ], 200);
        } else {
            // Email chưa tồn tại, tạo tài khoản mới
            $newUser = User::create([
                'name' => $request->input('name'),
                'email' => $email,
                'login' => 2
                // Thêm các trường dữ liệu khác của người dùng nếu cần thiết
            ]);

            if ($newUser) {

                $token = $newUser->createToken('authToken', ['expires_in' => 10])->plainTextToken;
                return response()->json([
                    'status' => 200,
                    'token' => 'Bearer ' . $token,
                    'id user' => $newUser->id,
                ], 200);
            } else {
                return response()->json([
                    'exists' => false,
                    'message' => 'Failed to create a new account.',
                ], 500);
            }
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }
    public function checkMail(Request $request)
    {
        // $user = DB::table('users')->where('email', $request->email)->first();
        $type = DB::table('users')->where('login', 1)->where('email', $request->email)->first();

        if ($type) { // Kiểm tra cả hai điều kiện
            return response()->json([
                'message' => 'Email tồn tại và login = 1',
            ], 200);
        }


        return response()->json([
            'message' => 'Email không tồn tại',
        ], 404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }
    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => [
                    'required',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
                ],
            ]);

            $user = DB::table('users')->where('email', $request->email)->first();

            if ($user) {
                $input['password'] = bcrypt($request->input('password'));
                DB::table('users')->where('email', $request->email)->update($input);

                return response()->json([
                    'message' => 'Password updated successfully',
                ], 200);
            }

            return response()->json([
                'message' => 'User not found',
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred: ' . $th->getMessage(),
            ], 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            $user->name = $request->input('name', $user->name);
            // $user->email = $request->input('email', $user->email);

            // Kiểm tra và lưu số điện thoại nếu có
            $phone = $request->input('phone');
            if ($phone !== null) {
                if (!preg_match('/^0\d{9}$/', $phone) || strlen($phone) !== 10) {
                    return response()->json([
                        'message' => 'Invalid phone number. Phone number should start with 0 and have exactly 10 digits.'
                    ], 400);
                }
            }
            $user->phone = $phone;

            $user->gender = $request->input('gender', $user->gender);
            $user->address = $request->input('address', $user->address);

            if ($request->hasFile('image')) {
                $storage = Storage::disk('public');

                if ($storage->exists($user->image)) {
                    $storage->delete($user->image);
                }
                $imageName = Str::random(32) . "." . $request->image->getClientOriginalExtension();
                $storage->put("/user/{$imageName}", file_get_contents($request->image));
                $path = $request->image->storeAs('public/storage/user', $imageName);
                $user->image = $imageName;
                $user->image = $path;
            }
            $user->save();

            return response()->json([
                'message' => 'Update successful',
                'data' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'image' => $user->image,
                    'gender' => $user->gender,
                    'address' => $user->address
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went really wrong!'
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
