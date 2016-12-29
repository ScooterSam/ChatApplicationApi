<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function login(Request $request)
    {

        $details = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(Auth::attempt($details)) {
            $user = User::whereEmail($request->email)->first();
            return response()->json($user);
        }

        return response()->json(['message' => 'Failed to login!'], 401);
    }
}
