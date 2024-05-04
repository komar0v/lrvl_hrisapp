<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User\UserMdls as M_User;;

class Account extends Controller
{
    public function login(Request $request)
    {
        $requestData = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                "status" => false,
                "message" => "Email atau Password Anda salah"
            ], 401);
        }

        $expiredTime = auth()->guard('api')->payload();

        return response()->json([
            "status" => true,
            "login_at" => now()->format('Y-m-d H:i:s'),
            "expired_at" => date('Y-m-d H:i:s', $expiredTime('exp')),
            // "id"=>auth()->guard('api')->id(),
            "user"    => auth()->guard('api')->user(),
            "token"   => $token
        ], 200);
    }

    public function logout()
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            //return response JSON
            return response()->json([
                "status" => true,
                "message" => "Logout Berhasil!",
            ], 200);
        }
    }

    public function me()
    {
        $user = M_User::findOrFail(JWTAuth::user()->id);

        return response()->json($user, 200);
    }
}
