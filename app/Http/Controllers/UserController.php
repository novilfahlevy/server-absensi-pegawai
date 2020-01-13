<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUserRequest;
use App\User;
use App\Role;
use ApiResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function index(){

        $user = User::all();

        return response()->json([ 'status' => '200', 'message' => 'Sukses','user' => $user]);
    }

    public function store(RegisterUserRequest $request){

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $role = Role::find(2);
        $user->assignRole($role);

        return response()->json([ 'status' => '200', 'message' => 'Sukses','user' => $user]);
    }
}
