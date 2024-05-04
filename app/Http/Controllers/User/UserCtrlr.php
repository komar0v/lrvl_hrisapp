<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserMdls as M_User;
use Illuminate\Http\Request;

class UserCtrlr extends Controller
{
    public function registerUser(Request $req){
        $req->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users_tbl,email',
            'password' => 'required|min:6',
        ]);
    
        // Create a new user
        $user = M_User::create([
            'nama' => $req->nama,
            'email' => $req->email,
            'password' => bcrypt($req->password),
            'account_type' => 'user'
        ]);
    
        return response()->json(['user' => $user], 201);
    }

    public function getAllUsers(){
        return response()->json(M_User::getAllNonAdminUsers(), 200);
    }
}
