<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            // 'password' => 'required',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/'
            ],
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['email'] = strtolower($input['email']);
        $input['password'] = bcrypt($input['password']);
        $input['login'] = 1;

        $existingUser = User::where('email', $input['email'])->first();

        if ($existingUser) {
            if ($existingUser->login == 2) {
                $input['name'] = $existingUser->name;                   
                // Thực hiện cập nhật dữ liệu người dùng
                $existingUser->update($input);
                return response()->json(['status' => 200, 'message' => 'Cập nhật thành công'], 200);
            } else {
                return response()->json(['status' => 400, 'message' => 'Tài khoản đã tồn tại'], 400);
            }
        }

        // Tạo người dùng mới
        $user = User::create($input);

        return response()->json(['status' => 200, 'message' => 'Đăng ký thành công'], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken', ['expires_in' => 10])->plainTextToken;
            return response()->json([
                'status' => 200,
                'token' => 'Bearer ' . $token, 'id user' => auth()->user()->id
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function exampleFunction(Request $request)
    {
        if (auth('api')->check()) {
            // Token hợp lệ, xử lý logic ở đây
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    /**
     * Details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }
}
