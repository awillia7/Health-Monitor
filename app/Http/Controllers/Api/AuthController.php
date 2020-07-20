<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $loginData["samaccountname"] = $loginData["username"];
        unset($loginData["username"]);
        
        if (auth()->attempt($loginData))
        {
            $user = auth()->user();
            $success['token'] = $user->createToken('intranet')->accessToken;
            return response()->json([
                'user' => $user,
                'success' => $success
            ], 200);
        }
        else
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
