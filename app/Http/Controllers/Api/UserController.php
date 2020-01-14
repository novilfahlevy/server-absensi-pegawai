<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['token'] = $user->createToken('Passport Token')->accessToken;
            return response()->json(['status' => 200, 'message' => $success]);
        }
    }
}
