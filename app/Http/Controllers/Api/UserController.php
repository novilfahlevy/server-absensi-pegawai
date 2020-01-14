<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\User;
use App\Role;
use App\Helpers\ApiResponse;

class UserController extends Controller
{
    public function index()
    {

        $user = User::all();

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function show($id){
        $user = User::with('roles')->where('id',$id)->first();

        return response()->json(['status' => '200' , 'message' => 'sukses', 'user' => $user]);
    }


    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['id'] = $user->id;
            $success['name'] = $user->name;
            $success['token'] = $user->createToken('Passport Token')->accessToken;
            return response()->json(['status' => 200, 'message' => $success]);
        } else {
            return response()->json(['status' => 401, 'message' => 'Email atau password salah!']);
        }
    }

    public function store(RegisterUserRequest $request)
    {

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $role = Role::find(2);
        $user->assignRole($role);

        return response()->json(['status' => '200', 'message' => 'Sukses', 'user' => $user]);
    }

    public function unauthorized()
    {
        return response()->json(['status' => 'Unauthorized'],401);
    }

}
